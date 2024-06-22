<x-utils.modal id="createThread" tform="store">
  <x-slot name="title">
    @lang('Create thread')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control" placeholder="{{ __('Name') }}" />
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Code')</label>
      <input wire:model.lazy="code" type="text" class="form-control" maxlength="4" placeholder="{{ __('Code') }}"/>
      @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

