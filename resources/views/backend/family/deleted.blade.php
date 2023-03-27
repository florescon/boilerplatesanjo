@extends('backend.layouts.app')

@section('title', __('Family'))

@section('breadcrumb-links')
    @include('backend.family.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted families') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.family.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.family.family-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.family.show-family />

@endsection

