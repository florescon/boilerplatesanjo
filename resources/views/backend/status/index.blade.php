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
            <strong class="shadow-effects" style="color: white;"> @lang('Order statuses') </strong>
            <div class="card-header-actions mb-5">
              <a href="#" class="card-header-action" style="color: green;"  data-toggle="modal" wire:click="createmodal()" data-target="#exampleModal"><i class="c-icon cil-plus"></i> @lang('Create departament') </a>
            </div>
        </x-slot>

        <x-slot name="body">

            <livewire:backend.status.status-table />

        </x-slot>
    </x-backend.card>

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("departamentStore", () => {
          $("#exampleModal").modal("hide");
      });
    </script>


    <script type="text/javascript">
      Livewire.on("departamentUpdate", () => {
          $("#updateModal").modal("hide");
      });
    </script>
@endpush
