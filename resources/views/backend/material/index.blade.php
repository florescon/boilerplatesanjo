@extends('backend.layouts.app')

@section('title', __('Feedstock'))

@section('breadcrumb-links')
    @include('backend.material.includes.breadcrumb-links')
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/material.css') }}">
@endpush

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Feedstock') </strong>
        </x-slot>

        <x-slot name="headerActions">

            @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify-quantities'))
                <livewire:backend.material.modify-feedstock />
            @endif

            @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.create'))
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    style="color: orange;"
                    href="{{ route('admin.material.create') }}"
                    :text="__('Create feedstock')"
                />
            @endif
        </x-slot>

        <x-slot name="body">
            <livewire:backend.material-table />
        </x-slot>

        <x-slot name="footer">
          <footer class="footer mt-3">
              <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6 m-auto text-center">
                  <div>
                    <p> 
                      <a href="{{ route('admin.material.records_history') }}">Historial de stock ingresado/sustraido de materia prima</a>
                    </p>
                  </div>
                </div>
                <div class="col-xl-6 m-auto text-center">
                  <div>
                    <p> 
                      <a href="{{ route('admin.material.records_history_group') }}">Historial de stock ingresado/sustraido de materia prima <strong><u>agrupado por fecha</u></strong></a>
                    </p>
                  </div>
                </div>
              </div>
          </footer>
          <footer class="footer mt-3">
              <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6 m-auto text-center">
                  <div>
                    <p> 
                      <a href="{{ route('admin.material.records') }}">Ir a registros de materia prima consumidos</a>
                    </p>
                  </div>
                </div>
              </div>
          </footer>
        </x-slot>
    </x-backend.card>

    <livewire:backend.material.create-material />
    <livewire:backend.material.show-material />

    <livewire:backend.material.modal-stock-material />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("materialStore", () => {
          $("#createMaterial").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("materialUpdate", () => {
          $("#updateStockModal").modal("hide");
      });
    </script>

@endpush
