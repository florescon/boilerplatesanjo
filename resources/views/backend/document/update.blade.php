<!-- Modal Update -->
<div wire:ignore.self  class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">@lang('Update document')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form wire:submit.prevent="update">
        <div class="modal-body">

          <input type="hidden" wire:model="selected_id">

          <label>@lang('Title')</label>
          <input wire:model="title" type="text" class="form-control"/>
          @error('title') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-4">@lang('File DST')</label>
          <input wire:model="file_dst" type="file" class="form-control-file"/>
          @error('file_dst') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-4">@lang('File EMB')</label>
          <input wire:model="file_emb" type="file" class="form-control-file"/>
          @error('file_emb') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-4">@lang('Comment')</label>
          <input wire:model="comment" type="text" class="form-control"/>
          @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
          <button type="submit" class="btn btn-primary">@lang('Update changes')</button>
        </div>
      </form>
    </div>
  </div>
</div>