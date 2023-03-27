<x-utils.modal id="editFamily" width="modal-dialog-centered" tform="update">
  <x-slot name="title">
    @lang('Edit family')
  </x-slot>

  <x-slot name="content">
      <input type="hidden" wire:model="selected_id">
      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Update changes')</button>

  </x-slot>
</x-utils.modal>