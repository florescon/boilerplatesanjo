<x-utils.modal id="createPayment" tform="store">
  <x-slot name="title">
    @lang('Create payment') <i class="cil-plus"></i>
  </x-slot>

  <x-slot name="content">
      <label class="mt-2">@lang('Amount')</label>
      <input wire:model="amount" type="number" step="any" class="form-control"/>
      @error('amount') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Payment method')</label>
      <livewire:backend.setting.select-payment-method :clear="true"/>
      @error('payment_method') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Comment')</label>
      <input wire:model.lazy="comment" type="text" class="form-control"/>
      @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Date')</label>
      <input wire:model="date" type="date" class="form-control"/>
      @error('date') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Billing')</label>

      <div class="form-check">
        <label class="c-switch c-switch-primary">
          <input type="checkbox" class="c-switch-input" wire:model="isBill">
          <span class="c-switch-slider"></span>
        </label>
      </div>

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save payment')</button>
  </x-slot>
</x-utils.modal>