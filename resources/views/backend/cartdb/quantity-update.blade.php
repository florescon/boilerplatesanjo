<div style="min-width: 60px;">
    <input wire:model="quantity" class="form-control text-center" tabindex="-1"  wire:change="update" min="1" type="number">
    @error('quantity') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>
