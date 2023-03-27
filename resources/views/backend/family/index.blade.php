@extends('backend.layouts.app')

@section('title', __('Family'))

@section('breadcrumb-links')
    @include('backend.family.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Families')</kbd> </strong>
        </x-slot>

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.family.create'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    data-toggle="modal" 
                    style="color: green;"
                    wire:click="$emitTo('backend.family.create-family', 'createmodal')" 
                    data-target="#createFamily"
                    :text="__('Create family')"
                />
            </x-slot>
        @endif

        <x-slot name="body">

            <livewire:backend.family.family-table />

        </x-slot>
    </x-backend.card>

    <livewire:backend.family.create-family />
    <livewire:backend.family.show-family />
    <livewire:backend.family.edit-family />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("familyStore", () => {
          $("#createFamily").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("familyUpdate", () => {
          $("#editFamily").modal("hide");
      });
    </script>
@endpush
