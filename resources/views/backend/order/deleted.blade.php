@extends('backend.layouts.app')

@section('title', __('Order'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.order.order-table status="deleted"/>

@endsection
