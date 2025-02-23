@extends('backend.layouts.app')

@section('title', __('Quotation'))

@section('breadcrumb-links')
    {{-- @include('backend.order.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @include('backend.order.includes.quotations-old')

    <livewire:backend.store.order-store-table status="quotations_store"/>

@endsection
