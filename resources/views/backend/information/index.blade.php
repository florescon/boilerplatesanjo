@extends('backend.layouts.app')

@section('title', __('Information'))

@push('after-styles')

@endpush

@section('content')

	<livewire:backend.information.show-information />

@endsection

@push('after-scripts')

@endpush