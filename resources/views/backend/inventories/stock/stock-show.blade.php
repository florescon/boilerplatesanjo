@extends('backend.layouts.app')

@section('title', __('Store Inventory'))

@section('content')

    <livewire:backend.inventory.stock.stock-show-table :inventory="$inventory" />

@endsection