<x-utils.modal id="showModal" width="modal-dialog-centered">
  <x-slot name="title">
    @lang('Show logged') {{ $user }} <em>{{ $email }}</em>
  </x-slot>

  <x-slot name="content">
    <table class="table">
      <tbody>
        <tr>
          <th scope="row">@lang('Login At')</th>
          <td>   
            <x-utils.undefined :data="$last_login_at"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('IP Address')</th>
          <td>   
            <x-utils.undefined :data="$last_login_ip"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('City')</th>
          <td>   
            <x-utils.undefined :data="$city"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('State name')</th>
          <td>   
            <x-utils.undefined :data="$state_name"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('Postal code')</th>
          <td>   
            <x-utils.undefined :data="$postal_code"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('Latitude')</th>
          <td>   
            <x-utils.undefined :data="$lat"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('Longitude')</th>
          <td>   
            <x-utils.undefined :data="$lon"/>
          </td>
        </tr>
        <tr>
          <th scope="row">@lang('Timezone')</th>
          <td>   
            <x-utils.undefined :data="$timezone"/>
          </td>
        </tr>
      </tbody>
    </table>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>