@extends('backend.layouts.app')

@section('title', __('Capture Batch'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}">
@endpush

@section('content')

  <livewire:backend.order.capture-batch :order="$order"/>

@endsection

@push('after-scripts')
  <script type="text/javascript">
      function redirect(goto) {
        var conf = confirm("¿Redireccionar?");
        if (conf && goto != '') {
          window.location = goto;
        }
      }

    var selectEl = document.getElementById('redirectSelect');

    selectEl.onchange = function() {
      if (this.value.startsWith('http')) {
        var goto = this.value;
        redirect(goto);
      }
    };
  </script>
@endpush