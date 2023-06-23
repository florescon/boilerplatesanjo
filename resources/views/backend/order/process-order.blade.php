@extends('backend.layouts.app')

@section('title', __('Process'))

@section('content')
    
    <livewire:backend.order.process-order :order="$order" :status="$status"/>

@endsection
