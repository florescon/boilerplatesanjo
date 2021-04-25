@extends('backend.layouts.app')

@section('title', __('Consumption product'))

@section('content')

    <livewire:backend.product.consumption-product :product="$product"/>

@endsection
