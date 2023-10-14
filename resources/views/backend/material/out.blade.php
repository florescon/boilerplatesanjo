@extends('backend.layouts.app')

@section('title', __('Quotation'))

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
                            <a href="{{ route('admin.material.out_history') }}" class=""><i class="fa fa-list"></i> @lang('Warehouse release forms list')</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="card shadow-lg">
                          <div class="card-body text-center">
                            <a href="#!" data-toggle="modal" wire:click="searchproduct()" data-target="#searchProduct"><i class="fa fa-search"></i> @lang('Search feedstock')</a>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="page-body">

                          <div class="row">
                          
                            <livewire:backend.material.out-feedstock />

                            <livewire:backend.material.out-feedstock-summary />

                          </div>

                    </div>

                </div>
                <div id="styleSelector"> </div>
            </div>
        </div>
    </div>

    <livewire:backend.material.search-feedstock/>

@endsection
