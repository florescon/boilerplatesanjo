<div
x-data="{ color: '#000' }"
    x-init="
        picker = new Picker($refs.button);
        picker.onDone = rawColor => {
            color = rawColor.hex;
            $dispatch('input', color)
        }
    "
    wire:ignore
    {{ $attributes }}
>

<button x-ref="button" class="btn btn-primary btn-sm">
  Seleccione <span  x-text="color"  class="badge badge-light"></span>
</button>


    
</div>