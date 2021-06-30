@extends('backend.layouts.app')

@section('title', __('Deleted status'))

@section('breadcrumb-links')
    @include('backend.status.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Deleted status') </strong>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.status.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.status.status-table status="deleted"/>

        </x-slot>
    </x-backend.card>

@endsection
