<x-utils.modal id="createCash" tform="store">
  <x-slot name="title">
      @lang('Add initial daily cash closing') <i class="cil-money"></i>
  </x-slot>

  <x-slot name="content">
      <label>@lang('Quantity')</label>
      <input wire:model="initial" type="text" class="form-control"/>
      @error('initial') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
        <button type="submit" class="btn btn-primary">@lang('Save') <i class="cil-money"></i></button>
  </x-slot>
</x-utils.modal>

