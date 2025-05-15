<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\ProductionBatch;
use App\Models\Status;
use App\Models\Order;
use App\Models\ProductOrder;
use App\Models\Material;
use App\Domains\Auth\Models\User;

class ProdBatch extends Component
{
    public ProductionBatch $productionBatch;
    public array $receivedQuantities = [];
    public bool $selectAll = false;
    public $getStatusCollection;
    public $order;
    public $status;
    public ?int $user = null;
    public $users;
    public $date_entered;

    protected $listeners = [
        'makeConsumptionEmited', 
    ];

    public function mount()
    {
        $idStatus = $this->productionBatch->status_id;
        $this->status = Status::find($idStatus);
        $this->users = User::admins()->select(['id', 'name'])->orderBy('name')->get();

        $this->order = Order::find($this->productionBatch->order_id);
        $this->getStatusCollection = $this->status->getStatusCollection();

        // Inicializar el array con las cantidades recibidas para cada item
        foreach ($this->productionBatch->items as $item) {
            $this->receivedQuantities[$item->id] = 0;
        }

        $this->date_entered = optional($this->productionBatch->date_entered)->format('Y-m-d');

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

            abort(403, __('Consumption required') . ' :(');
        }
    }

    public function receiveAll()
    {
        $this->requiredConsumption();

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
            $batch = $this->order->createProductionBatch([
                'status_id' => 11, 
                'parent_id' => $dataToSave[0]['parent_id'], 
                'production_batch_items' => $dataToSave, 
                'prev_status' => $this->getStatusCollection['previous_status'], 
                'is_principal' => $this->getStatusCollection['is_principal'], 
                'with_previous' => ($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']) ]);
        }

        if($this->getStatusCollection['final_process']){
            foreach ($this->productionBatch->items as $item) {
                $item->active = 0;
                $item->save();
            }
        }

        $this->reset(['receivedQuantities', 'selectAll']);
        $this->productionBatch->refresh();
    }

    public function receiveSelected()
    {
        $this->requiredConsumption();

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

                if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier'] || $this->getStatusCollection['final_process']){
                    $item->active -= $quantityToAdd;
                }
                $item->save();
            }
        }

        if($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']){
            
            $batch = $this->order->createProductionBatch([
                'status_id' => 11, 
                'parent_id' => $dataToSave[0]['parent_id'], 
                'production_batch_items' => $dataToSave, 
                'prev_status' => $this->getStatusCollection['previous_status'], 
                'is_principal' => $this->getStatusCollection['is_principal'], 
                'with_previous' => ($this->getStatusCollection['final_lot'] || $this->getStatusCollection['supplier']) ]);
        }

        $this->reset(['receivedQuantities', 'selectAll']);
        $this->productionBatch->refresh();
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


    public function render()
    {
        $getTableData = getTableData($this->productionBatch->items()->get());

        return view('backend.order.livewire.production-batch',[
            'getTableData' => $getTableData,
        ]);
    }
}
