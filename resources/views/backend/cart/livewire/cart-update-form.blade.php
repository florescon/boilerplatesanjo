<div>
    <input wire:model="quantity" class="form-control" type="number" min="1" wire:change="updateCart">
    @error('quantity') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>
