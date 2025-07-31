<?php

namespace App\Http\Livewire\Backend\Order;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use DB;

class AdvancedOrder extends Component
{
    public $order_id, $lates_statusId, $slug, $isComment, $comment, $isDate, $date_entered;

    public $from_store = null;
    public $order;

    public bool $showPriceWithoutTax = false;

    protected $listeners = ['reasignUserStore' => 'render', 'cartUpdated' => '$refresh', 'reasignDepartamentStore' => 'render'];


    public function mount(Order $order)
    {
        $this->order_id = $order->id;
    }

    public function searchproduct()
    {
        // Lógica para buscar productos aquí
        // Por ejemplo:
        // $this->products = Product::search($this->searchTerm)->get();
    }


    public function renderButton()
    {
        $this->emit('serviceStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Data saved in this table'), 
        ]);
    }


    public function removeP($productId): void
    {
        // Obtener el conteo actual de productos sin cotización
        $productsCount = $this->order->products_without_quotation->count();
        

        // Verificar si hay 1 o menos productos
        if ($productsCount <= 1) {
            $this->emit('swal:alert', [
                'icon' => 'error',
                'title' => __('No se puede eliminar el último producto'),
            ]);
            return;
        }
        
        // Si hay más de 1 producto, proceder con la eliminación
        $delete = DB::table('product_order')->where('id', $productId)->delete();
        
        // Emitir evento de éxito
        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Deleted'),
            'text' => __('Producto eliminado correctamente'),
        ]);

        $this->emit('cartUpdated');
        
        // Opcional: actualizar la lista de productos después de eliminar
    }

    public function removeProduct($productId): void
    {
        $delete = DB::table('product_order')->where('id', $productId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }


    public function processQuotation()
    {
        $order = Order::whereId($this->order_id)->first();

        $order->touch();

        $last_order_or_request = $order->last_order_or_request;

        $orderUpdate = $order->update(['type' => !$this->from_store ? 1 : 5, 'folio' => $last_order_or_request+1, 'date_entered' => today(), 'created_at' => now()]);
        $order->product_quotation()->update(['type' => !$this->from_store ? 1 : 5]);   

        if($this->from_store){
            $this->requestReadyForDelivery($order);
        }

        return $this->redirectRoute('admin.order.advanced', $this->order_id);
    }

    public function render()
    {
        $order = Order::with('suborders')->findOrFail($this->order_id);

        $quotationExists = $order->product_quotation()->exists();

        $limit = $order->created_at->addDays(7);
        $now = Carbon::now();
        $result = $now->gt($limit);

        return view('backend.order.livewire.advanced-order')->with(compact('order', 'quotationExists', 'result'));
    }
}
