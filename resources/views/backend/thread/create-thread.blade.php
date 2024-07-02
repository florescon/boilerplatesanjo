<x-utils.modal id="createThread" tform="store">
  <x-slot name="title">
    @lang('Create thread')
  </x-slot>

  <x-slot name="content">
      <label>@lang('Name')</label>
      <input wire:model.defer="name" type="text" class="form-control" placeholder="{{ __('Name') }}" />
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Code')</label>
      <input wire:model.defer="code" type="text" class="form-control" placeholder="{{ __('Code') }}"/>
      @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Brand')</label>
      <x-utils.virtual-select 
        wire:model.defer="brand_id"
        :options="[
            'options' => collect($brands)->map(function($brand) {
                return [
                    'label' => $brand->name,
                    'value' => $brand->id
                ];
            })->toArray(),
           'selectedValue' => [],
           'showValueAsTags' => true,
        ]"
      />
      @error('brand_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

