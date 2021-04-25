
@push('after-styles')
<style type="text/css">
  
  input.form-control:disabled {
      background-color: #fff;
  }

</style>

@endpush


<!-- Modal Show -->
<div wire:ignore.self  class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="{{ $color ? 'border: '. $color. ' 5px solid' : '' }}">
      <div class="modal-header">
        <h5 class="modal-title" id="showModalLabel">@lang('View color')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <table class="table">
            <tbody>
              <tr>
                <th scope="row">@lang('Name')</th>
                <td>
                  {{ $name }}
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Color')</th>
                <td>          
                  {{ $color }}
                </td>
              </tr>

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