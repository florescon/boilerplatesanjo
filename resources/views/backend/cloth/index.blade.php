@extends('backend.layouts.app')

@section('title', __('Cloth'))


@section('breadcrumb-links')
    @include('backend.cloth.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Cloths') </strong>
        </x-slot>


        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                data-toggle="modal" 
                style="color: green;"
                wire:click="$emitTo('backend.cloth.cloth-form', 'createmodal')" 
                data-target="#createCloth"
                :text="__('Create cloth')"
            />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.cloth.cloth-table />

        </x-slot>
    </x-backend.card>




    <livewire:backend.cloth.cloth-form />
    <livewire:backend.cloth.show-cloth />
    <livewire:backend.cloth.edit-cloth />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("clothStore", () => {
          $("#createCloth").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("clothUpdate", () => {
          $("#editCloth").modal("hide");
      });
    </script>

@endpush
