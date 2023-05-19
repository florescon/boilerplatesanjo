@extends('backend.layouts.app')

@section('title', __('Outputs'))

@section('content')

    <livewire:backend.order.suborders :order="$order"/>

@endsection
