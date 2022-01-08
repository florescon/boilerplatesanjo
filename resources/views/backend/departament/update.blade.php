@inject('model', '\App\Domains\Auth\Models\User')

<!-- Modal Update -->
<div wire:ignore.self  class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">@lang('Update departament')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form wire:submit.prevent="update">
        <div class="modal-body">

          <input type="hidden" wire:model="selected_id">

          <label>@lang('Name')</label>
          <input wire:model.lazy="name" type="text" class="form-control"/>
          @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('Email')</label>
          <input wire:model.lazy="email" type="text" class="form-control"/>
          @error('email') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('Comment')</label>
          <input wire:model.lazy="comment" type="text" class="form-control"/>
          @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('Phone')</label>
          <input wire:model.lazy="phone" type="text" maxlength="10" class="form-control"/>
          @error('phone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('Address')</label>
          <input wire:model.lazy="address" type="text" class="form-control"/>
          @error('address') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('RFC')</label>
          <input wire:model.lazy="rfc" type="text" class="form-control"/>
          @error('rfc') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('Type price')</label>
          <select wire:model.lazy="type_price" name="type_price" class="form-control">
              <option value="{{ $model::PRICE_RETAIL }}" {{ $type_price === $model::PRICE_RETAIL ? 'selected' : '' }}>@lang('Retail price')</option>
              <option value="{{ $model::PRICE_AVERAGE_WHOLESALE }}" {{ $type_price === $model::PRICE_AVERAGE_WHOLESALE ? 'selected' : '' }}>@lang('Average wholesale price')</option>
              <option value="{{ $model::PRICE_WHOLESALE }}" {{ $type_price === $model::PRICE_WHOLESALE ? 'selected' : '' }}>@lang('Wholesale price')</option>
          </select>
          @error('type_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
          <button type="submit" class="btn btn-primary">@lang('Update changes')</button>
        </div>
      </form>
    </div>
  </div>
</div>