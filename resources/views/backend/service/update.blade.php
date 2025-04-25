<!-- Modal Update -->
<div wire:ignore.self  class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">@lang('Update service')</h5>
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

          <label>@lang('Code')</label>
          <input wire:model.lazy="code" type="text" class="form-control" required />
          @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label>@lang('Price')</label>
          <input wire:model.lazy="price" type="number" step="any" class="form-control"/>
          @error('price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
          <button type="submit" class="btn btn-primary">@lang('Update')</button>
        </div>
      </form>
    </div>
  </div>
</div>