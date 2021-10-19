<x-utils.modal id="searchProduct">
  <x-slot name="title">
    @lang('Search product')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      <button class="btn btn-primary mt-3" data-toggle="modal" 
        {{-- wire:click="detailsproduct()"   --}}
        {{-- wire:click="$emit('backend.store.pos.details-product', 'detailsproduct', @json(['name' => 'Flores'])))" --}}
        data-target="#detailsProduct">@lang('Child modal')</button>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

