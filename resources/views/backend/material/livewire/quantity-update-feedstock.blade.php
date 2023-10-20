<div style="min-width: 60px;">
    <input wire:model.lazy="quantity" class="form-control text-center opacity-placeholder" style="color:red;" wire:change="update" step="any" type="number">
    @error('quantity') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>
