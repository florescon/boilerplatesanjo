<x-utils.modal id="createSize" tform="store">
  <x-slot name="title">
    @lang('Create size')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label>@lang('Short name') (@lang('For coding'))</label>
      <input wire:model.lazy="short_name" type="text" class="form-control" maxlength="6" placeholder="{{ __('max :characters characters', ['characters' => 6]) }}"/>
      @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

