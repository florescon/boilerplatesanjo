@extends('backend.layouts.app')

@section('title', __('Store Inventory'))

@section('content')

    <livewire:backend.inventory.store.store-show-table :inventory="$inventory" />

@endsection