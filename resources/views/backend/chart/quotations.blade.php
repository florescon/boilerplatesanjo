@extends('backend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('title', __('Quotations'))

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links-work')
@endsection

@section('content')

    <livewire:backend.chart.order.order-table status="quotations"/>

@endsection
