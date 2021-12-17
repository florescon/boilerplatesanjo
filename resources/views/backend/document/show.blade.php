<!-- Modal Show -->
<div wire:ignore.self  class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showModalLabel">@lang('View document')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <table class="table">
            <tbody>
              <tr>
                <th scope="row">@lang('Title')</th>
                <td>
                  {{ $title }}
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('File DST')</th>
                <td>          
                  {!! $file_dst !!}
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('File EMB')</th>
                <td>          
                  {!! $file_emb !!}
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Comment')</th>
                <td>   
                  <x-utils.undefined :data="$comment"/>
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Enabled')</th>
                <td>          
                  <p>{!! $is_enabled !!}</p>
                </td>
              </tr>
              @if($imageShow)
              <tr>
                <th scope="row">@lang('Image')</th>
                <td>          
                  <img src="{{ asset('/storage/' . $imageShow) }}" width="400px">
                </td>
              </tr>
              @endif
              <tr>
                <th scope="row">@lang('Created at')</th>
                <td>   
                  {{ $created }}       
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Updated at')</th>
                <td>          
                  <p>{{ $updated }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
        </div>
    </div>
  </div>
</div>