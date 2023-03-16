@extends('backend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('title', __('Order'))

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.order.order-table />

@endsection
