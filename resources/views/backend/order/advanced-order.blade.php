@extends('backend.layouts.app')

@section('title', __('Advanced options'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}">
@endpush

@section('content')

    <livewire:backend.order.advanced-order :order="$order"/>

@endsection

@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("reasignUserStore", () => {
          $("#reasignUser").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("reasignDepartamentStore", () => {
          $("#reasignDepartament").modal("hide");
      });
    </script>

@endpush