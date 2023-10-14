<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use App\Models\CartFeedstock;
use Illuminate\Support\Str;

class EditInlineFeedstock extends Component
{
    public $cartId;
    public $shortId;
    public $origName; // initial cart name state
    public $newName; // dirty cart name state
    public $isName; // determines whether to display it in bold text

    public function mount(CartFeedstock $cart)
    {
        $this->cartId = $cart->id;
        $this->shortId = $cart->short_id;
        $this->origName = $cart->comment;

        $this->init($cart); // initialize the component state
    }

    public function save()
    {
        $cart = CartFeedstock::findOrFail($this->cartId);
        $newName = (string)Str::of($this->newName)->trim()->substr(0, 100); // trim whitespace & more than 100 characters
        $newName = $newName === $this->shortId ? null : $newName; // don't save it as cart name it if it's identical to the short_id

        $cart->comment = $newName ?? null;
        $cart->save();

        $this->init($cart); // re-initialize the component state with fresh data after saving
    }

    private function init(CartFeedstock $cart)
    {
        $this->origName = $cart->comment ?: $this->shortId;
        $this->newName = $this->origName;
        $this->isName = $cart->comment ?? false;
    }

    public function render()
    {
        return view('backend.material.livewire.edit-inline-feedstock');
    }
}
