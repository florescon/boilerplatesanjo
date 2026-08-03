@extends('backend.layouts.app')

@section('title', __('Status'))

@section('breadcrumb-links')
    @include('backend.status.includes.breadcrumb-links')
@endsection

@push('after-styles')

    <style type="text/css">
        .shadow-primary {   
          box-shadow: 0 1px 2px rgba(0, 0, 0, 0.075) inset, 0 0 10px rgb(0, 0, 193);
        }   

        .shadow-effects{
            font-size:25px;
            font-weight:bold;
            color: transparent;
            letter-spacing: .10em;
            text-shadow: -3px -3px 2px #000,1px -1px 0 #fe6161, 2px 1px 3px red,3px 3px 1px #37408C;
        }  
    </style>

@endpush

@section('content')

    <x-backend.card>

      <x-slot name="header">
          <strong style="color: #85144b;"> <kbd>@lang('Statuses')</kbd> </strong>
      </x-slot>

        <x-slot name="headerActions">
          <x-utils.link
            style="color: #85144b;"
            icon="c-icon cil-plus"
            class="card-header-action"
            data-toggle="modal" 
            wire:click="$emitTo('backend.status.create-status', 'createmodal')" 
            data-target="#createStatus"
            :text="__('Create status')"
          />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.status.status-table />

        </x-slot>
    </x-backend.card>

    <livewire:backend.status.create-status />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("statusStore", () => {
          $("#createStatus").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("statusUpdate", () => {
          $("#updateModal").modal("hide");
      });
    </script>

@endpush