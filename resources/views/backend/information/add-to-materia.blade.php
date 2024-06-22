@extends('backend.layouts.app')

@section('title', __('Add to materia'))

@push('after-styles')

@endpush

@section('content')

	<livewire:backend.information.add-to-materia :status="$status" />

@endsection

@push('after-scripts')

@endpush