@extends('backend.layouts.app')

@section('title', __('Request'))

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.store.order-store-table status="requests_store"/>

@endsection
