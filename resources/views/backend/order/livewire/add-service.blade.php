<x-utils.modal id="addService" tform="store">
  <x-slot name="title">
    @lang('Add service') <i class="cil-plus"></i>
  </x-slot>

  <x-slot name="content">

      <label class="mt-2">@lang('Service')</label>
      <livewire:backend.service.select-service :clear="true"/>
      @error('payment_method') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      @if($service)
        <label class="mt-2 text-center">@lang('Price')</label>
        <input wire:model.defer="price" type="number" step="any" class="form-control"/>

        <label class="mt-2">@lang('Amount')</label>
        <input wire:model.defer="amount" type="number" class="form-control"/>
        @error('amount') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      @endif

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      @if($service)
        <button type="submit" class="btn btn-primary">@lang('Add service')</button>
      @endif
  </x-slot>
</x-utils.modal>