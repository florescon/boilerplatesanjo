<x-utils.modal id="showModal" width="modal-dialog-centered">
  <x-slot name="title">
    @lang('Show feedstock')
  </x-slot>

  <x-slot name="content">

    <table class="table">
      <tbody>

        <tr>
          <th scope="row">@lang('Part number')</th>
          <td>   
            <x-utils.undefined :data="$part_number"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Name')</th>
          <td>   
            <x-utils.undefined :data="$name"/>
          </td>
        </tr>
        
        <tr>
          <th scope="row">@lang('Price')</th>
          <td>   
            <x-utils.undefined :data="$price"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Unit')</th>
          <td>   
            <x-utils.undefined :data="$unit"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Color')</th>
          <td>   
            <x-utils.undefined :data="$color"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Size_')</th>
          <td>   
            <x-utils.undefined :data="$size"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Stock')</th>
          <td>   
            <x-utils.undefined :data="$stock"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Acquisition cost')</th>
          <td>   
            <x-utils.undefined :data="$acquisition_cost"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Description')</th>
          <td>   
            <x-utils.undefined :data="$description"/>
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

        @if($deleted)
        <tr>
          <th scope="row">@lang('Deleted')</th>
          <td>   
            <x-utils.undefined :data="$deleted"/>
          </td>
        </tr>
        @endif

      </tbody>
    </table>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>