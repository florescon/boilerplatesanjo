<!-- Modal Update -->
<div wire:ignore.self  class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">@lang('Update document_')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form wire:submit.prevent="update">
        <div class="modal-body">

          <input type="hidden" wire:model="selected_id">

          <label>@lang('Title')</label>
          <input wire:model.lazy="title" type="text" class="form-control"/>
          @error('title') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

          <div class="row mt-2">
            <div class="col">
              <label>@lang('Width') (mm)</label>
              <input type="text" wire:model.lazy="width" class="form-control" placeholder="{{ __('Width').' (mm)' }}">
              @error('width') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
            <div class="col">
              <label>@lang('Height') (mm)</label>
              <input type="text" wire:model.lazy="height" class="form-control" placeholder="{{ __('Height').' (mm)' }}">
              @error('height') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
          </div>

          <div class="row mt-2">
            <div class="col">
              <label>@lang('Stitches')</label>
              <input type="text" wire:model.lazy="stitches" class="form-control" placeholder="{{ __('Stitches') }}">
              @error('stitches') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
            <div class="col">
              <label>@lang('PPM')</label>
              <input type="text" wire:model.lazy="ppm" class="form-control" placeholder="{{ __('Height') }}">
              @error('ppm') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label class="mt-4">
                @lang('File DST')
                <br>
                {!! $file_dst_label !!}
              </label>
            
              <input wire:model="file_dst" type="file" class="form-control-file"/>
              @error('file_dst') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
            <div class="col">
              <label class="mt-4">
                @lang('File EMB')
                <br>
                {!! $file_emb_label !!}
              </label>
              
              <input wire:model="file_emb" type="file" class="form-control-file"/>
              @error('file_emb') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label class="mt-4">
                @lang('File PDF')
                <br>
                {!! $file_pdf_label !!}
              </label>

              <input wire:model="file_pdf" type="file" class="form-control-file"/>
              @error('file_pdf') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>

            <div class="col">
              <label class="mt-4">
                @lang('Time') i:s
              </label>

              <input wire:model.lazy="lapse" type="text" class="form-control" disabled/>
              @error('lapse') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>

          </div>          

          <label class="mt-4">
            @lang('Image')
            <br>
            @if($file_image_label && !$image)
              <img class="card-img-top" src="{{ asset('/storage/' . $file_image_label) }}" alt="Card image cap">
            @endif
            @if ($image)
                <br><br>
                @php
                    try {
                       $url = $image->temporaryUrl();
                       $photoStatus = true;
                    }catch (RuntimeException $exception){
                        $this->photoStatus =  false;
                    }
                @endphp
                @if($photoStatus)
                    <img class="img-fluid" alt="Responsive image" src="{{ $url }}">
                @else
                    @lang('Something went wrong while uploading the file.')
                @endif
            @endif
          </label>

          <div class="custom-file">
            <input type="file" wire:model.lazy="image" class="custom-file-input @error('image') is-invalid  @enderror" id="customFileLangHTML">
            <label class="custom-file-label" for="customFileLangHTML" data-browse="Principal">@lang('Image')</label>
          </div>

          <div wire:loading wire:target="image">@lang('Uploading')...</div>
          @error('image') <span class="text-danger">{{ $message }}</span> @enderror

          <label class="mt-4">@lang('Comment')</label>
          <input wire:model.lazy="comment" type="text" class="form-control"/>
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