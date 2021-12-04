<!-- Modal -->
<div wire:ignore.self  class="modal fade"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('Create document')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form wire:submit.prevent="store">
        <div class="modal-body">
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
          <button type="submit" class="btn btn-primary">@lang('Save')</button>
        </div>
      </form>
    </div>
  </div>
</div>
