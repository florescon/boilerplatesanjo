@extends('backend.layouts.app')

@section('title', __('Kardex'))

@section('content')

  <livewire:backend.material.kardex-material :material="$material" />

@endsection

@push('middle-scripts')


@endpush

@push('after-scripts')
@endpush
