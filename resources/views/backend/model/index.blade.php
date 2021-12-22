@extends('backend.layouts.app')

@section('title', __('Model'))

@section('breadcrumb-links')
    @include('backend.model.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Models') </strong>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                data-toggle="modal" 
                style="color: green;"
                wire:click="$emitTo('backend.model.create-model', 'createmodal')" 
                data-target="#createModel"
                :text="__('Create model')"
            />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.model.model-table />
        </x-slot>
    </x-backend.card>


    <livewire:backend.model.create-model />
    <livewire:backend.model.show-model />
    <livewire:backend.model.edit-model />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("modelStore", () => {
          $("#createModel").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("modelUpdate", () => {
          $("#editModel").modal("hide");
      });
    </script>

@endpush
