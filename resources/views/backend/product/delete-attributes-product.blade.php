@extends('backend.layouts.app')

@section('title', __('Delete attributes'))

@section('content')

    <livewire:backend.product.delete-attributes-product :product="$product"/>

@endsection
