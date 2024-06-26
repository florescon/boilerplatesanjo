@extends('backend.layouts.app')

@section('title', __('Thread'))

@section('breadcrumb-links')
    @include('backend.thread.includes.breadcrumb-links')
@endsection

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Threads')</kbd> </strong>
        </x-slot>

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.store.list'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    data-toggle="modal" 
                    style="color: green;"
                    wire:click="$emitTo('backend.thread.create-thread', 'createmodal')" 
                    data-target="#createThread"
                    :text="__('Create thread')"
                />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.thread.thread-table />
        </x-slot>
    </x-backend.card>


    <livewire:backend.thread.create-thread />
    <livewire:backend.thread.show-thread />
    <livewire:backend.thread.edit-thread />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("threadStore", () => {
          $("#createThread").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("threadUpdate", () => {
          $("#editThread").modal("hide");
      });
    </script>

@endpush
