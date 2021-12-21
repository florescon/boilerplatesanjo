<x-utils.modal id="createService" ariaLabelledby="createServiceModal" tform="store">
  <x-slot name="title">
    @lang('Create service')
  </x-slot>

  <x-slot name="content">

    <div class="form-group row">
      <label for="name" class="col-md-2 col-form-label">@lang('Name')<sup>*</sup></label>
      <div class="col-md-10">
          <input type="text" name="name" wire:model.lazy="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}" maxlength="100" autofocus required />
                                  
          @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div><!--form-group-->

    <div class="form-group row">
      <label for="code" class="col-md-2 col-form-label">@lang('Code')</label>
      <div class="col-md-10">
        <input type="text" name="code" wire:model.lazy="code" class="form-control" placeholder="{{ __('Code') }}" value="{{ old('code') }}" maxlength="100" />

        @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div><!--form-group-->

    <div class="form-group row">
      <label for="price" class="col-md-2 col-form-label">@lang('Price')<sup>*</sup></label>
      <div class="col-md-10">
          <input type="text" name="price" wire:model="price" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('price') }}" maxlength="100" required />

          @error('price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div><!--form-group-->

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>