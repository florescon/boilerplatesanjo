@extends('backend.layouts.app')

@section('title', __('Order'))

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.store.order-store-table status="sales_store"/>

@endsection
