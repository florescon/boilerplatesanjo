@extends('backend.layouts.app')

@section('title', __('Assignments'))

@section('content')
    
    <livewire:backend.order.assignments-order :order="$order" :status="$status"/>

@endsection
