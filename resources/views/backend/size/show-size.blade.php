<x-utils.modal id="showModal" width="modal-dialog-centered">
  <x-slot name="title">
    @lang('Show size')
  </x-slot>

  <x-slot name="content">

    <table class="table">
      <tbody>
        <tr>
          <th scope="row">@lang('Name')</th>
          <td>   
            <x-utils.undefined :data="$name"/>
          </td>
        </tr>
        
        <tr>
          <th scope="row">@lang('Slug')</th>
          <td>   
            <x-utils.undefined :data="$slug"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Created')</th>
          <td>   
            <x-utils.undefined :data="$created"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Updated')</th>
          <td>   
            <x-utils.undefined :data="$updated"/>
          </td>
        </tr>

      </tbody>
    </table>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>

