@extends('backend.layouts.app')

@section('title', __('Suborder outputs'))

@section('content')

    <livewire:backend.order.suborders :order="$order"/>

@endsection
