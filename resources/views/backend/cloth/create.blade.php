<x-utils.modal id="createCloth" tform="store">
  <x-slot name="title">
    @lang('Create cloth')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

