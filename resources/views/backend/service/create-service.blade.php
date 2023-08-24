<x-utils.modal id="createService" width="modal-sm" ariaLabelledby="createServiceModal" tform="store">
  <x-slot name="title">
    @lang('Create service')
  </x-slot>

  <x-slot name="content">

    <div class="form-group row justify-content-center">
      <div class="col-md-10">
          <input type="text" name="name" wire:model.lazy="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}" maxlength="100" autofocus />
                                  
          @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div><!--form-group-->

    <div class="form-group row justify-content-center">
      <div class="col-md-10">
        <input type="text" name="code" wire:model="code" class="form-control" placeholder="{{ __('Code') }}" value="{{ old('code') }}" maxlength="100" />

        @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div><!--form-group-->

    <div class="form-group row justify-content-center">
      <div class="col-md-10">
          <input type="text" name="price" wire:model.lazy="price" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('price') }}" maxlength="100" />

          @error('price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div><!--form-group-->

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>