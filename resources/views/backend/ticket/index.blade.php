@extends('backend.layouts.app')

@section('title', __('Ticket'))

@section('breadcrumb-links')
    {{-- @include('backend.product.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <livewire:backend.ticket.ticket-table />

@endsection
