@extends('backend.layouts.app')

@section('title', __('Batch'))

@section('breadcrumb-links')
    {{-- @include('backend.product.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <livewire:backend.batch.batch-table :typeBatch="6" :theName="'Manufacturing'"/>

@endsection