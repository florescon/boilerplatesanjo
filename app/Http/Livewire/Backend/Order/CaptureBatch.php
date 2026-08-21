<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\ProcessRoute;
use App\Models\Batch;
use Carbon\Carbon;
use DB;
use Symfony\Component\HttpFoundation\Response;

class CaptureBatch extends Component
{
    public $order_id;
    public $order;

    public bool $floatButton = true;

    protected $listeners = ['quantitiesUpdated' => 'handleQuantitiesUpdate', 'save', 'saveAll', 'initializeQuantities',
    ];

    public function mount(Order $order)
    {
        $this->order_id = $order->id;
    }

    public function saveAll(int $getID)
    {
        $products = $this->order->products()
            ->whereHas('product', function ($query) use ($getID) {
                $query->where('parent_id', $getID);
            })
            ->with('product.parent.size')
            ->get();

        $availableProducts = 0;

        foreach($products as $p)
        {
            $availableProducts += $p->available;
        }

        if ($availableProducts == 0) {
            // Si ya existe, emitir alerta y salir del método
            $this->emit('swal:alert', [
                'icon' => 'warning', // o 'error' si prefieres indicar que ya existe
                'title' => __('No se puede crear porque no hay cantidades disponibles'), // Mensaje personalizado (puedes cambiarlo)
            ]);
            return; // Detener la ejecución
        }

        $processRoutes = ProcessRoute::where('process_id', 2)
            ->with('operation')
            ->orderBy('sequence')
            ->get();

        $batch = Batch::create([
            'order_id' => $this->order_id,
            'process_id' => 2,
            'status_name' => 'pending',
        ]);

        foreach ($products as $product) {

            // Si este producto no tiene cantidad disponible, no lo agregamos
            if ($product->available <= 0) {
                continue;
            }

            $batchItem = $batch->batch_product()->create([
                'product_order_id' => $product->id,
                'product_id'       => $product->product_id,
                'quantity'         => $product->available,
            ]);

            /*
             * Crear una operación por cada process_route
             */
            foreach ($processRoutes as $route) {

                $batchItem->batch_operations()->create([
                    'order_id'       => $this->order_id,
                    'batch_id'       => $batch->id,
                    'batch_item_id'  => $batchItem->id,
                    'operation_id'   => $route->operation_id,
                    'operation_name' => $route->operation->name,
                    'expected'       => $product->available,
                    'status_name'    => 'pending',
                    'sequence'       => $route->sequence,
                    'product_id'     => $getID,
                ]);
            }

            $product->available = 0;
            $product->save();
        }

        return redirect()->route('admin.batch.edit', $batch->id);

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
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
        return view('backend.order.livewire.capture-batch');
    }
}
