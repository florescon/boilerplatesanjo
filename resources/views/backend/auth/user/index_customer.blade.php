@extends('backend.layouts.app')

@section('title', __('Users'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Customers')
        </x-slot>

            <x-slot name="headerActions">
                @if ($logged_in_user->can('admin.access.user.exportcustomer') || ($logged_in_user->hasAllAccess()))
                    <x-utils.link
                        icon="c-icon cil-applications-settings"
                        class="card-header-action"
                        :href="route('admin.auth.user.exportcustomer')"
                        :text="__('Export Customers')"
                    />
                @endif
                @if ($logged_in_user->can('admin.access.user.exportcustomer') || $logged_in_user->hasAllAccess())
                    <x-utils.link
                        icon="c-icon cil-plus"
                        class="card-header-action"
                        :href="route('admin.auth.user.create_customer')"
                        :text="__('Create Customer')"
                    />
                @endif
            </x-slot>

        <x-slot name="body">
            <livewire:backend.users-table :customers="true"/>
        </x-slot>
    </x-backend.card>
@endsection
