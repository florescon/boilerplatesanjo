@extends('backend.layouts.app')

@section('title', __('Vendor'))

@section('breadcrumb-links')
    @include('backend.vendor.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Vendors')</kbd> </strong>
        </x-slot>

        <x-slot name="headerActions">

            @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.vendor.create'))
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    style="color: red;"
                    href="{{ route('admin.vendor.create') }}"
                    :text="__('Create vendor')"
                />
            @endif

        </x-slot>

        <x-slot name="body">

            <livewire:backend.vendor.vendor-table />

        </x-slot>
    </x-backend.card>

    {{-- <livewire:backend.vendor.show-vendor /> --}}

@endsection
