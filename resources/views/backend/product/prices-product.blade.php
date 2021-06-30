@extends('backend.layouts.app')

@section('title', __('Prices product'))

@section('content')

    <livewire:backend.product.prices-product :product="$product"/>

@endsection
