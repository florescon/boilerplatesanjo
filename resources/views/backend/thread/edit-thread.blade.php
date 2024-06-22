<x-utils.modal id="editThread" width="modal-dialog-centered" tform="update">
  <x-slot name="title">
    @lang('Edit thread')
  </x-slot>

  <x-slot name="content">
      <input type="hidden" wire:model="selected_id">

      <label class="mt-2">@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Code')</label>
      <input wire:model.lazy="code" type="text" class="form-control"/>
      @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Update changes')</button>

  </x-slot>
</x-utils.modal>