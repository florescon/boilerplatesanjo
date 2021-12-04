@extends('backend.layouts.app')

@section('title', __('Brand'))


@section('breadcrumb-links')
    @include('backend.brand.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted brand') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.brand.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.brand.brand-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.brand.show-brand />

@endsection

