<div style="min-width: 100px;">
    <input wire:model.lazy="price" class="form-control text-center" tabindex="-1"  wire:change="update" step="any" type="number">
    @error('price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>
