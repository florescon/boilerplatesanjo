@extends('backend.layouts.app')

@section('title', __('Cloth'))


@section('breadcrumb-links')
    @include('backend.cloth.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted cloths') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.cloth.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.cloth.cloth-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.cloth.show-cloth />

@endsection
