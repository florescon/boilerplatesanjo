<x-utils.modal id="editSize" width="modal-dialog-centered" tform="update">
  <x-slot name="title">
    @lang('Edit size')
  </x-slot>

  <x-slot name="content">

      <input type="hidden" wire:model="selected_id">

      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Short name')</label>
      <input wire:model.lazy="short_name" type="text" class="form-control" maxlength="4" placeholder="{{ __('max :characters characters', ['characters' => 4]) }}"/>
      @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        <strong>@lang('Short name')</strong> Para codificación y para que el cliente ubique el nombre fácilmente. Es identificador único.
      </div>

      <label class="mt-2">@lang('Sort')</label>
      <input wire:model.lazy="sort" type="text" class="form-control" maxlength="4" placeholder="{{ __('enter number') }}"/>
      @error('sort') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Update changes')</button>

  </x-slot>
</x-utils.modal>

