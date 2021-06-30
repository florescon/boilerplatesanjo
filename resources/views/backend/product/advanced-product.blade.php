@extends('backend.layouts.app')

@section('title', __('Description product'))

@section('content')

    <livewire:backend.product.advanced-product :product="$product"/>

@endsection
