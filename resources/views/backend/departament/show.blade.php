<!-- Modal Show -->
<div wire:ignore.self  class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showModalLabel">@lang('View departament')</h5>
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
                <th scope="row">@lang('Email')</th>
                <td>          
                  {{ $email }}
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Comment')</th>
                <td>   
                  <x-utils.undefined :data="$comment"/>
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Phone')</th>
                <td>   
                  <x-utils.undefined :data="$phone"/>
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Address')</th>
                <td>   
                  <x-utils.undefined :data="$address"/>
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('RFC')</th>
                <td>   
                  <x-utils.undefined :data="$rfc"/>
                </td>
              </tr>
              <tr>
                <th scope="row">@lang('Enabled')</th>
                <td>          
                  <p>{!! $is_enabled !!}</p>
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