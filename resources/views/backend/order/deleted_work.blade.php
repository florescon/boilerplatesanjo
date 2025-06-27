@extends('backend.layouts.app')

@section('title', __('Order'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links-work')
@endsection

@section('content')

    <livewire:backend.chart.order.order-work-table status="deleted"/>

@endsection
