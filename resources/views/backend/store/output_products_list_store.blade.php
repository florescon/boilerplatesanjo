@extends('backend.layouts.app')

@section('title', __('Output products'))

@section('breadcrumb-links')
    {{-- @include('backend.order.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <livewire:backend.store.order-store-table status="output_products_store"/>

@endsection
