@extends('backend.layouts.app')

@section('title', __('Service Type'))

@section('breadcrumb-links')
    @include('backend.servicetype.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Service Type')</kbd> </strong>
        </x-slot>


        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.servicetype.create'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    data-toggle="modal" 
                    style="color: green;"
                    wire:click="$emitTo('backend.service-type.create-service', 'createmodal')" 
                    data-target="#createServiceType"
                    :text="__('Create service type')"
                />
            </x-slot>
        @endif

        <x-slot name="body">

            <livewire:backend.service-type.service-table />

        </x-slot>
    </x-backend.card>

    <livewire:backend.service-type.create-service />
    <livewire:backend.service-type.show-service />
    <livewire:backend.service-type.edit-service />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("serviceTypeStore", () => {
          $("#createServiceType").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("serviceTypeUpdate", () => {
          $("#editServiceType").modal("hide");
      });
    </script>
@endpush
