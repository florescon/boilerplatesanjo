@extends('backend.layouts.app')

@section('title', __('Service Order'))

@section('breadcrumb-links')
    @include('backend.serviceorder.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.service-order.service-order-list />

    <livewire:backend.service-order.edit-service-order />

    <livewire:backend.service-order.assign-personal/>

@endsection

@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("serviceOrderUpdate", () => {
          $("#editServiceOrder").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("assignPersonalUpdate", () => {
          $("#assignPersonal").modal("hide");
      });
    </script>

    <script>
        Livewire.on('clear-personal', clear => {
            jQuery(document).ready(function () {
                $("#userselect").val('').trigger('change')
            });
        })
    </script>

@endpush
