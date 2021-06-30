@extends('backend.layouts.app')

@section('title', __('Move between stocks'))

@section('content')

    <livewire:backend.product.move-stock-product :product="$product"/>

@endsection
