@extends('backend.layouts.app')

@section('title', __('Size'))

@section('breadcrumb-links')
    @include('backend.size.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Sizes')</kbd> </strong>
        </x-slot>


        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.size.create'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    data-toggle="modal" 
                    style="color: green;"
                    wire:click="$emitTo('backend.size.create-size', 'createmodal')" 
                    data-target="#createSize"
                    :text="__('Create size')"
                />
            </x-slot>
        @endif

        <x-slot name="body">

            <livewire:backend.size.size-table />

        </x-slot>
    </x-backend.card>

    <livewire:backend.size.create-size />
    <livewire:backend.size.show-size />
    <livewire:backend.size.edit-size />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("sizeStore", () => {
          $("#createSize").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("sizeUpdate", () => {
          $("#editSize").modal("hide");
      });
    </script>
@endpush
