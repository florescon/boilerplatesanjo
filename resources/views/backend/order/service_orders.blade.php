@extends('backend.layouts.app')

@section('title', __('Service Orders'))

@push('after-styles')
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}"> --}}
@endpush

@section('content')
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="row justify-content-center">

            <livewire:backend.service-order.create-service-order :order="$order"/>

            <livewire:backend.service-order.service-order-table :order="$order"/>

            <livewire:backend.service-order.edit-service-order />

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("serviceOrderUpdate", () => {
          $("#editServiceOrder").modal("hide");
      });
    </script>

@endpush
