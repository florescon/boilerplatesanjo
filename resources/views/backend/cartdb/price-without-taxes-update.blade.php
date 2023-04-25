<div style="min-width: 100px;">
    <input wire:model.lazy="price_without_tax" class="form-control text-center" tabindex="-1"  wire:change="update" step="any" type="number">
    @error('price_without_tax') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>
