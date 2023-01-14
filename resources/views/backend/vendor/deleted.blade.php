@extends('backend.layouts.app')

@section('title', __('Vendor'))

@section('breadcrumb-links')
    @include('backend.vendor.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted vendors') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.vendor.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.vendor.vendor-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    {{-- <livewire:backend.material.show-material /> --}}

@endsection

