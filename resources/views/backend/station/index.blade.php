@extends('backend.layouts.app')

@section('title', __('Station'))

@section('breadcrumb-links')
    {{-- @include('backend.product.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <livewire:backend.station.station-table/>

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
@endpush