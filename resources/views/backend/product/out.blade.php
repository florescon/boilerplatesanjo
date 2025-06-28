@extends('backend.layouts.app')

@section('title', __('Finished product release form'))

@push('after-styles')
  <link rel="stylesheet" href="{{ asset('css_custom/pos.css') }}">

  <style type="text/css">
    .table-striped>tbody>tr:nth-child(odd)>td,
    .table-striped>tbody>tr:nth-child(odd)>th {
      background-color: #FDFD96;
    }
  </style>
@endpush

@section('content')

  <div class="pcoded-content">

      <div class="pcoded-inner-content">

          <div class="main-body">
              <div class="page-wrapper">
                  <div class="row mb-4" style="margin-top: -30px">
                    <div class="col-sm-6">
                      <div class="card shadow-lg">
                        <div class="card-body text-center">
                          <a href="{{ route('admin.product.out_history') }}" class=""><i class="fa fa-list"></i> @lang('Finished product release forms list')</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="card shadow-lg">
                        <div class="card-body text-center">
                          <a href="#!" data-toggle="modal" wire:click="searchproduct()" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Add product')</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="page-body">

                        <div class="row">
                        
                          <livewire:backend.product.out-product />

                          <livewire:backend.product.out-product-summary />

                        </div>

                  </div>

              </div>
              <div id="styleSelector"> </div>
          </div>
      </div>
  </div>

  <livewire:backend.cartdb.search-products-out :typeSearch="'out'" branchIdSearch="0"/>

@endsection

@push('after-scripts')
  <script>
      document.addEventListener('DOMContentLoaded', function () {
          window.livewire.on('redirectToTicketOut', url => {
              window.open(url, '_blank'); // Abre la URL en una nueva pestaña
          });
      });
  </script>
@endpush