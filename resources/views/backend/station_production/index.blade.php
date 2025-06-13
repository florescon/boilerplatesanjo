@extends('backend.layouts.app')

@section('title', __('Station'))

@section('breadcrumb-links')
    @include('backend.station.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.station.station-production-table/>

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