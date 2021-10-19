<x-utils.modal id="detailsProduct">
  <x-slot name="title">
    @lang('Details product')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name') {{ $name }}</label>
      <input wire:model="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>

