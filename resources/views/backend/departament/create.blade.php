@inject('model', '\App\Domains\Auth\Models\User')

<!-- Modal -->
<div wire:ignore.self  class="modal fade"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('Create departament')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form wire:submit.prevent="store">
        <div class="modal-body">

          <label>@lang('Name')</label>
          <input wire:model.lazy="name" type="text" class="form-control" placeholder="{{ __('Name') }}" />
          @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('Email')</label>
          <input wire:model.lazy="email" type="text" class="form-control" placeholder="{{ __('Email') }}"/>
          @error('email') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('Comment')</label>
          <input wire:model.lazy="comment" type="text" class="form-control" placeholder="{{ __('Comment') }}"/>
          @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('Phone')</label>
          <input wire:model.lazy="phone" type="text" maxlength="10" class="form-control" placeholder="{{ __('Phone') }}"/>
          @error('phone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('Address')</label>
          <input wire:model.lazy="address" type="text" class="form-control" placeholder="{{ __('Address') }}"/>
          @error('address') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('RFC')</label>
          <input wire:model.lazy="rfc" type="text" class="form-control" placeholder="{{ __('RFC') }}"/>
          @error('rfc') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('Type price')</label>
          <select wire:model.lazy="type_price" name="type_price" class="form-control">
              <option value="{{ $model::PRICE_RETAIL }}">@lang('Retail price')</option>
              <option value="{{ $model::PRICE_AVERAGE_WHOLESALE }}">@lang('Average wholesale price')</option>
              <option value="{{ $model::PRICE_WHOLESALE }}">@lang('Wholesale price')</option>
          </select>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
          <button type="submit" class="btn btn-primary">@lang('Save')</button>
        </div>
      </form>
    </div>
  </div>
</div>
