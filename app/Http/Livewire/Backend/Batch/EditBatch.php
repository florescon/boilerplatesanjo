<?php

namespace App\Http\Livewire\Backend\Batch;

use Livewire\Component;
use App\Models\Order;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EditBatch extends Component
{
    public $batchId;

    public bool $floatButton = true;

    public bool $processed = true;
    public bool $received = false;
    public bool $delivered = false;

    public $selectedOperation = 'NoLink';

    public $selectedAfterOperation = null;
    
    public $quantities = [];

    protected $listeners = ['editSelectBatch', 'save'];

    public $selectedStatus = [];

    public $activeOperation;

    public $chartData;
    public $chartShapes;
    
    public $operations = [];

    public function mount()
    {
        $this->chartData = $this->getChartData();
        $this->getChartShapes();

        $batchOne = Batch::findOrFail($this->batchId);

        $this->operations = collect(
            $batchOne->getUniqueOperation()
        )->values()->all();

        $this->activeOperation = $this->operations[0]->operation_id ?? null;
    }


    public function selectOperation($operationId)
    {
        $this->activeOperation = (int) $operationId;
    }
    public function selectStatus($selectedStatus, $operationId)
    {
        $this->selectedStatus[$operationId] = $selectedStatus;
    }

    public function editSelectBatch(Batch $batch)
    {
        $this->batchId = $batch->id;
    }

    public function updatedQuantities($value, $keyPath)
    {
        [$operationId, $batchItemId] = explode('.', $keyPath);

        $batchItem = DB::table('batch_items')
            ->where('id', $batchItemId)
            ->first();

        // Debe ser entero
        if (!filter_var($value, FILTER_VALIDATE_INT) && $value != 0) {
            $this->addError(
                "quantities.$operationId.$batchItemId",
                'Debe capturar un número entero.'
            );
            // $this->quantities[$operationId][$batchItemId] = 0;

            return;
        }

        $value = (int) $value;

        // No puede ser negativo
        if ($value < 0) {
            $this->addError(
                "quantities.$operationId.$batchItemId",
                'No puede ser negativo.'
            );
            // $this->quantities[$operationId][$batchItemId] = 0;

            return;
        }

        // No puede exceder la cantidad disponible
        if ($value > $batchItem->quantity) {
            $this->addError(
                "quantities.$operationId.$batchItemId",
                "No puede ser mayor a {$batchItem->quantity}."
            );

            // $this->quantities[$operationId][$batchItemId] = 0;
            return;
        }

        // Si pasó todas las validaciones, limpia cualquier error previo
        $this->resetErrorBag('quantities.'.$keyPath);
        $this->resetValidation("quantities.$operationId.$batchItemId");
    }

    public function resetInput()
    {
        $this->quantities = [];
    }

    public function save($operationId)
    {
        $batchOne = Batch::findOrFail($this->batchId);

        $operations = collect($batchOne->getUniqueOperation());

        $currentOperation = $operations
            ->firstWhere('operation_id', $operationId);

        $previousOperation = $operations
            ->filter(fn ($operation) =>
                $operation->sequence < $currentOperation->sequence
            )
            ->sortByDesc('sequence')
            ->first();


        // Estado seleccionado para ESTA operación
        $selectedStatus = $this->selectedStatus[$operationId] ?? 'processed';

        $previousField = [
            'processed' => 'expected',
            'received'  => 'processed',
            'delivered' => 'received',
        ];

        $quantities = $this->quantities[$operationId] ?? [];

        if (empty(array_filter($quantities))) {
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title' => __('Datos vacíos'),
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 1. VALIDAR TODO
        |--------------------------------------------------------------------------
        | Aquí NO hacemos ningún update.
        |--------------------------------------------------------------------------
        */

        foreach ($quantities as $batchItemId => $qty) {

            // Ignorar valores vacíos o menores/iguales a 0
            if (!$qty || (int) $qty <= 0) {
                continue;
            }

            $previousDelivered = 0;

            if ($selectedStatus === 'processed' && $previousOperation) {

                $previousBatchOperation = DB::table('batch_operations')
                    ->where('batch_item_id', $batchItemId)
                    ->where('operation_id', $previousOperation->operation_id)
                    ->first();

                $previousDelivered = (int) (
                    $previousBatchOperation->delivered ?? 0
                );
            }

            $batchOperation = DB::table('batch_operations')
                ->where('batch_item_id', $batchItemId)
                ->where('operation_id', $operationId)
                ->first();

            if (!$batchOperation) {
                continue;
            }

            $currentValue = (int) (
                $batchOperation->{$selectedStatus} ?? 0
            );

            $newValue = $currentValue + (int) $qty;

            /*
            |--------------------------------------------------------------------------
            | VALIDACIÓN ESPECIAL PARA PROCESSED
            |--------------------------------------------------------------------------
            */

            if (
                $selectedStatus === 'processed' &&
                $previousOperation
            ) {
                if ($newValue > $previousDelivered) {

                    $this->emit('swal:alert', [
                        'icon' => 'warning',
                        'title' => __('No se puede crear, verifique cantidades con respecto al anterior Proceso'),
                    ]);

                    return;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDACIÓN NORMAL
            |--------------------------------------------------------------------------
            */

            if (isset($previousField[$selectedStatus])) {

                $previousStatus = $previousField[$selectedStatus];

                $maxValue = (int) (
                    $batchOperation->{$previousStatus} ?? 0
                );

                if ($newValue > $maxValue) {

                    $this->emit('swal:alert', [
                        'icon' => 'warning',
                        'title' => __('La cantidad excede la cantidad permitida'),
                    ]);

                    return;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. ACTUALIZAR
        |--------------------------------------------------------------------------
        | Si llegamos aquí significa que TODAS las cantidades son válidas.
        |--------------------------------------------------------------------------
        */

        foreach ($quantities as $batchItemId => $qty) {

            if (!$qty || (int) $qty <= 0) {
                continue;
            }

            $batchOperation = DB::table('batch_operations')
                ->where('batch_item_id', $batchItemId)
                ->where('operation_id', $operationId)
                ->first();

            if (!$batchOperation) {
                continue;
            }

            $currentValue = (int) (
                $batchOperation->{$selectedStatus} ?? 0
            );

            $newValue = $currentValue + (int) $qty;

            DB::table('batch_operations')
                ->where('batch_item_id', $batchItemId)
                ->where('operation_id', $operationId)
                ->update([
                    $selectedStatus => $newValue,
                    'updated_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | TRANSFERIR ACTIVE
            |--------------------------------------------------------------------------
            */

            if ($selectedStatus === 'processed') {

                if ($previousOperation) {

                    $previousBatchOperation = DB::table('batch_operations')
                        ->where('batch_item_id', $batchItemId)
                        ->where('operation_id', $previousOperation->operation_id)
                        ->first();

                    $previousActive = (int) (
                        $previousBatchOperation->active ?? 0
                    );

                    DB::table('batch_operations')
                        ->where('batch_item_id', $batchItemId)
                        ->where('operation_id', $previousOperation->operation_id)
                        ->update([
                            'active' => $previousActive - (int) $qty,
                            'updated_at' => now(),
                        ]);
                }

                // Sumar qty al active de la operación actual
                DB::table('batch_operations')
                    ->where('batch_item_id', $batchItemId)
                    ->where('operation_id', $operationId)
                    ->increment('active', (int) $qty);
            }

            /*
            |--------------------------------------------------------------------------
            | COMPLETED
            |--------------------------------------------------------------------------
            */

            if (
                $selectedStatus === 'delivered' &&
                $newValue === (int) $batchOperation->expected
            ) {

                DB::table('batch_operations')
                    ->where('batch_item_id', $batchItemId)
                    ->where('operation_id', $operationId)
                    ->update([
                        'status_name' => 'completed',
                        'updated_at' => now(),
                    ]);
            }
        }

        if (
            $currentOperation &&
            $currentOperation->sequence == $operations->last()->sequence
        ) {
            // Es la última operación
            $batchOne->markAsCompletedIfReady();
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Datos Guardados'),
        ]);

        $this->resetInput();

        $this->emit('batchChart', $this->getChartData());
        $this->emit('unitShapes', $this->getChartShapes());
        $this->getChartShapes();
    }

    public function messageAlert($getMethod, $getID)
    {
        abort_if(!in_array($getMethod, ['save', 'saveAll']), Response::HTTP_NOT_FOUND);

        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => '¿Crear? ',
            'html' => 'Capturado: ' . ' productos <br>',
            'confirmText' => '¿Desea confirmar?',
            'method' => (string) $getMethod,
            'params' => $getID,
        ]);
    }


    public function getChartShapes()
    {
        $batchOne = Batch::findOrFail($this->batchId);
        $operationsDB = $batchOne->totalProcess();

        $chartShapes = [
            'labels' => collect($operationsDB)
                ->pluck('operation_name')
                ->values()
                ->toArray(),

            'series' => collect($operationsDB)
                ->pluck('sumActive')
                ->map(fn ($value) => (int) $value)
                ->values()
                ->toArray(),
        ];

        $this->chartShapes = $chartShapes;

        return $this->chartShapes;

    }

    public function getChartData()
    {
        $rows = DB::table('batch_operations as bo')
            ->selectRaw("
                bo.batch_id,
                bo.operation_name,
                SUM(bo.expected) expected,
                SUM(bo.processed) processed,
                SUM(bo.received) received,
                SUM(bo.delivered) delivered,
                ROUND(SUM(bo.processed) * 100.0 / SUM(bo.expected),2) avance
            ")
            ->where('bo.batch_id', $this->batchId)
            ->groupBy(
                'bo.batch_id',
                'bo.operation_name',
                'bo.sequence'
            )
            ->orderBy('bo.sequence')
            ->get();

        // dd($rows);

        return [
            'categories' => $rows->pluck('operation_name'),
            'expected'   => $rows->pluck('expected'),
            'processed'  => $rows->pluck('processed'),
            'received'  => $rows->pluck('received'),
            'delivered'  => $rows->pluck('delivered'),
            'avance'     => $rows->pluck('avance'),
        ];
    }

    public function saveOperation()
    {
        $batch = Batch::findOrFail($this->batchId);

        if ($batch->status_name === 'completed') {
            $this->emit('swal:alert', [
                'icon' => 'warning', // o 'error' si prefieres indicar que ya existe
                'title' => __('Lote completado, no es posible agregar.'), // Mensaje personalizado (puedes cambiarlo)
            ]);

            return;
        }

        if ($this->selectedOperation === 'NoLink') {
            $this->emit('swal:alert', [
                'icon' => 'warning', // o 'error' si prefieres indicar que ya existe
                'title' => __('Select an operation'), // Mensaje personalizado (puedes cambiarlo)
            ]);

            return;
        }


        if ($this->selectedAfterOperation === null || $this->selectedAfterOperation === '') {
            $this->emit('swal:alert', [
                'icon' => 'warning', // o 'error' si prefieres indicar que ya existe
                'title' => __('Select the operation after which you wish to add it.'), // Mensaje personalizado (puedes cambiarlo)
            ]);

            return;
        }

        $operation = \App\Models\Operation::findOrFail(
            $this->selectedOperation
        );

        // ==========================================
        // VALIDAR SI YA EXISTE EN ESTE BATCH
        // ==========================================

        $exists = $batch->batch_operations()
            ->where('operation_id', $operation->id)
            ->exists();

        if ($exists) {
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title' => __('Operation already exists'),
            ]);

            return;
        }

        // Sequence de la operación seleccionada
        $sequence = (int) $this->selectedAfterOperation;

        // Agregar 1
        $newSequence = $sequence + 1;

        $batchProducts = $batch->batch_product()->get();

        foreach ($batchProducts as $b) {

            $product = Product::findOrFail($b->product_id);

            $batch->batch_operations()->create([
                'order_id'       => $batch->order_id,
                'batch_item_id'  => $b->id,
                'operation_id'   => $operation->id,
                'operation_name' => $operation->name,
                'expected'       => $b->quantity,
                'status_name'    => 'pending',
                'sequence'       => $newSequence,
                'product_id'     => $product->parent_id,
            ]);
        }

        $this->selectedOperation = 'NoLink';
        $this->selectedAfterOperation = null;
    }

    public function render()
    {
        $batchOne = Batch::findOrFail($this->batchId);

        $order = Order::findOrFail($batchOne->order_id);

        $sizes = $batchOne->getProductSizesFromBatch();
        $colors = $batchOne->getProductColorsFromBatch();

        $batchItems = DB::table('batch_items')
            ->join('products', 'batch_items.product_id', '=', 'products.id')
            ->leftJoin('products as parents', 'products.parent_id', '=', 'parents.id')
            ->where('batch_items.batch_id', $this->batchId)
            ->select(
                'batch_items.id',
                'batch_items.product_id',
                'products.parent_id',
                'products.size_id',
                'products.color_id',
                'batch_items.quantity as qty',
                'parents.name as parent_name',
                'parents.code as parent_code',
            )
            ->get();

            switch ($this->selectedStatus) {
                case 'expected':
                    $statusColumn = 'batch_operations.expected';
                    break;

                case 'received':
                    $statusColumn = 'batch_operations.received';
                    break;

                case 'delivered':
                    $statusColumn = 'batch_operations.delivered';
                    break;

                default:
                    $statusColumn = 'batch_operations.processed';
                    break;
            }

        $batchItems = DB::table('batch_items')
            ->join('products', 'batch_items.product_id', '=', 'products.id')
            ->join('batch_operations', 'batch_items.id', '=', 'batch_operations.batch_item_id')
            ->leftJoin('products as parents', 'products.parent_id', '=', 'parents.id')
            ->where('batch_items.batch_id', $this->batchId)
            ->select(
                'batch_operations.operation_id',
                'batch_items.id',
                'batch_items.product_id',
                'products.parent_id',
                'products.size_id',
                'products.color_id',
                'batch_items.quantity as qty',
                'parents.name as parent_name',
                'parents.code as parent_code',
                'batch_operations.expected as expected',
                'batch_operations.processed as processed',
                'batch_operations.received as received',
                'batch_operations.delivered as delivered',
                'batch_operations.id as process_id',
                // DB::raw("$statusColumn as status_qty")
            )
            ->get();



        $matrix = [];
        $parents = [];

        foreach ($batchItems as $item) {


            $parentId = $item->parent_id ?: 0;

            $parents[$parentId] = $item->parent_name ? 
                                    ($item->parent_code.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$item->parent_name)  
                                    :  'Sin producto padre';

            // $matrix[$parentId][$item->color_id][$item->size_id] = $item;
            $matrix[$item->operation_id][$parentId][$item->color_id][$item->size_id] = $item;
        }

$totals = [];

foreach ($matrix as $operationId => $parentsData) {

    foreach ($parentsData as $parentIdData => $colorsData) {

        foreach ($colorsData as $colorId => $sizesData) {

            foreach ($sizesData as $sizeId => $item) {

                $qty = $item->qty ?? 0;

                // Total por color
                $totals[$operationId][$parentIdData]['colors'][$colorId] =
                    ($totals[$operationId][$parentIdData]['colors'][$colorId] ?? 0) + $qty;

                // Total por talla
                $totals[$operationId][$parentIdData]['sizes'][$sizeId] =
                    ($totals[$operationId][$parentIdData]['sizes'][$sizeId] ?? 0) + $qty;

                // Total por producto padre
                $totals[$operationId][$parentIdData]['product'] =
                    ($totals[$operationId][$parentIdData]['product'] ?? 0) + $qty;

                // Opcional: total general de la operación
                $totals[$operationId]['operation'] =
                    ($totals[$operationId]['operation'] ?? 0) + $qty;
            }
        }
    }
}

    $sizesByParent = [];

    foreach ($parents as $parentId => $name) {
        $sizesByParent[$parentId] = DB::table('products')
            ->join('sizes', 'sizes.id', '=', 'products.size_id')
            ->where('products.parent_id', $parentId)
            ->select(
                'sizes.id',
                'sizes.name',
                'sizes.sort'
            )
            ->distinct()
            ->orderBy('sizes.sort')
            ->get();
    }

        return view('backend.batch.livewire.edit-batch')->with([
            'batchOne' => $batchOne,
            'chartData' => $this->getChartData(),
            'order' => $order,
            'sizes' => $sizes,
            'sizesByParent' => $sizesByParent,
            'chartShapes' => $this->getChartShapes(),
            'colors' => $colors,
            'batchItems' => $batchItems,
            'matrix' => $matrix,
            'parents' => $parents,
            'totals' => $totals,
        ]);
    }
}
