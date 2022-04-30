@extends('backend.layouts.app')

@section('title', __('Inventories'))

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css_custom/technext.css') }}">
@endpush

@section('content')
	<div class="row">
	    <div class="col-sm-4">
	        <div class="card pb-5 shadow">
	            <img class="img-fluid" src="{{ asset('/img/ga/stock.jpg')}}" alt="">
	            <div class="card-body">
	                <div class="text-center">
	                    <h4 class="card-widget__title text-dark mt-3">Producto Terminado</h4>
	                    <p class="text-muted">Stock</p>
	                    <a class="btn gradient-4 btn-lg border-0 btn-rounded px-5 text-white" href="{{ route('admin.inventory.stock') }}">Inventariar</a>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-4">
	        <div class="card pb-5 shadow">
	            <img class="img-fluid" src="{{ asset('/img/ga/revision.jpg')}}" alt="">
	            <div class="card-body">
	                <div class="text-center">
	                    <h4 class="card-widget__title text-dark mt-3">Materia Prima</h4>
	                    <p class="text-muted">Almacén Materia Prima</p>
	                    <a class="btn gradient-4 btn-lg border-0 btn-rounded px-5 text-white" href="{{ route('admin.inventory.feedstock') }}">Inventariar</a>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-4">
	        <div class="card pb-5 shadow">
	            <img class="img-fluid" src="{{ asset('/img/ga/store.jpg')}}" alt="">
	            <div class="card-body">
	                <div class="text-center">
	                    <h4 class="card-widget__title text-dark mt-3">Tienda</h4>
	                    <p class="text-muted">Stock Tienda</p>
	                    <a class="btn gradient-4 btn-lg border-0 btn-rounded px-5 text-white" href="{{ route('admin.inventory.store') }}">Inventariar</a>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

@endsection
