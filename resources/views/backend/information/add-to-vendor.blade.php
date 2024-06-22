@extends('backend.layouts.app')

@section('title', __('Add to vendor'))

@push('after-styles')

@endpush

@section('content')

	<livewire:backend.information.add-to-vendor :status="$status" />

@endsection

@push('after-scripts')

@endpush