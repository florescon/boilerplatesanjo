@extends('backend.layouts.app')

@section('title', __('Edit'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/timeline.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/chart.css') }}"> --}}
    <link rel="stylesheet" href="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/css/base/pages/app-invoice.css">
@endpush

@section('content')

    <livewire:backend.chart.order.edit-order :order="$order"/>

@endsection
