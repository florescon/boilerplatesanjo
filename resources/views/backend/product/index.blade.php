@extends('backend.layouts.app')

@section('title', __('Product'))

@section('breadcrumb-links')
    @include('backend.product.includes.breadcrumb-links')
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/services.css') }}">
@endpush

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Products')</kbd> </strong>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                    style="color: red;"
                    icon="cil-arrow-thick-from-left"
                    class="card-header-action"
                    :href="route('admin.product.out')"
                    :text="__('Vale de Salida PT')"
            />

            <x-utils.link
                    style="color: blue;"
                    icon="cil-tags"
                    class="card-header-action"
                    :href="route('admin.product.list')"
                    :text="__('List of products')"
            />
            <x-utils.link
	                style="color: green;"
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.product.create')"
                    :text="__('Create')"
            />
        </x-slot>

        <x-slot name="body">
    		<livewire:backend.product.product-table :nameStock="$nameStock ?? null" :linkEdit="$linkEdit ?? null" :fromStore="$fromStore ?? false" />
		</x-slot>
	</x-backend.card>

@endsection
