@extends('backend.layouts.app')

@section('title', __('Size'))


@section('breadcrumb-links')
    @include('backend.size.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted sizes') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.size.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.size.size-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.size.show-size />

@endsection

