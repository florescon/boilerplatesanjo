@extends('backend.layouts.app')

@section('title', __('Service Orders'))

@push('after-styles')
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}"> --}}
@endpush

@section('content')
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="row justify-content-center">

            <livewire:backend.children-order.create-children-order :order="$order"/>

            </div>
        </div>
    </div>

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

@endpush
