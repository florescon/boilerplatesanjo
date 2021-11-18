@extends('backend.layouts.app')

@section('title', __('Deleted Finances panel'))

@section('breadcrumb-links')
    @include('backend.store.includes.breadcrumb-links-box-history')
@endsection

@section('content')

    <livewire:backend.store.box.box-history status="deleted"/>

@endsection
