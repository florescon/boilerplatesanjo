@extends('backend.layouts.app')

@section('title', __('Quotation'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/pos.css') }}">
@endpush

@section('content')

	<div class="pcoded-content">

		<div class="page-header">
			<div class="page-block" style="background: rgba(75,133,75,.5);">
				<div class="row align-items-center">
					<div class="col-md-6">
						<div class="page-header-title">
							<h5 class="m-b-10"><i class="fas fa-store"></i> Producción - {{ ucfirst(now()->monthName).' '.now()->format('j, Y') }}</h5>
							<p class="m-b-0">{{ partDay() }} {{ Auth::user()->name }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<li class="breadcrumb-item mr-4">
							<a href="{{ route('admin.order.quotations') }}" class="text-white"><h3> @lang('List of quotations')</h3></a>
						</li>
						<ul class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="#!" data-toggle="modal" wire:click="searchproduct()" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Search product')</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="pcoded-inner-content">

			<div class="main-body">
				<div class="page-wrapper">

					<div class="page-body">

						  <div class="row">
						  
							<livewire:backend.cartdb.products :type="'quotation'" branchId="0"/>

							<livewire:backend.cartdb.summarydb :typeSummary="'quotation'" branchIdSummary="0" isMain="true"/>

						  </div>

					</div>

				</div>
				<div id="styleSelector"> </div>
			</div>
		</div>
	</div>

	<livewire:backend.cartdb.search-products :typeSearch="'quotation'" branchIdSearch="0"/>

	<livewire:backend.cartdb.create-customer :type="'quotation'" branchId="0" isMain="true"/>

  <div class="layout-switcher" tabindex="1">
    <div class="layout-switcher-head d-flex justify-content-between">
   		<span>Acceso directo &nbsp;</span>
			<i class="cil-chevron-top"></i>
    </div>
    <div class="layout-switcher-body">

      <div class="layout-switcher-option active">
        <a href="#!" data-toggle="modal" wire:click="createcustomer()" data-target="#createCustomer" class="text-white text-center">
          <i class="cil-user-follow"></i>
        	Crear cliente
        </a>
      </div>
      
    </div>
	</div>
	
	{{-- <livewire:backend.store.pos.search-product :onlyType="'products_sale' ?? null"/> --}}

@endsection