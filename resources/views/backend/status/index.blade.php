@extends('backend.layouts.app')

@section('title', __('Status'))

@section('breadcrumb-links')
    @include('backend.status.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Status') </strong>
        </x-slot>

        <x-slot name="body">

            <livewire:backend.status.status-table />

        </x-slot>
    </x-backend.card>

@endsection
