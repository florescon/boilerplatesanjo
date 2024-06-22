@extends('backend.layouts.app')

@section('title', __('Quotation'))

@section('breadcrumb-links')
    {{-- @include('backend.order.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <livewire:backend.store.order-store-table status="quotations_store"/>

@endsection
