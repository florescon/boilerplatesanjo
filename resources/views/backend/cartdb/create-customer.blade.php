@inject('model', '\App\Domains\Auth\Models\User')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/search-product.css') }}">
@endpush

<x-utils.modal id="createCustomer" tform="store">
  <x-slot name="title">
    @lang('Create customer')
  </x-slot>

  <x-slot name="content">
    <div class="form-row">
      <div class="form-group col">
        <label for="inputName">@lang('Name')<sup>*</sup></label>
        <input type="text" name="name" id="inputName" wire:model.lazy="name" class="form-control" placeholder="{{ __('Name') }}">
        @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
      <div class="form-group col">
        <label for="inputPhone">@lang('Phone')<sup>*</sup></label>
        <input type="text" name="phone" id="inputPhone" wire:model.lazy="phone" class="form-control" placeholder="{{ __('Phone') }}">
        @error('phone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col">
        <label for="inputEmail">@lang('Email')</label>
        <input type="text" name="email" id="inputEmail" wire:model.lazy="email" class="form-control" placeholder="{{ __('Necessary but not required') }}">
        @error('email') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
      <div class="form-group col">
        <label for="inputRFC">@lang('RFC')</label>
        <input type="text" name="rfc" id="inputRFC" wire:model.lazy="rfc" class="form-control" placeholder="{{ __('RFC') }}">
        @error('rfc') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col">
        <label for="inputAddress">@lang('Address')</label>
        <input type="text" name="address" id="inputAddress" wire:model.lazy="address" class="form-control" placeholder="{{ __('Address') }}">
        @error('address') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

    <div class="form-group row">
      <div class="form-group col">

        <label for="inputPrice">@lang('Type price')<sup>*</sup></label>

        <select name="type_price" class="form-control" wire:model.lazy="type_price">
            <option value="{{ $model::PRICE_RETAIL }}">@lang('Retail price')</option>
            <option value="{{ $model::PRICE_AVERAGE_WHOLESALE }}">@lang('Average wholesale price')</option>
            <option value="{{ $model::PRICE_WHOLESALE }}">@lang('Wholesale price')</option>
            <option value="{{ $model::PRICE_SPECIAL }}">@lang('Special price')</option>
        </select>

      </div>
    </div><!--form-group-->
    
    <p class="text-right"><strong class="text-danger">*</strong> @lang('Required')</p>

  </x-slot>

  <x-slot name="footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
    <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>
