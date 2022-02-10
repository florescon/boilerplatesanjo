@extends('backend.layouts.app')

@section('title', __('Brand'))

@section('breadcrumb-links')
    @include('backend.brand.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Brands') </strong>
        </x-slot>

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.brand.create'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    data-toggle="modal" 
                    style="color: green;"
                    wire:click="$emitTo('backend.brand.create-brand', 'createmodal')" 
                    data-target="#createBrand"
                    :text="__('Create brand')"
                />
            </x-slot>
        @endif

        <x-slot name="body">

            <livewire:backend.brand.brand-table />

        </x-slot>
    </x-backend.card>

    <livewire:backend.brand.create-brand />
    <livewire:backend.brand.show-brand />
    <livewire:backend.brand.edit-brand />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("brandStore", () => {
          $("#createBrand").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("brandUpdate", () => {
          $("#editBrand").modal("hide");
      });
    </script>
@endpush
