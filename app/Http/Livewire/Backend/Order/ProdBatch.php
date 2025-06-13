<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\ProductionBatch;
use App\Models\Status;
use App\Models\Order;
use App\Models\ProductOrder;
use App\Models\Material;
use App\Models\ServiceType;
use App\Domains\Auth\Models\User;

class ProdBatch extends Component
{
    public ProductionBatch $productionBatch;
    public array $receivedQuantities = [];
    public array $sendQuantities = [];
    public bool $selectAll = false;
    public $getStatusCollection;
    public $order;
    public $status;
    public ?int $user = null;
    public $users;
    public $date_entered;

    public $notes;
    public $isNote;

    public $buttonDisabled = false;

    public $next_status, $previous_status;

    public bool $showSentToStock = false;

    public $selectedServiceType;
    public $inputOptions = [];

    protected $listeners = [
        'makeConsumptionEmited', 'receiveAll',
        'saveInvoiceDate',
        'enableButton' => 'enableButton',
        'renderview' => 'render'
    ];

    public function mount()
    {
        $idStatus = $this->productionBatch->status_id;
        $this->status = Status::find($idStatus);
        $this->users = User::admins()->select(['id', 'name'])->orderBy('name')->get();

        $this->isNote = $this->productionBatch->notes;
        $this->initnote($this->productionBatch);

        $this->order = Order::find($this->productionBatch->order_id);
        $this->getStatusCollection = $this->status->getStatusCollection();

        // Inicializar el array con las cantidades recibidas para cada item
        foreach ($this->productionBatch->items as $item) {
            $this->receivedQuantities[$item->id] = '';
        }

        $this->loadInitialData($this->status);

        $this->date_entered = optional($this->productionBatch->date_entered)->format('Y-m-d');

        $this->selectedServiceType = $this->productionBatch->service_type_id;

        // Cargar las opciones del select
        $this->inputOptions = ServiceType::orderBy('name')
                                        ->pluck('name', 'id')
                                        ->toArray();
    }

    public function updatedSelectedServiceType($value)
    {
        // Actualizar el modelo cuando cambia la selección
        $this->productionBatch->update([
            'service_type_id' => $value
        ]);
        
        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Saved'),
        ]);
    }

    private function initnote(ProductionBatch $product)
    {
        $this->notes = $product->notes;
        $this->isNote = $product->notes ?? false;
    }

    public function savenote()
    {
        $this->validate([
            'notes' => ['min:1', 'max:256'],
        ]);

        $product = ProductionBatch::findOrFail($this->productionBatch->id);

        $product->notes = $this->notes ?? null;
        $product->save();

        $this->initnote($product); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    protected function loadInitialData($status)
    {
        $this->next_status = Status::where('level', '>', $status->level)
            ->whereActive(true)
            ->oldest('level')
            ->first();
            
        $this->previous_status = Status::where('level', '<', $status->level)
            ->whereActive(true)
            ->latest('level')
            ->first();
    }

    public function enableButton()
    {
        sleep(3); // Espera 3 segundos (no recomendado para producción)
        $this->buttonDisabled = false;
        
        // Mejor alternativa para producción:
        // Usar un dispatch browser event con setTimeout en el frontend
    }

    public function savePersonalId($stationId, $userId)
    {
        $station = ProductionBatch::find($stationId);
        if ($station) {
            $station->personal_id = $userId;
            $station->save();
            
            // Actualizar la propiedad local
            $this->productionBatch = $station->fresh(); // Recargar el modelo con los cambios
            $this->user = $userId; // Actualizar la propiedad user
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Saved'),
        ]);
    }

    public function updatedDateEntered($value)
    {
        if (!$value) {
            return; // Evita guardar si está vacío
        }

        $this->productionBatch->date_entered = $value;
        $this->productionBatch->save(); // Guarda automáticamente sin necesidad de submit

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->productionBatch->items as $item) {
                $this->receivedQuantities[$item->id] = $item->input_quantity - $item->output_quantity;
            }
        } else {
            foreach ($this->productionBatch->items as $item) {
                $this->receivedQuantities[$item->id] = 0;
            }
        }
    }

    private function requiredConsumption()
    {
        if($this->getStatusCollection['initial_lot'] && !$this->productionBatch->consumption){
            $this->emit('swal:alert', [
               'icon' => 'error',
                'title'   => __('Consumption required'), 
            ]);

            return false;

            // abort(403, __('Consumption required') . ' :(');
        }

        return true;
    }

    public function receiveAll($stationId)
    {

        if(!$this->requiredConsumption()) {
            return;
        }

        $dataToSave = [];

        foreach ($this->productionBatch->items as $item) {
            $maxQuantity = $item->input_quantity - $item->output_quantity;
            if ($maxQuantity > 0) {
                if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']){
                    $dataToSave[] = [
                        'parent_id' => $item->product->parent_id,
                        'color_id' => $item->product->color_id,
                        'size_id' => $item->product->size_id,
                        'quantity' => $maxQuantity,                        
                        'product_id' => $item->product_id,
                    ];
                }

                $item->output_quantity += $maxQuantity;
                $item->save();
            }
        }

        // dd($dataToSave);

        if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']){

             if(!isset($dataToSave[0])){
                $this->emit('swal:alert', [
                    'icon' => 'error',
                    'title'   => __('Nada por recibir'), 
                ]);
                return false;
            }

            $batch = $this->order->createProductionBatch([
                'status_id' => 11, 
                'parent_id' => $dataToSave[0]['parent_id'], 
                'production_batch_items' => $dataToSave,
                'not_restricted' => $this->getStatusCollection['not_restricted'],
                'prev_status' => $this->getStatusCollection['previous_status'], 
                'is_principal' => false, 
                'with_previous' => $this->getStatusCollection['id'] ]);
        }

        if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['final_process'] || $this->getStatusCollection['supplier'] || $this->getStatusCollection['not_restricted']){
            foreach ($this->productionBatch->items as $item) {
                $item->active = 0;
                $item->save();
            }
        }

        $this->reset(['receivedQuantities', 'selectAll']);
        $this->productionBatch->refresh();

        return $this->emit('swal:modal', [
            'icon' => 'success',
            'title' => __('Cantidades recibidas'),
        ]);
    }

    private function verifyIfEmptyReceive()
    {
        $allEmptyOrZero = true;
        foreach ($this->receivedQuantities as $value) {
            if ($value !== "" && $value !== 0 && $value !== "0") {
                $allEmptyOrZero = false;
                break;
            }
        }
        
        if ($allEmptyOrZero) {
            $this->emit('swal:alert', [
                'icon' => 'error',
                'title' => __('No emita datos vacios'), 
            ]);
            return true; // Cambiado a `true` para indicar que está vacío/cero
        }

        return false; // No está vacío/cero
    }


    public function receiveSelected()
    {

        if ($this->verifyIfEmptyReceive()) {
            return false; // Detiene la ejecución si está vacío/cero
        }
        
        if(!$this->getErrorNotRestricted()) {
            return false;
        }

        $this->requiredConsumption();

        $this->buttonDisabled = true;

        $dataToSave = [];

        foreach ($this->productionBatch->items as $item) {
            $maxAllowed = $item->input_quantity - $item->output_quantity;
            $quantityToAdd = $this->receivedQuantities[$item->id] ?? 0;

            // Validar que la cantidad no exceda el máximo permitido
            if ($quantityToAdd > 0 && $quantityToAdd <= $maxAllowed) {


                if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']){

                    $dataToSave[] = [
                        'parent_id' => $item->product->parent_id,
                        'color_id' => $item->product->color_id,
                        'size_id' => $item->product->size_id,
                        'quantity' => $quantityToAdd,                        
                        'product_id' => $item->product_id,
                    ];
                }


                $item->output_quantity += $quantityToAdd;

                if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier'] || $this->getStatusCollection['final_process'] || $this->getStatusCollection['not_restricted']){
                    $item->active -= $quantityToAdd;
                }
                $item->save();
            }
        }

        if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']){
            
            if(!isset($dataToSave[0])){
                $this->emit('swal:alert', [
                    'icon' => 'error',
                    'title'   => __('Nada por recibir'), 
                ]);
                return false;
            }

            $batch = $this->order->createProductionBatch([
                'status_id' => 11, 
                'parent_id' => $dataToSave[0]['parent_id'], 
                'production_batch_items' => $dataToSave, 
                'not_restricted' => $this->getStatusCollection['not_restricted'],
                'prev_status' => $this->getStatusCollection['previous_status'], 
                'is_principal' => false, 
                'with_previous' => $this->getStatusCollection['id'] ]);
        }

        $this->reset(['receivedQuantities', 'selectAll']);
        $this->productionBatch->refresh();

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Recibido'), 
        ]);
    
    }


    public function sendToStock()
    {
        $this->buttonDisabled = true;

        $dataToSave = [];

        foreach ($this->productionBatch->items as $item) {
            $maxAllowed = $item->active;
            $quantityToAdd = $this->sendQuantities[$item->id] ?? 0;

            // Validar que la cantidad no exceda el máximo permitido
            if ($quantityToAdd > 0 && $quantityToAdd <= $maxAllowed) {


                if($this->getStatusCollection['initial_process']){

                    $dataToSave[] = [
                        'parent_id' => $item->product->parent_id,
                        'color_id' => $item->product->color_id,
                        'size_id' => $item->product->size_id,
                        'quantity' => $quantityToAdd,                        
                        'product_id' => $item->product_id,
                    ];
                }

                $item->active -= $quantityToAdd;

                $item->save();
            }
        }

        if($this->getStatusCollection['initial_process']){
            
            if(!isset($dataToSave[0])){
                $this->emit('swal:alert', [
                    'icon' => 'error',
                    'title'   => __('Verifique cantidades'), 
                ]);
                return false;
            }

            $batch = $this->order->createProductionBatch([
                'status_id' => 15,
                'sendToStock' => true,
                'parent_id' => $dataToSave[0]['parent_id'], 
                'production_batch_items' => $dataToSave, 
                'is_principal' => false,
                'not_restricted' => $this->getStatusCollection['not_restricted'],
                'prev_status' => $this->getStatusCollection['previous_status'], 
                'initial_process' => $this->getStatusCollection['initial_process'],
                'with_previous' => $this->getStatusCollection['id'] 
            ]);

            foreach ($batch->items as $item) {
                $item->output_quantity = $item->input_quantity;
                $item->active = 0;
                $item->save();
            }

        }

        $this->reset(['sendQuantities', 'selectAll']);
        $this->productionBatch->refresh();

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Enviado a stock'), 
        ]);
    
    }


    public function getRemainingQuantity($item)
    {
        return $item->input_quantity - $item->output_quantity;
    }

    public function makeConsumptionEmited($stationId)
    {
        $station = ProductionBatch::with('items')->find($stationId);

        $order = $this->order;

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

            foreach($station->items as $product_statione){

                $productOrder = ProductOrder::where('product_id', $product_statione->product_id)->where('order_id', $station->order_id)->first();

                $quantity = $product_statione->input_quantity;

                if($productOrder->gettAllConsumptionSecond($quantity) != 'empty'){
                    foreach($productOrder->gettAllConsumptionSecond($quantity) as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $order->id,
                            'product_order_id' => $productOrder->id, 
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
            if($material['quantity'] == 0){
                continue;
            }

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
                    <b>
                       <a class='text-primary' href='/admin/material?search={$error['part_number']}&editStock=true' target='_blank'> {$error['material_name']}</a></b> (Código: {$error['part_number']}) <br> Cantidad Requerida: {$error['required_quantity']} {$error['unit_measurement']}, <br> Existencia: {$error['available_stock']} {$error['unit_measurement']}");
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


        foreach($station->items as $product_statione){

            $productOrder = ProductOrder::where('product_id', $product_statione->product_id)->where('order_id', $station->order_id)->first();

            $quantity = $product_statione->input_quantity;

            if($productOrder->gettAllConsumptionSecond($quantity) != 'empty'){

                foreach($productOrder->gettAllConsumptionSecond($quantity) as $key => $consumption){

                    $order->materials_order()->create([
                        'production_batch_id' => $station->id,
                        'product_order_id' => $productOrder->id,
                        'material_id' => $key,
                        'price' => $consumption['price'],
                        'unit_quantity' => $consumption['unit'],
                        'quantity' => $consumption['quantity'],
                    ]);
                }
            }
        }

        $this->productionBatch->refresh();

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


    public function makeInvoiceDate($station_id)
    {
        return $this->emit('swal:inputdate', [
            'title' => 'Fecha de Factura',
            'html' => '<input type="date" id="invoice-date" class="swal2-input" placeholder="calendar 1">',
            'getId' => $station_id,
            'showCancelButton' => true,
            'method' => 'saveInvoiceDate',
        ]);

    }

    public function saveInvoiceDate($station_id, ?string $dateInput = null)
    {
        if($dateInput){
            $productionBatch = ProductionBatch::findOrFail($station_id);
            $productionBatch->invoice_date = $dateInput;
            $productionBatch->save();

           $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Saved'), 
            ]); 

           $this->emit('renderview');
        }
    }

    private function getErrorNotRestricted()
    {
        if($this->getStatusCollection['not_restricted'] && !$this->productionBatch->service_type_id){
            $this->emit('swal:alert', [
               'icon' => 'error',
                'title'   => __('Seleccione tipo de servicio'), 
            ]);

            return false;
        }
        return true;
    }

    public function makeReceiveAll($stationId)
    {
        if (!$this->getErrorNotRestricted()) {
            return false; // Detiene la ejecución si está vacío/cero
        }

        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => 'Se recibirán todos los productos',
            'html' => 'Todos los productos de este folio',
            'confirmText' => '¿Desea confirmar?',
            'method' => 'receiveAll',
            'params' => $stationId,
        ]);
    }

    public function render()
    {
        $getTableData = getTableData($this->productionBatch->items()->get());

        return view('backend.order.livewire.production-batch',[
            'getTableData' => $getTableData,
        ]);
    }
}
