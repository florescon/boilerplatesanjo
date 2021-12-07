<x-utils.modal id="createFinance" tform="store">
  <x-slot name="title">
    @if($checkboxExpense == false)
      @lang('Create income') <i class="cil-plus"></i>
    @else
      @lang('Create expense') <i class="cil-minus"></i>
    @endif
  </x-slot>

  <x-slot name="content">
      <div class="form-group form-check text-center">
        <label class="c-switch c-switch-danger">
          <input type="checkbox" class="c-switch-input" wire:model="checkboxExpense" checked>
          <span class="c-switch-slider"></span>
        </label>
        <div>
          @if($checkboxExpense == false)
            <strong>@lang('I want it to be expense')<i class="cil-hand-point-up"></i></strong>
          @else
            <strong>@lang('Now is an expense')</strong>
          @endif
        </div>
      </div>

      <label>@lang('Name')</label>
      <input wire:model="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Amount')</label>
      <input wire:model="amount" type="number" step="any" class="form-control"/>
      @error('amount') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Payment method')</label>
      <livewire:backend.setting.select-payment-method :clear="true"/>
      @error('payment_method') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Comment')</label>
      <input wire:model="comment" type="text" class="form-control"/>
      @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Date')</label>
      <input wire:model="date" type="date" class="form-control"/>
      @error('date') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label class="mt-2">@lang('Ticket text')</label>
      <input wire:model="ticket_text" type="text" class="form-control"/>
      @error('ticket_text') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      @if($checkboxExpense == false)
        <button type="submit" class="btn btn-primary">@lang('Save income')</button>
      @else
        <button type="submit" class="btn btn-danger">@lang('Save expense')</button>
      @endif
  </x-slot>
</x-utils.modal>

