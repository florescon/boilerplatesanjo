@extends('backend.layouts.app')

@section('title', __('Service Type'))


@section('breadcrumb-links')
    @include('backend.size.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted services type') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.servicetype.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.service-type.service-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.service-type.show-service />

@endsection

