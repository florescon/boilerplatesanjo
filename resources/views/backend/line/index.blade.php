@extends('backend.layouts.app')

@section('title', __('Line'))

@section('breadcrumb-links')
    @include('backend.line.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Lines')</kbd> </strong>
        </x-slot>

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.line.create'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    data-toggle="modal" 
                    style="color: green;"
                    wire:click="$emitTo('backend.line.create-line', 'createmodal')" 
                    data-target="#createLine"
                    :text="__('Create line')"
                />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.line.line-table />
        </x-slot>
    </x-backend.card>


    <livewire:backend.line.create-line />
    <livewire:backend.line.show-line />
    <livewire:backend.line.edit-line />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("lineStore", () => {
          $("#createLine").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("lineUpdate", () => {
          $("#editLine").modal("hide");
      });
    </script>

@endpush
