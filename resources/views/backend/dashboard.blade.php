@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">

@endpush

@section('content')
    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.order.order')))
    <x-backend.card>
        <x-slot name="body">

            {{-- @lang('Welcome to the Dashboard') --}}
        
            <livewire:backend.dashboard.kanban />

        </x-slot>

        <x-slot name="footer">

          <div class="content-list" data-filter-list="content-list-body">
            <!--end of content list head-->
            <div class="content-list-body">

              <div class="card card-note">
                <div class="card-header">
                  <div class="media align-items-center">
                    <div class="media-body">
                      <h6 class="mb-0" data-filter-by="text">Nota:</h6>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <span data-filter-by="text">A considerar</span>
                    <div class="ml-1 dropdown card-options">
                      <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="cil-hand-point-left"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item text-danger" href="#">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body ml-4" data-filter-by="text">
                  <ul>
                    <li>Los listado muestran un límite de diez registros por defecto, para ello considere los totales en los encabezados de cantidades y en totales de artículos</li>
                  </ul>

                </div>
              </div>

            </div>
          </div>

        </x-slot>
    
    </x-backend.card>
    @endif
@endsection
