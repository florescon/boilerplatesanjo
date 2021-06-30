@extends('backend.layouts.app')

@section('title', __('Where is products?'))

@section('content')

    <livewire:backend.order.where-is-products :order="$order"/>

@endsection
