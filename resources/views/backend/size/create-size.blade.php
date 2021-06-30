<x-utils.modal id="createSize" tform="store">
  <x-slot name="title">
    @lang('Create size')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label>@lang('Short name')</label>
      <input wire:model="short_name" type="text" class="form-control" maxlength="4"/>
      @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

