@extends('backend.layouts.app')

@section('title', __('Station'))

@section('breadcrumb-links')
    @include('backend.station_production.includes.breadcrumb-links')
@endsection

@section('content')

      <div class="alert alert-danger alert-dismissible fade show " role="alert">
        <strong>¡Estaciones eliminadas!</strong> <p class="d-inline text-primary">Eliga 'Historial'</p> </a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <livewire:backend.station.station-production-table status="deleted"/>

@endsection

@push('after-scripts')
    <script>
        function disableKeyUpDown() {
            document.getElementById('selectStatus').addEventListener('keydown', function(event) {
                if (event.key === 'ArrowUp' || event.key === 'ArrowDown' || event.key === 'PageUp' || event.key === 'PageDown') {
                    event.preventDefault();
                }
            });
        }
    </script>

    <script>
        Livewire.on('clear-personal', clear => {
            jQuery(document).ready(function () {
                $("#userselect").val('').trigger('change')
            });
        })
    </script>

@endpush