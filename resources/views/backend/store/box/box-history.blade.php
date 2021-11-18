@extends('backend.layouts.app')

@section('title', __('Daily cash closing panel'))

@section('breadcrumb-links')
    @include('backend.store.includes.breadcrumb-links-box-history')
@endsection

@section('content')

    <livewire:backend.store.box.box-history />

@endsection