@extends('backend.layouts.app')

@section('title', __('Unit'))

@section('breadcrumb-links')
    @include('backend.unit.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Units') </strong>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                data-toggle="modal" 
                style="color: green;"
                wire:click="$emitTo('backend.unit.create-unit', 'createmodal')" 
                data-target="#createUnit"
                :text="__('Create unit')"
            />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.unit.unit-table />
        </x-slot>
    </x-backend.card>

    <livewire:backend.unit.create-unit />
    <livewire:backend.unit.show-unit />
    <livewire:backend.unit.edit-unit />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("unitStore", () => {
          $("#createUnit").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("unitUpdate", () => {
          $("#editUnit").modal("hide");
      });
    </script>

@endpush
