@extends('backend.layouts.app')

@section('title', __('Consumption product'))

@section('content')

    <livewire:backend.product.consumption-product :product="$product"/>

@endsection

@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("serviceUpdate", () => {
          $("#updateModal").modal("hide");
      });
    </script>

@endpush