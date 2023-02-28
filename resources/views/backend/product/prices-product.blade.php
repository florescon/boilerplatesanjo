@extends('backend.layouts.app')

@section('title', __('Prices and codes'))

@push('after-styles')
    <style type="text/css">
        .aqua-gradient {
          height: 130px;
          background: linear-gradient(150deg, #53f 15%, #05d5ff 70%, #a6ffcb 94%);
        }
    </style>
@endpush

@section('content')

    <livewire:backend.product.prices-product :product="$product" :nameStock="$nameStock ?? null"/>

@endsection
