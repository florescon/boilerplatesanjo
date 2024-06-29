<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Station;
use App\Models\Status;
use App\Models\ProductOrder;
use App\Models\Product;
use App\Models\Material;
use App\Models\ProductStation;
use App\Models\BatchProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Carbon\Carbon;
use Exception;
use App\Events\Order\OrderAssignmentCreated;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use App\Domains\Auth\Models\User;

class StationsOrder extends Component
{
    public $order_id, $status_id, $status_name;

    public $q;

    public ?int $user = null;
    public $users;

    public $next_status, $previous_status;

    public $quantity;

    public $quantityFromStock;

    public $quantityFromSupplier;

    public $status;

    public $sumValue = 0;
    public $sumValueStation = [];

    public ?string $date = null;
    public ?string $date_entered = null;

    public $output;

    protected $listeners = [
        'selectedCompanyItem', 
        'makeConsumptionEmited', 
        'makeOutputEmited', 
        'sendToStockEmmited', 
        'save',
        'saveFromSupplier',
        'saveFromInitialProcess',
        'deleteStation', 
        'AmountReceived' => 'render'];

    public function mount(Order $order, Status $status)
    {
        $this->status = $status;
        $this->order_id = $order->id;
        $this->status_id = $status->id;
        $this->status_name = $status->name;

        $this->next_status = Status::where('level', '>', $status->level)->whereActive(true)
                ->oldest('level')
                ->first();
        $this->previous_status = Status::where('level', '<', $status->level)->whereActive(true)
                ->latest('level')
                ->first();
        $this->users = User::admins()->select(['id', 'name'])->orderBy('name')->get();

    }

    public function savePersonalId($stationId, $userId)
    {
        $station = Station::find($stationId);
        if ($station) {
            $station->personal_id = $userId;
            $station->save();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Saved'),
        ]);
    }

    public function saveInStation($station_id)
    {
        $orderModel = Order::with('products')->find($this->order_id);
        $getStation = Station::whereId($station_id)->first();

        if (!empty($this->quantity) && isset($this->quantity[$station_id])) {

            $result = array_filter($this->quantity, function($subArray) {
                $filtered = array_filter($subArray, function($item) {
                    return !empty($item['available']) && $item['available'] != "0";
                });
                return !empty($filtered);
            });

            if(empty($result)){
                return $this->emit('swal:modal', [
                    'icon' => 'info',
                    'title' => __('Add something'),
                ]);
            }

            $quantities = $this->quantity[$station_id];

            foreach ($getStation->product_station as $productStation) {
                if (isset($quantities[$productStation->id])) {
                    $quantity = $quantities[$productStation->id]['available'];

                    if ($this->status->process) {
                        $this->validate([
                            "quantity.$station_id.$productStation->id.available" => 'sometimes|nullable|numeric|integer|gt:0|max:' . $productStation->getAvailableInitialProcess($this->status_id),
                        ]);
                    } else {
                        $this->validate([
                            "quantity.$station_id.$productStation->id.available" => 'sometimes|nullable|numeric|integer|gt:0|max:' . $productStation->getAvailableBatch($this->status_id, $station_id),
                        ]);
                    }
                }
            }

            $batch = new Station([
                'order_id' => $this->order_id,
                'status_id' => $this->status_id,
                'personal_id' => $this->user ?? null,
                'date_entered' => $this->date ?: today(),
                'created_audi_id' => Auth::id(),
                'last_modified_audi_id' => Auth::id(),
                'station_id' => $station_id,
                'active' => true,
            ]);

            $orderModel->stations()->save($batch);

            foreach ($quantities as $key => $product) {
                if (empty($product['available'])) {
                    continue;
                }

                $product_station = ProductStation::where('id', $key)->first();
                $quantityValue = $product_station->metadata['closed'];

                $this->discountQuantityPreviousState($product_station->id, $product['available']);

                if ($this->previous_status->initial_lot) {
                    $product_station->update([
                        'metadata->closed' => $quantityValue - $product['available'],
                    ]);
                }

                if ($this->previous_status->not_restricted) {
                    $previous_status_again = Status::where('level', '<', $this->previous_status->level)->whereActive(true)->latest('level')->first();

                    if ($previous_status_again->initial_process) {
                        $product_station->update([
                            'metadata->closed' => $quantityValue - $product['available'],
                        ]);
                    }
                }

                $product_station->children()->create([
                    'order_id' => $this->order_id,
                    'station_id' => $batch->id,
                    'product_id' => $product_station->product_id,
                    'product_order_id' => $product_station->product_order_id,
                    'status_id' => $this->status_id,
                    'personal_id' => $this->user ?? null,
                    'created_audi_id' => Auth::id(),
                    'quantity' => (int) $product['available'],
                    'metadata' => [
                        'open' => (int) $product['available'],
                        'closed' => 0,
                    ],
                    'active' => true,
                ]);
            }

            $this->resetInput();

            $this->sumValueStation = [];

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title' => __('Saved'),
            ]);
        }

        else {
            return $this->emit('swal:modal', [
                'icon' => 'info',
                'title' => __('Add something'),
            ]);

        }
    }

    public function emitUpdatedInStation($stationId)
    {
        // dd($this->quantity);

        if($this->quantity){
            $this->sumValueStation[$stationId] = $this->calculateSum($this->quantity[$stationId]); 

            return $this->sumValueStation[$stationId];
        }
    }

    public function emitUpdatedQuantity()
    {
        $this->sumValue = $this->calculateSum($this->quantity) + 
                          $this->calculateSum($this->quantityFromStock) + 
                          $this->calculateSum($this->quantityFromSupplier);

        return $this->sumValue;
    }


    private function calculateSum($quantities)
    {
        $sum = 0;
        if (is_array($quantities) || is_object($quantities)) {
            foreach ($quantities as $q) {
                if (isset($q['available']) && is_numeric($q['available'])) {
                    $available = (int) $q['available'];
                    if ($available > 0) {
                        $sum += $available;
                    }
                }
            }
        }
        return $sum;
    }

    private function discountQuantityPreviousState(int $statusForDiscount, ?int $quantity = 0)
    {
        $productsStation = ProductStation::where('order_id', $this->order_id)->where('product_station_id', $statusForDiscount)->get();        

        foreach($productsStation as $productStation)
        {
            $quantityValue = $productStation->metadata['closed'];

            if($quantity >= $quantityValue){
                $productStation->update([
                    'metadata->closed' => $quantityValue - $quantityValue,
                ]);
            }

            abs($quantity -= $quantityValue);
        }
    }

    public function saveFromInitialProcess()
    {
        /* You can use for Inventory */
        $orderModel = Order::with('products')->find($this->order_id);

        /* Inefficient */
        foreach($orderModel->products as $productOrder)
        {
            $product = Product::whereId($productOrder->product_id)->first();

            if(is_array($this->quantityFromStock) && array_key_exists($productOrder->id, $this->quantityFromStock)){

                if($product->type == true){
                    $this->validate([
                        'quantityFromStock.'.$productOrder->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$product->stock,
                    ]);
                }

                $this->validate([
                    'quantityFromStock.'.$productOrder->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$productOrder->available_process,
                ]);
            }
        }

        // dd('s');

        if(!empty($this->quantityFromStock)){

            $station = Station::updateOrCreate(
                [
                    'order_id' => $this->order_id,
                    'status_id' => $this->status->getFirstStatusProcess()->id,
                ],
                [
                    'date_entered' => $this->date ?: today(),
                    'created_audi_id' => Auth::id(),
                    'last_modified_audi_id' => Auth::id(),
                    'active' => true,
                ],
            );


            foreach($this->quantityFromStock as $key => $q){

                $qq = (int) $q['available'];

                $getProductOrder = ProductOrder::whereId($key)->first();

                //Decrement Product
                $decProduct = Product::whereId($getProductOrder->product_id)->first(); 

                if($decProduct->type == true){
                    $this->createHistory($decProduct, $qq, true, 'stock');
                    $decProduct->decrement('stock', abs($qq));
                }

                $getProductStation = ProductStation::where('order_id', $this->order_id)->where('product_id', $getProductOrder->product_id)->where('status_id', $this->status->getFirstStatusProcess()->id)->where('station_id', $station->id)->first();

                if($getProductStation)
                {

                    $getValueOpen = $getProductStation->metadata['open'];

                    $getProductStation->update([
                        'quantity' => DB::raw("IFNULL(quantity, 0) + $qq"),
                        'metadata->open' => $getValueOpen + $qq,
                        'active' => true,
                    ]);
                }
                else
                {
                    $createProductStation = ProductStation::create([
                        'order_id' =>  $this->order_id,
                        'product_id' => $getProductOrder->product_id,
                        'product_order_id' => $getProductOrder->id,
                        'status_id' =>  $this->status->getFirstStatusProcess()->id,
                        'station_id' => $station->id,
                        'personal_id' =>  $this->user ?? null,
                        'created_audi_id' => Auth::id(),
                        'quantity' => $qq,
                        'metadata' => [
                                    'open' => $qq,
                                    'closed' => 0,
                        ],
                        'active' => true,
                    ]);
                }


            }

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);

            $this->quantityFromStock = '';
        }

    }

    public function save()
    {
        $orderModel = Order::with('products')->find($this->order_id);

        // dd($this->quantity);


        /* Inefficient */
        foreach($orderModel->products as $productOrder)
        {
            if(is_array($this->quantity) && array_key_exists($productOrder->id, $this->quantity)){

                $this->validate([
                    'quantity.'.$productOrder->id.'.available' => 'sometimes|nullable|numeric|integer|gt:0|max:'.$productOrder->available_lot,
                ]);
            }
        }

        if(!empty($this->quantity)){

            $batch = new Station([
                'order_id' => $this->order_id,
                'status_id' => $this->status_id,
                'personal_id' => $this->user ?? null,
                'date_entered' => $this->date ?: today(),
                'created_audi_id' => Auth::id(),
                'last_modified_audi_id' => Auth::id(),
                'active' => true,
            ]);

            $orderModel->stations()->save($batch);                

                foreach($this->quantity as $key => $product){

                    if(empty($product['available'])){
                        continue;
                    }

                    /* Set array into statuses */
                    $statuses = array();
                    foreach(Status::orderBy('level')->whereBatch(true)->whereActive(true)->get() as $status){
                        $statuses[] = $status->setVisible(['id', 'short_name'])->toArray();
                    }
                    $array = array();
                    Arr::set($array, 'statuses', $statuses);


                    $product_order = ProductOrder::where('id', $key)->withTrashed()->first();

                    $q = (int) $product['available'];

                    $batch->product_station()->create([
                        'order_id' =>  $this->order_id,
                        'product_id' =>  $product_order->product_id,
                        'product_order_id' => $product_order->id,
                        'status_id' =>  $this->status_id,
                        'personal_id' =>  $this->user ?? null,
                        'created_audi_id' => Auth::id(),
                        'quantity' => (int) $product['available'],
                        'metadata' => [
                                    'open' => (int) $product['available'],
                                    'closed' => 0,
                                    'statuses' => data_fill($statuses, '*.q', $q),
                        ],
                        'active' => true,
                    ]); 
                }

                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('Created'), 
                ]);

            $this->resetInput();
            $this->emitUpdatedQuantity();
            $this->emit('$refresh');
        }
    }


    public function saveFromSupplier()
    {
        $orderModel = Order::with('products')->find($this->order_id);

        $vendorIds = [];

        if ($this->status->supplier) {
            foreach ($orderModel->products as $productOrder) {
                if (isset($this->quantityFromSupplier[$productOrder->id]['available'])) {
                    $quantity = $this->quantityFromSupplier[$productOrder->id]['available'];

                    if ($quantity === "" || $quantity == 0) {
                        continue;
                    }

                    if ($productOrder->product->isProduct()) {
                        if (!$productOrder->product->parent->vendor_id) {
                            return $this->emit('swal:modal', [
                                'icon' => 'error',
                                'title' => __('Supplier Needed'),
                            ]);
                        }

                        $vendorId = $productOrder->product->parent->vendor_id;
                        $vendorIds[] = $vendorId;
                    }
                    else{
                        return $this->emit('swal:modal', [
                            'icon' => 'error',
                            'title' => __('No puedes agregar servicios a los Pedidos a Proveedor'),
                        ]);
                    }

                    $this->validate([
                        'quantityFromSupplier.' . $productOrder->id . '.available' => 'sometimes|nullable|numeric|integer|gt:0|max:' . $productOrder->available_supplier,
                    ]);
                }
            }
        }

        if (count(array_unique($vendorIds)) > 1) {
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('Multiple Vendors Detected'),
            ]);
        }

        if(!empty($this->quantityFromSupplier)){

            $batch = new Station([
                'order_id' => $this->order_id,
                'status_id' => $this->status_id,
                'personal_id' => $this->user ?? null,
                'date_entered' => $this->date ?: today(),
                'created_audi_id' => Auth::id(),
                'last_modified_audi_id' => Auth::id(),
                'active' => true,
            ]);

            $orderModel->stations()->save($batch);

                foreach($this->quantityFromSupplier as $key => $product){

                    if(empty($product['available'])){
                        continue;
                    }

                    /* Set array into statuses */
                    $statuses = array();
                    foreach(Status::orderBy('level')->whereBatch(true)->whereActive(true)->get() as $status){
                        $statuses[] = $status->setVisible(['id', 'short_name'])->toArray();
                    }
                    $array = array();
                    Arr::set($array, 'statuses', $statuses);


                    $product_order = ProductOrder::where('id', $key)->withTrashed()->first();

                    $q = (int) $product['available'];

                    $batch->product_station()->create([
                        'order_id' =>  $this->order_id,
                        'product_id' =>  $product_order->product_id,
                        'product_order_id' => $product_order->id,
                        'status_id' =>  $this->status_id,
                        'personal_id' =>  $this->user ?? null,
                        'created_audi_id' => Auth::id(),
                        'quantity' => (int) $product['available'],
                        'metadata' => [
                                    'open' => (int) $product['available'],
                                    'closed' => 0,
                                    'statuses' => data_fill($statuses, '*.q', $q),
                        ],
                        'active' => true,
                    ]); 
                }

                $this->emit('swal:alert', [
                    'icon' => 'success',
                    'title'   => __('Created'), 
                ]);

            $this->quantityFromSupplier = '';

        }
    }

    public function closeLot($station)
    {
        $stationClose = Station::find($station);
        $children = $stationClose->children()->get();

        foreach($children as $ch){
            foreach($ch->product_station()->get() as $product){
                $product->update([
                    'metadata->closed' => $product->metadata['open'],
                ]);
                $product->update([
                    'metadata->open' => 0,
                ]);
            }
        }
    }

    public function closeStation($station_id)
    {
        $getData = 'Send auto to Initial Process';

        /*
        if(in_array($getData, $this->status->getDataStatus()))
        {
            dd('yes');
        }
        */

        /* Get Station */ 
        $stationClose = Station::find($station_id);

        /* Show error if initial_lot and not make consumption */
        if(!$stationClose->consumption && $this->status->initial_lot){
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title'   => __('Consumption required'),
                'html' => __('It is suggested to verify BOM prior to consumption'),
            ]);
        }

        /* Get all Products Station */
        $productsStation = $stationClose->product_station()->get();

        /* Iterate all Products Station */
        foreach($productsStation as $productStation){

            /** Save products received **/

            if($productStation->metadata['open'] > 0){
                $productStation->product_station_receiveds()->create([
                        'order_id' => $this->order_id,
                    'quantity' => $productStation->metadata['open'],
                    'product_order_id' => $productStation->product_order_id,
                    'status_id' => $this->status_id,
                    'created_audi_id' => Auth::id(),
                ]);
            }

            /* Get value closed */
            $getQuantityClosed = $productStation->metadata['closed'];

            /** Save new value closed **/
            $productStation->update([
                'metadata->closed' =>  $getQuantityClosed + $productStation->metadata['open'],
            ]);

            /* Save new value open*/
            $productStation->update([
                'metadata->open' => 0,
            ]);

            /* Save zero to closed when not_restricted */
            if($this->status->not_restricted){
                $productStation->update([
                    'metadata->closed' =>  0,
                ]);
            }


            /** Send to initial process station **/
            if($this->status->supplier || $this->status->final_lot)
            {
                $station = Station::updateOrCreate(
                    [
                        'order_id' => $this->order_id,
                        'status_id' => $this->status->getFirstStatusProcess()->id,
                    ],
                    [
                        'date_entered' => $this->date ?: today(),
                        'created_audi_id' => Auth::id(),
                        'last_modified_audi_id' => Auth::id(),
                        'active' => true,
                    ],
                );

                if($station){

                    $allZero = $station->product_station->every(function ($productStation) {
                        return $productStation->metadata['open'] == 0;
                    });

                    $getProductStation = ProductStation::where('order_id', $this->order_id)
                        ->where('product_id', $productStation->product_id)
                        ->where('status_id', $this->status->getFirstStatusProcess()->id)
                        ->where('station_id', $station->id)->first();

                    // ->where('product_station_id', $productStation->id)

                    if($productStation->metadata['closed'] > 0){
                        if($getProductStation)
                        {
                            $getValueOpen = $getProductStation->metadata['open'];
                            
                            $metadataProductStation = $getProductStation->metadata_product_station;

                            if (is_null($metadataProductStation)){

                                if (!isset($metadataProductStation[$getProductStation->station_id])) {
                                    $metadataProductStation[$getProductStation->station_id] = 0;
                                }
                                $metadataProductStation[$getProductStation->station_id] = $getProductStation->quantity;

                                $getProductStation->update([
                                    'metadata_product_station' => $metadataProductStation
                                ]);

                            }

                            $metadataProductStation = $getProductStation->metadata_product_station;

                            // Si la clave station_id no existe en el json, inicializa con 0
                            if (!isset($metadataProductStation[$station_id])) {
                                $metadataProductStation[$station_id] = 0;
                            }

                            $metadataProductStation[$station_id] += $productStation->quantity;


                            $getProductStation->update([
                                'quantity' => DB::raw("IFNULL(quantity, 0) + $productStation->quantity"),
                                'metadata->open' => $getValueOpen + $productStation->quantity,
                                'metadata_product_station' => $metadataProductStation,
                                'active' => true,
                            ]);                        
                        }
                        else
                        {
                            $createProductStation = ProductStation::create([
                                'order_id' =>  $this->order_id,
                                'product_id' => $productStation->product_id,
                                'product_order_id' => $productStation->product_order_id,
                                'status_id' =>  $this->status->getFirstStatusProcess()->id,
                                'station_id' => $station->id,
                                'personal_id' =>  $this->user ?? null,
                                'created_audi_id' => Auth::id(),
                                'quantity' => (int) $productStation->quantity,
                                'product_station_id' => $productStation->id,
                                'metadata' => [
                                            'open' => (int) $productStation->quantity,
                                            'closed' => 0,
                                ],
                                'active' => true,
                            ]);
                        }
                    }

                    $productStation->update([
                        'metadata->closed' => 0,
                    ]);

                }
            }
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);
    }

    public function makeToggleExtra($stationId, $value)
    {
        $station = Station::find($stationId);

        if (!$station) {
            return;
        }

        foreach ($station->product_station as $productStation) {
            $metadata = $productStation->metadata_extra ?? [];

            // Toggle the $value value
            $metadata[$value] = isset($metadata[$value]) ? !$metadata[$value] : true;

            $productStation->update(['metadata_extra' => $metadata]);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Processed'),
        ]);
    }

    public function makeToggleNotConsider($stationId)
    {
        $station = Station::find($stationId);

        if (!$station) {
            return;
        }

        foreach ($station->product_station as $productStation) {
            $not_consider = $productStation->not_consider ? false : true;
            $productStation->update(['not_consider' => $not_consider]);
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Processed'),
        ]);
    }


    public function makeConsumptionEmited($stationId)
    {
        $station = Station::with('product_station.product_order.consumption_filter', 'product_station.product_order.material')->find($stationId);
        $order = Order::with('user')->find($this->order_id);

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

            $ordercollection->push([
                'id' => $order->id,
                'folio' => $order->folio,
                'user' => optional($order->user)->name,
                'type' => $order->characters_type_order,
                'comment' => $order->comment,
            ]);

            foreach($station->product_station as $product_statione){
                $quantity = $product_statione->quantity;

                if($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty'){
                    foreach($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $order->id,
                            'product_order_id' => $product_statione->product_order->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'family' => $consumption['family'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
                        ]);
                    }
                }
            }

        $materials = $consumptionCollect->groupBy('material_id')->map(function ($row) {
                    return [
                        'order' => $row[0]['order'],
                        'product_order_id' => $row[0]['product_order_id'], 
                        'material_name' => $row[0]['material_name'],
                        'part_number' => $row[0]['part_number'],
                        'material_id' => $row[0]['material_id'],
                        'unit' => $row[0]['unit'],
                        'unit_measurement' => $row[0]['unit_measurement'],
                        'vendor' => $row[0]['vendor'],
                        'family' => $row[0]['family'],
                        'quantity' => $row->sum('quantity'),
                        'stock' => $row[0]['stock'],
                    ];
                });


        $allMaterials = $materials->map(function ($product) {
            return [
                'order'            => $product['order'],
                'material_name' => $product['material_name'],
                'part_number'         => $product['part_number'],
                'unit_measurement' => $product['unit_measurement'],
                'quantity' => $product['quantity'],
            ];
        });

        // Colección para almacenar los errores.
        $errors = collect();

        // Verificar si el stock es menor a la cantidad requerida.
        foreach ($allMaterials as $materialId => $material) {
            $materialModel = Material::find($materialId);

            if (!$materialModel || $materialModel->stock < $material['quantity']) {
                $errors->push([
                    'material_name' => $material['material_name'],
                    'part_number' => $material['part_number'],
                    'required_quantity' => $material['quantity'],
                    'unit_measurement' => $material['unit_measurement'],
                    'available_stock' => $materialModel->stock ?? 0,
                ]);
            }
        }

        // Si hay errores, emitir todos los errores.
        if ($errors->isNotEmpty()) {
            $errorMessages = $errors->map(function ($error) {
                return __("
                    <br>
                    <b>{$error['material_name']}</b> (Código: {$error['part_number']}) <br> Cantidad Requerida: {$error['required_quantity']} {$error['unit_measurement']}, <br> Existencia: {$error['available_stock']} {$error['unit_measurement']}");
            })->implode('<br><br>');

            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('Lack of raw materials'),
                'html' => $errorMessages, 
                'footer' => "<a class='text-danger' href='/admin/material' target='_blank'>Ir a materia prima <i class='fas fa-external-link-alt m-1'></i></a>",

            ]);
        }

        // Lógica para el consumo de materiales...
        $station->update([
            'consumption' => true,
        ]);


        foreach($station->product_station as $product_statione){

            $quantity = $product_statione->quantity;

            if($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty'){

                foreach($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption){

                    $order->materials_order()->create([
                        'station_id' => $station->id,
                        'product_order_id' => $product_statione->product_order->id,
                        'material_id' => $key,
                        'price' => $consumption['price'],
                        'unit_quantity' => $consumption['unit'],
                        'quantity' => $consumption['quantity'],
                    ]);
                }
            }
        }


        return $this->emit('swal:modal', [
            'icon' => 'success',
            'title' => __('Materials consumption processed successfully'),
        ]);
    }

    public function makeConsumption($stationId)
    {
        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => 'El consumo de materiales se procesará',
            'html' => 'Consumo del Lote seleccionado',
            'confirmText' => '¿Desea confirmar?',
            'method' => 'makeConsumptionEmited',
            'params' => $stationId,
        ]);
    }

    public function makeOutput($stationId)
    {
        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => 'La salida se efectuará',
            'html' => 'Salida',
            'confirmText' => '¿Desea confirmar?',
            'method' => 'makeOutputEmited',
            'params' => $stationId,
        ]);
    }

    public function sendToStock($stationId)
    {
        $station = Station::with([
            'product_station',
            'product_station.product.parent',
            'product_station.product.color',
            'product_station.product.size'
        ])->find($stationId);

        $productsWithClosed = $station->product_station->filter(function($productStation) {
            return isset($productStation->metadata['closed']) && $productStation->metadata['closed'] > 0;
        });

        if ($productsWithClosed->isEmpty()) {
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('No quantities available'),
            ]);
        }

        $sortedProducts = $productsWithClosed->sortBy([
            ['product.parent.name', 'asc'],
            ['product.color.name', 'asc'],
            ['product.size.sort', 'asc']
        ]);

        $productList = $sortedProducts->map(function($productStation) {
            return  '<em class="text-danger">' . $productStation->metadata['closed'] . '</em>  &nbsp;&nbsp;' .$productStation->product->full_name;
        })->implode('<br> ');

        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => '¿Enviar todo a almacén?',
            'html' => $productList,
            'confirmText' => '¿Desea confirmar?',
            'method' => 'sendToStockEmmited',
            'params' => $stationId,
        ]);
    }

    public function sendToStockEmmited($stationId)
    {
        $station = Station::with('product_station')->find($stationId);

        foreach($station->product_station as $productStation)
        {
            // $getProductOrder = ProductOrder::whereId($productStation->product_order_id)->first();
            // $getProductOrder->increment('out', abs($productStation->metadata['closed']));

            if(abs($productStation->metadata['closed']) > 0){
                $productStation->product_station_outs()->create([
                    'order_id' => $this->order_id,
                    'out_quantity' => abs($productStation->metadata['closed']),
                    'type_out' => 'inventary',
                    'created_audi_id' => Auth::id(),
                    'product_order_id' => $productStation->product_order_id,
                ]);
            }

            $product_increment = Product::where('id', $productStation->product_id)->first();

            if($product_increment->isProduct()){
                $this->createHistory($product_increment, $productStation->metadata['closed'], false, 'stock');
                $product_increment->increment('stock', abs($productStation->metadata['closed']));
            }

            $productStation->update([
                'metadata->closed' => 0,
            ]);
        }

        return $this->emit('swal:modal', [
            'icon' => 'info',
            'title' => __('Enviado a almacén'),
        ]);
    }

    public function createHistory($product, $stock, bool $isOutput = false, string $typeStock)
    {
        $product->history_subproduct()->create([
            'product_id' => optional($product->parent)->id ?? null,
            'old_stock' => $product->$typeStock,
            'stock' => $stock,
            'type_stock' => $typeStock,
            'is_output' => $isOutput,
            'audi_id' => Auth::id(),
        ]);
    }

    public function makeOutputEmited($stationId)
    {
        $station = Station::with('product_station')->find($stationId);

        $allZero = $station->product_station->every(function ($productStation) {
            return $productStation->metadata['open'] == 0;
        });

        if ($allZero) {
            foreach ($station->product_station as $productStation) {

                // $product = ProductOrder::whereId($productStation->product_order_id)->first();
                // $product->increment('out', abs($productStation->metadata['closed']));

                if(abs($productStation->metadata['closed']) > 0){
                    $productStation->product_station_outs()->create([
                        'order_id' => $this->order_id,
                        'out_quantity' => abs($productStation->metadata['closed']),
                        'type_out' => 'final',
                        'created_audi_id' => Auth::id(),
                        'product_order_id' => $productStation->product_order_id,
                    ]);
                }

                $productStation->update([
                    'metadata->closed' => 0,
                ]);
            }

            return $this->emit('swal:modal', [
                'title' => __('Done'),
                'imageUrl' => asset('img/ok.jpg'),
            ]);
        } else {
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('Error'),
                'html' => __('Not all metadata inputs are zero.'),
            ]);
        }
    }

    public function createInfo($getMethod)
    {
        abort_if(!in_array($getMethod, ['save', 'saveFromSupplier', 'saveFromInitialProcess']), Response::HTTP_NOT_FOUND);

        $this->emitUpdatedQuantity();

        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => '¿Crear?',
            'html' => 'Capturado: '.$this->sumValue.' productos',
            'confirmText' => '¿Desea confirmar?',
            'method' => (string) $getMethod,
        ]);
    }

    public function resetInput()
    {
        $this->quantity = '';
    }

    public function deleteStation($idStation)
    {
        if((int) $idStation){

            $station = Station::find($idStation);

            if($station->hasBatch() && !$station->hasInitialLot()){
                $updateStation = Station::findOrFail($station->station_id);
                $updateStation->update(['active' => true]);

                foreach ($station->product_station as $productStation) {
                    $closed = $productStation->metadata['closed'];
                    $open = $productStation->metadata['open'];

                    $sumMetadata = $closed + $open;

                    $updateProductStation = $productStation->findOrFail($productStation->product_station_id);

                    $getClosedValue = $updateProductStation->metadata['closed'];

                    $updateProductStation->update([
                        'metadata->closed' => $getClosedValue + $sumMetadata,
                        'active' => true,
                    ]);
                }
            }

            $station->delete();

           $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Deleted'), 
            ]); 
        }
        else{
           $this->emit('swal:alert', [
                'icon' => 'error',
                'title'   => __('Error'), 
            ]); 
        }     
    }

    public function makeDeletion($idStation)
    {
        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => '¿Realmente desea eliminar?',
            'html' => '
                    La eliminación afecta: <br><br>
                    <b>- Cantidades recibidas</b>.<br>
                    <b>- Folios de Estaciones relacionadas, sólo aquellas consecuentes de seguimiento y lo que le corresponde</b>.<br>
                    <b>- Consumos</b>.<br>
                    ',
            'confirmText' => '¿Desea confirmar?',
            'method' => 'deleteStation',
            'params' => $idStation,
        ]);
    }

    public function render()
    {
        $model = Order::with([
            'stations.product_station.product.color',
            'stations.product_station.product.size',
            'stations.product_station.product.parent',

            'products.lot_product',
            'products.product_station_received',

            'lot_stations.product_station.product.color', 
            'lot_stations.product_station.product.size', 
            'lot_stations.product_station.product.parent',
        ])->findOrFail($this->order_id);


        return view('backend.order.livewire.stations')->with([
            'model' => $model,
        ]);
    }
}
