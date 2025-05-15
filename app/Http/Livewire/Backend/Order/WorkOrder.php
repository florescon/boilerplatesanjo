<?php

namespace App\Http\Livewire\Backend\Order;
use App\Models\Order;
use App\Models\Status;
use App\Models\Station;
use App\Models\ProductStation;
use Symfony\Component\HttpFoundation\Response;
use DB;

use Livewire\Component;

class WorkOrder extends Component
{
    public Status $status;
    public Order $order;

    public $parent_id;
    public $color;
    public $size;
    public $groupTotals = []; // Guardará los totales por grupo

    public $quantities = [];

    public $colorIds = []; // Nuevo array para almacenar los color_id

    public $initialQuantities = []; // Almacena los valores iniciales
    public $saving = []; // Para controlar el estado de los botones

    public bool $floatButton = true;

    public $status_id;
    public $next_status, $previous_status;

    public $calculateStatusQuantities; // Variable para almacenar el cálculo

    protected $queryString = [
        'floatButton' => ['except' => FALSE],
    ];

    protected $listeners = ['quantitiesUpdated' => 'handleQuantitiesUpdate', 'save', 'saveAll', 'initializeQuantities',
        'updateGroupTotal' => 'setGroupTotal'
    ];

    public function mount($order, $status)
    {
        $this->initializeQuantities();
        
        foreach ($order->getSizeTablesData() as $parentId => $tableData) {
            foreach ($tableData['rows'] as $rowIndex => $row) {
                $this->colorIds[$parentId][$rowIndex] = $row['color_id'];
            }
        }

        $this->status_id = $status->id;
        $this->status_name = $status->name;

        $this->loadInitialData($status);

        $this->calculateStatusQuantities();

    }

    public function calculateStatusQuantities()
    {
        $this->calculateStatusQuantities = $this->order->calculateStatusQuantities();
    }
    
    public function emitStatusQuantities()
    {
        $this->calculateStatusQuantities();
        $this->emit('statusQuantitiesUpdated', $this->calculateStatusQuantities);
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

    public function setGroupTotal($data)
    {
        $this->groupTotals[$data['parentId']] = $data['total'];
        // dd($data);
    }

    protected function initializeQuantities()
    {
        $tablesData = $this->order->getSizeTablesData($this->status->getStatusCollection());
        
        foreach ($tablesData as $parentId => $tableData) {
            foreach ($tableData['rows'] as $rowIndex => $row) {
                foreach ($tableData['headers'] as $header) {
                    if (isset($row['sizes'][$header['id']])) {
                        $initialQty = $row['sizes'][$header['id']]['quantity'] ?? 0;
                        $activeQty = $row['sizes'][$header['id']]['active'] ?? 0;
                        
                        // Guardar en ambos arrays
                        // $this->quantities[$parentId][$rowIndex][$header['id']] = $initialQty;
                        $this->initialQuantities[$parentId][$rowIndex][$header['id']]['initial'] = $initialQty;
                        $this->initialQuantities[$parentId][$rowIndex][$header['id']]['active'] = $activeQty;
                    }
                }
            }
        }
    }

    public function updatedQuantities($value, $keyPath)
    {
        // Parsear la clave multidimensional (ej: "quantities.1.0.2")
        $keys = explode('.', $keyPath);


        if (count($keys) === 3) {
            $parentId = $keys[0];
            $rowIndex = $keys[1];
            $sizeId = $keys[2];
            
            // Validar que sea un entero
            if (!is_numeric($value) || (int)$value != $value) {
                $this->addError('quantities.'.$keyPath, 'Debe ser número');
                $this->quantities[$parentId][$rowIndex][$sizeId] = 0;
                return;
            }
            
            $value = (int)$value;
            
            // Validar que no sea negativo
            if ($value < 0) {
                // dd('no negativo');
                $this->addError('quantities.'.$keyPath, 'No negativo');
                $this->quantities[$parentId][$rowIndex][$sizeId] = 0;
                return;
            }
            
            // Validar que no exceda la cantidad inicial
            $initialQty = $this->initialQuantities[$parentId][$rowIndex][$sizeId]['initial'] ?? 0;
            $activeQty = $this->initialQuantities[$parentId][$rowIndex][$sizeId]['active'] ?? 0;
            
            // dd($activeQty);

            $result = $initialQty - $activeQty;

            if ($value > $activeQty) {
                // dd('mayor');
                $this->addError('quantities.'.$keyPath, 'No exceder ('.$activeQty.')');
                $this->quantities[$parentId][$rowIndex][$sizeId] = null;
                // $this->quantities[$parentId][$rowIndex][$sizeId] = $initialQty;
                return;
            }
            
            // Si pasa todas las validaciones, actualizar el valor
            $this->quantities[$parentId][$rowIndex][$sizeId] = $value;
            $this->resetErrorBag('quantities.'.$keyPath);
        }
        else{
            // dd('s');
        }
    }

    public function saveAll(int $getID)
    {
        $getStatusCollection = $this->status->getStatusCollection();

        if (!$getStatusCollection['is_batch'] || !$getStatusCollection['is_principal']) {
            $this->emit('swal:alert', [
                'icon'  => 'warning',
                'title' => __('No se puede crear aquí'),
            ]);
            return;
        }

        // 1. Verificar si ya existe un registro en production_batches con order_id y product_id = $getID
        $existingBatch = \DB::table('production_batches')
            ->where('order_id', $this->order->id)
            ->where('product_id', $getID)
            ->first();

        if ($existingBatch) {
            // Si ya existe, emitir alerta y salir del método
            $this->emit('swal:alert', [
                'icon' => 'warning', // o 'error' si prefieres indicar que ya existe
                'title' => __('No se puede crear porque hay datos asociados'), // Mensaje personalizado (puedes cambiarlo)
            ]);
            return; // Detener la ejecución
        }

        $products = $this->order->products()
            ->whereHas('product', function ($query) use ($getID) {
                $query->where('parent_id', $getID);
            })
            ->with('product.parent.size')
            ->get();

        $structuredArray = $products->map(function ($orderProduct) {
            return [
                'parent_id' => $orderProduct->product->parent_id,
                'color_id' => $orderProduct->product->color_id,
                'size_id' => $orderProduct->product->size_id,
                'quantity' => $orderProduct->quantity,
                'product_id' => $orderProduct->product_id
            ];
        })->toArray();

        $batch = $this->order->createProductionBatch(['status_id' => $this->status->id, 'parent_id' => $getID, 'production_batch_items' => $structuredArray, 'prev_status' => $getStatusCollection['previous_status'], 'is_principal' => $getStatusCollection['is_principal']]);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
    }

    public function save(int $getID)
    {
        // dd($this->colorIds);
        // dd($this->quantities[$getID]);
        // dd([
        //     'quantities' => $this->quantities,
        //     'color_ids' => $this->colorIds
        // ]);

        $getStatusCollection = $this->status->getStatusCollection();

        $dataToSave = [];

        if(!empty($this->quantities[$getID])){

            foreach ($this->quantities[$getID] as $rowIndex => $sizes) {

                foreach ($sizes as $sizeId => $quantity) {

                    if (!is_int($quantity) || $quantity <= 0) {
                        continue; // Saltar esta iteración si no cumple la condición
                    }

                    $getProduct = DB::table('products')->where('parent_id', $getID)->where('size_id', $sizeId)->where('color_id', $this->colorIds[$getID][$rowIndex])->first();

                    $dataToSave[] = [
                        'parent_id' => $getID,
                        'color_id' => $this->colorIds[$getID][$rowIndex] ?? null,
                        'size_id' => $sizeId,
                        'quantity' => $quantity,                        
                        'product_id' => $getProduct->id
                    ];
                }
            }
            

            $batch = $this->order->createProductionBatch(['status_id' => $this->status->id, 'parent_id' => $getID, 'production_batch_items' => $dataToSave, 'prev_status' => $getStatusCollection['previous_status'], 'is_principal' => $getStatusCollection['is_principal']]);

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);

            $this->resetInput();
            // $this->emitUpdatedQuantity();
            $this->initializeQuantities();
            $this->emitStatusQuantities();
        }
        else{
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title' => 'Agrega cantidades'
            ]);
        }
    }

    public function resetInput()
    {
        $this->quantities = [];
    }

    public function messageAlert($getMethod, $getID)
    {
        abort_if(!in_array($getMethod, ['saveByParent', 'save', 'saveAll', 'saveFromSupplier']), Response::HTTP_NOT_FOUND);

        // $this->emitUpdatedQuantity();


        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => '¿Crear?',
            'html' => 'Capturado: ' . ' productos <br>',
            'confirmText' => '¿Desea confirmar?',
            'method' => (string) $getMethod,
            'params' => $getID,
        ]);
    }

    public function render()
    {
        $getStatusCollection = $this->status->getStatusCollection();

        return view('backend.order.livewire.works')->with([
            'getStatusCollection' => $getStatusCollection,
        ]);
    }
}
