<x-utils.modal id="editSize" width="modal-dialog-centered" tform="update">
  <x-slot name="title">
    @lang('Edit size')
  </x-slot>

  <x-slot name="content">

      <input type="hidden" wire:model="selected_id">

      <label>@lang('Name')</label>
      <input wire:model="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label>@lang('Short name')</label>
      <input wire:model="short_name" type="text" class="form-control" maxlength="4"/>
      @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Update changes')</button>

  </x-slot>
</x-utils.modal>

