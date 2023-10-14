<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SummaryFeedstock;
use App\Domains\Auth\Models\User;
use App\Models\CartFeedstock;
use App\Models\OrderStatusDelivery;
use App\Models\Out;
use Carbon\Carbon;

class OutFeedstockSummary extends Component
{
    public ?string $type = '';

    public ?int $branchIdSummary = 0;

    public bool $isMain = false;

    public $description = '';

    public $summary;

    public $customer, $phone, $address, $rfc;

    protected $listeners = ['selectedCompanyItem', 'cartUpdated' => '$refresh'];

    public function mount()
    {
        $this->branchId = 0;
        $this->summary = SummaryFeedstock::where('user_id', Auth::id())->first() ?? null;
        $this->description = $this->summary->description ?? '';
        $this->customer = $this->summary->customer_id ?? null;
        $this->type_price = $this->summary->customer->customer->type_price ?? 'retail';
        $this->phone = $this->summary->customer->customer->phone ?? null;
        $this->address = $this->summary->customer->customer->address ?? null;
        $this->rfc = $this->summary->customer->customer->rfc ?? null;
    }

    public function selectedCompanyItem($customer)
    {
        if ($customer) {
            $this->customer = $customer;

            $customerDB = User::where('id', $customer)->first();

            $summary = SummaryFeedstock::updateOrCreate(
                ['user_id' => Auth::id()],
                ['customer_id' => $this->customer]
            );

        }
        else{
            $this->customer = null;
        }

        $this->redirectLink();

    }

    public function redirectLink()
    {
        return redirect()->route('admin.material.out');
    }

    private function getProducts()
    {
        return $products = CartFeedstock::with('material.color', 'material.unit', 'material.size')->where('user_id', Auth::id())->get();
    }

    private function clearSummary()
    {
        $this->summary->delete();
    }

    public function clearUser()
    {
        $deleteCustomer = DB::table('summary_feedstocks')->where('id', $this->summary->id)->update(['customer_id' => null]);

        $this->redirectLink();
    }

    public function updatedDescription()
    {
        $summary = SummaryFeedstock::updateOrCreate(
            ['user_id' => Auth::id()],
            ['description' => $this->description]
        );
    }

    public function checkout()
    {
        $getProducts = $this->getProducts();

        $getProducts->when($getProducts->count(), function ($getProducts) {

            $out = new Out();
            $out->customer_id = $this->customer ?? null;
            $out->description = $this->description;
            $out->user_id = Auth::id();
            $out->save();

            foreach($this->getProducts() as $product){

                $out->feedstocks()->create([
                    'material_id' => $product->product_id,
                    'quantity' => $product->quantity,
                    'price' =>  $product->price,
                    'comment' => $product->comment,
                    'type' => $product->type,
                ]);
            }

            $this->clearSummary();
            $this->emit('clearAllProducts');

        });
    }    

    public function render()
    {
        $countProducts = DB::table('cart_feedstocks')->where('user_id', Auth::id())->count();

        return view('backend.material.livewire.out-feedstock-summary', [
            'countProducts' => $countProducts,
        ]);
    }
}
