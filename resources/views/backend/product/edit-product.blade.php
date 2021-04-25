@extends('backend.layouts.app')

@section('title', __('Edit product'))

@section('content')

    <livewire:backend.product.edit-product :product="$product"/>

@endsection
