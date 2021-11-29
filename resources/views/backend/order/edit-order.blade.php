@extends('backend.layouts.app')

@section('title', __('Edit order'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/timeline.css') }}">
    <link rel="stylesheet" href="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/css/base/pages/app-invoice.css">
@endpush

@section('content')

    @if(!$order->parent_order_id)
        <livewire:backend.order.edit-order :order="$order"/>
    @endif

    @if($order->parent_order_id)
        @include('backend.order.suborder')
    @endif

@endsection
