@extends('backend.layouts.app')

@section('title', __('Print feedstock'))

@section('content')
<x-backend.card shadowClass="0">
    <x-slot name="title">
        @lang('Show feedstock')
    </x-slot>

    <x-slot name="body">

        <table class="table">
          <tbody>

            <tr>
              <th scope="row">@lang('Code')</th>
              <td>   
                <x-utils.undefined :data="$material->part_number"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Name')</th>
              <td>   
                <x-utils.undefined :data="$material->name"/>
              </td>
            </tr>
            
            <tr>
              <th scope="row">@lang('Price')</th>
              <td>   
                <x-utils.undefined :data="$material->price"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Unit')</th>
              <td>   
                <x-utils.undefined :data="optional($material->unit)->name"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Color')</th>
              <td>   
                <x-utils.undefined :data="optional($material->color)->name"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Size_')</th>
              <td>   
                <x-utils.undefined :data="optional($material->size)->name"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Stock')</th>
              <td>   
                <x-utils.undefined :data="$material->stock"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Acquisition cost')</th>
              <td>   
                <x-utils.undefined :data="$material->acquisition_cost"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Description')</th>
              <td>   
                <x-utils.undefined :data="$material->description"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Vendor')</th>
              <td>   
                <x-utils.undefined :data="optional($material->vendor)->name"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Family')</th>
              <td>   
                <x-utils.undefined :data="optional($material->family)->name"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Created')</th>
              <td>   
                <x-utils.undefined :data="$material->created_at"/>
              </td>
            </tr>

            <tr>
              <th scope="row">@lang('Updated')</th>
              <td>   
                <x-utils.undefined :data="$material->updated_at"/>
              </td>
            </tr>

            @if($material->deleted)
            <tr>
              <th scope="row">@lang('Deleted')</th>
              <td>   
                <x-utils.undefined :data="$material->deleted_at"/>
              </td>
            </tr>
            @endif

          </tbody>
        </table>
     </x-slot>

</x-backend.card>
@endsection

@push('after-scripts')
  <script src="{{ asset('/js_custom/app-invoice-print.js') }}"></script>
@endpush