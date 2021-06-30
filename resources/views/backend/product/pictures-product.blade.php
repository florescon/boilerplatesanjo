@extends('backend.layouts.app')

@section('title', __('Product images'))

@section('content')

    <livewire:backend.product.pictures-product :product="$product"/>

@endsection
