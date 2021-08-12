<div class="input-group mb-3" style="   position: relative;
    width: 150px;
    height: 48px;
    font-weight: 400;
    font-size: 18px;
    line-height: 48px;
    border: none;
    /*display: block;*/
">
  <div class="input-group-prepend mr-2">
    <button wire:click="decrement" class="border-0" type="button">-</button>
  </div>
  <input  wire:model="quantity" style="text-align:center;" class="form-control" placeholder="" min="1" wire:change="updateCartList" aria-label="" aria-describedby="basic-addon1">
  <div class="input-group-append ml-2">
    <button wire:click="increment" class="border-0" type="button">+</button>
  </div>
</div>

