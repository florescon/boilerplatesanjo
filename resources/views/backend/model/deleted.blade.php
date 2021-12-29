@extends('backend.layouts.app')

@section('title', __('Model'))

@section('breadcrumb-links')
    @include('backend.model.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted models') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.model.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.model.model-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.model.show-model />

@endsection

