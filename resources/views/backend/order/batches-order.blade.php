@extends('backend.layouts.app')

@section('title', __('Batches'))

@section('content')
    
    <livewire:backend.order.batches-order :order="$order" :status="$status"/>

@endsection
