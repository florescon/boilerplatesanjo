@extends('backend.layouts.app')

@section('title', __('Feedstock'))

@section('breadcrumb-links')
    @include('backend.material.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Feedstock') </strong>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                data-toggle="modal" 
                style="color: green;"
                wire:click="$emitTo('backend.material.create-material', 'createmodal')" 
                data-target="#createMaterial"
                :text="__('Create feedstock')"
            />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.material-table />
        </x-slot>
    </x-backend.card>

    <livewire:backend.material.create-material />
    <livewire:backend.material.show-material />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("materialStore", () => {
          $("#createMaterial").modal("hide");
      });
    </script>

{{--     <script type="text/javascript">
      Livewire.on("materialUpdate", () => {
          $("#editMaterial").modal("hide");
      });
    </script>
 --}}
@endpush
