@extends('backend.layouts.app')

@section('title', __('Consumption product filter'))

@section('content')

    <livewire:backend.product.consumption-product-filter :product="$product"/>

@endsection
