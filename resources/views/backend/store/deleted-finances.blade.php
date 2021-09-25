@extends('backend.layouts.app')

@section('title', __('Deleted Finances panel'))

@section('breadcrumb-links')
    @include('backend.store.includes.breadcrumb-links-finances')
@endsection

@section('content')

    <livewire:backend.store.finance-table status="deleted"/>

@endsection
