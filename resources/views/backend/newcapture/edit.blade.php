@extends('backend.layouts.app')

@section('title', __('Station'))

@section('breadcrumb-links')
    {{-- @include('backend.product.includes.breadcrumb-links') --}}
@endsection

@push('after-styles')
  <style type="text/css">
    body::before {
      --size: 60px;
      --line: hsl(0 0% 0% / 0.15);
      content: '';
      height: 100vh;
      width: 100vw;
      position: fixed;
      background: linear-gradient(
        90deg,
        var(--line) 1px,
        transparent 1px var(--size)
      )
      50% 50% / var(--size) var(--size),
      linear-gradient(var(--line) 1px, transparent 1px var(--size)) 50% 50% /
      var(--size) var(--size);
      mask: linear-gradient(-15deg, transparent 30%, white);
      top: 0;
      transform-style: flat;
      pointer-events: none;
      z-index: -1;
    }
       /*
    *
    * ==========================================
    * CUSTOM UTIL CLASSES
    * ==========================================
    *
    */
    .collapsible-link {
      width: 100%;
      position: relative;
      text-align: left;
    }

    .collapsible-link::before {
      content: "\f107";
      position: absolute;
      top: 50%;
      right: 0.8rem;
      transform: translateY(-50%);
      display: block;
      font-family: "FontAwesome";
      font-size: 1.1rem;
    }

    .collapsible-link[aria-expanded="true"]::before {
      content: "\f106";
    }
  </style>
@endpush

@section('content')

  {{-- <livewire:backend.station.edit-station :station="$station"/> --}}

@endsection

@push('after-scripts')
  <script type="text/javascript">
  </script>
@endpush