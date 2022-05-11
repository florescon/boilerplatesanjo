<x-utils.modal id="editFinance" width="modal-dialog-centered" tform="update">
  <x-slot name="title"> 
    @lang('Edit')
  </x-slot>

  <x-slot name="content">
      <input type="hidden" wire:model="selected_id">
      <label>@lang('Name')</label>
      <input wire:model.lazy="name" type="text" class="form-control"/>
      @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label>@lang('Comment')</label>
      <input wire:model.lazy="comment" type="text" class="form-control"/>
      @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

      <label>@lang('Ticket text')</label>
      <input wire:model.lazy="ticket_text" type="text" class="form-control"/>
      @error('ticket_text') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Update changes')</button>

  </x-slot>
</x-utils.modal>