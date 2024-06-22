@extends('backend.layouts.app')

@section('title', __('Thread'))


@section('breadcrumb-links')
    @include('backend.thread.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted threads') </strong>  
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.thread.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.thread.thread-table status="deleted"/>

        </x-slot>
    </x-backend.card>

    <livewire:backend.thread.show-thread />

@endsection

