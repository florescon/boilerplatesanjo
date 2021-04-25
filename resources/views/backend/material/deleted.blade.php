@extends('backend.layouts.app')

@section('title', __('Feedstock'))


@section('breadcrumb-links')
    @include('backend.material.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted feedstocks') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.material.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.material-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.material.show-material />

@endsection

