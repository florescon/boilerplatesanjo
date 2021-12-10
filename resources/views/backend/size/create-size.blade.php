<x-utils.modal id="createSize" tform="store">
  <x-slot name="title">
    @lang('Create size')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label>@lang('Short name') (@lang('For coding'))</label>
      <input wire:model.lazy="short_name" type="text" class="form-control" maxlength="6" placeholder="{{ __('max :characters characters', ['characters' => 6]) }}" {{ $is_parent_size ? 'disabled' : '' }} />
      @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <div class="custom-control custom-switch mt-4">
        <input type="checkbox" class="custom-control-input" id="customSwitch1" wire:model="is_parent_size">
        <label class="custom-control-label" for="customSwitch1">@lang('Is parent size')</label>
      </div>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

