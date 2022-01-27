@extends('backend.layouts.app')

@section('title', __('Records products'))

@section('content')

    <livewire:backend.product.product-records-table />

@endsection
