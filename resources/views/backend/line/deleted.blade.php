@extends('backend.layouts.app')

@section('title', __('Line'))


@section('breadcrumb-links')
    @include('backend.line.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted lines') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.line.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.line.line-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.line.show-line />

@endsection

