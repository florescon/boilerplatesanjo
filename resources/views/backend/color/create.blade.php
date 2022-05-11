
<!-- Modal -->
<div wire:ignore.self class="modal fade"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="{{ $color ? 'border: '. $color. ' 5px solid' : '' }}">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('Create color')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form wire:submit.prevent="store">
        <div class="modal-body">
          <label>@lang('Name')</label>
          <input wire:model.lazy="name" type="text" class="form-control"/>
          @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="mt-2">@lang('Short name')</label>
          <input wire:model.lazy="short_name" type="text" class="form-control" placeholder="{{ __('max :characters characters', ['characters' => 6]) }}" />
          @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            <strong>@lang('Short name')</strong> Para codificación y para que el cliente ubique el nombre fácilmente. Es identificador único.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <label class="form-label" for="color">@lang('Color')</label>
          <input class="form-control form-control-color" wire:model="color" id="color" type="color" value="#563d7c" title="@lang('Select primary color')">
          @error('color') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <label class="form-label" for="color">@lang('Secondary color')</label>
          <input class="form-control form-control-color" wire:model="secondary_color" id="color" type="color" value="#fff" title="@lang('Select secondary color (optional)')">
          @error('secondary_color') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
          <button type="submit" class="btn btn-primary">@lang('Save')</button>
        </div>
      </form>
    </div>
  </div>
</div>
