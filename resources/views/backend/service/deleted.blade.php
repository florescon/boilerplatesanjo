@extends('backend.layouts.app')

@section('title', __('Service'))

@section('breadcrumb-links')
    @include('backend.service.includes.breadcrumb-links')
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/services.css') }}">
@endpush

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: red;"> @lang('Deleted services') </strong>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.service.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

    		<livewire:backend.service.service-table status="deleted"/>

		</x-slot>
	</x-backend.card>

@endsection
