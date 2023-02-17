@extends('backend.layouts.app')

@section('title', __('Service Order'))

@section('breadcrumb-links')
    @include('backend.serviceorder.includes.breadcrumb-links')
@endsection

@section('content')

    <livewire:backend.service-order.service-order-list />

@endsection
