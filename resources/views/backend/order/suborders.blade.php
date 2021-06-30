@extends('backend.layouts.app')

@section('title', __('Suborders'))

@section('content')

    <livewire:backend.order.suborders :order="$order"/>

@endsection
