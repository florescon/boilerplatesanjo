@extends('backend.layouts.app')

@section('title', __('Unit'))


@section('breadcrumb-links')
    @include('backend.unit.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted units') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.unit.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.unit.unit-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.unit.show-unit />

@endsection
