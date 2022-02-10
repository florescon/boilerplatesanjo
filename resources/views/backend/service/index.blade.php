@extends('backend.layouts.app')

@section('title', __('Service'))

@section('breadcrumb-links')
    @include('backend.service.includes.breadcrumb-links')
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/services.css') }}">
@endpush

@section('content')

    <x-backend.card>
      <x-slot name="header">
          <strong style="color: #85144b;"> @lang('Services') </strong>
      </x-slot>

      @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.service.create'))
        <x-slot name="headerActions">
          <x-utils.link
            style="color: #85144b;"
            icon="c-icon cil-plus"
            class="card-header-action"
            data-toggle="modal" 
            wire:click="$emitTo('backend.service.create-service', 'createmodal')" 
            data-target="#createService"
            :text="__('Create service')"
          />
        </x-slot>
      @endif

      <x-slot name="body">
        <livewire:backend.service.service-table />
      </x-slot>
    </x-backend.card>

    <livewire:backend.service.create-service />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("serviceStore", () => {
          $("#createService").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("serviceUpdate", () => {
          $("#updateModal").modal("hide");
      });
    </script>

@endpush
