<x-utils.modal id="createUnit" tform="store">
  <x-slot name="title">
    @lang('Create unit')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Abbreviation')</label>
      <input wire:model.lazy="abbreviation" type="text" class="form-control"/>
      @error('abbreviation') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

