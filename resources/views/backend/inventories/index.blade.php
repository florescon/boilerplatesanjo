@extends('backend.layouts.app')

@section('title', __('Inventories'))

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css_custom/technext.css') }}">
    <style type="text/css">
		.card {
		  font-size: 24px;
		  border: 2px solid black;
		  padding: 2rem 1rem;
		  min-height: 3em;
		  resize: both;
		  border-image: url("data:image/svg+xml;charset=utf-8,%3Csvg width='100' height='100' viewBox='0 0 100 100' fill='none' xmlns='http://www.w3.org/2000/svg'%3E %3Cstyle%3Epath%7Banimation:stroke 5s infinite linear%3B%7D%40keyframes stroke%7Bto%7Bstroke-dashoffset:776%3B%7D%7D%3C/style%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='0%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%232d3561' /%3E%3Cstop offset='25%25' stop-color='%23c05c7e' /%3E%3Cstop offset='50%25' stop-color='%23f3826f' /%3E%3Cstop offset='100%25' stop-color='%23ffb961' /%3E%3C/linearGradient%3E %3Cpath d='M1.5 1.5 l97 0l0 97l-97 0 l0 -97' stroke-linecap='square' stroke='url(%23g)' stroke-width='3' stroke-dasharray='388'/%3E %3C/svg%3E") 1;
		}
    </style>
@endpush

@section('content')
	<div class="row">
	    <div class="col-sm-4">
	        <div class="card shadow">
	            <img class="img-fluid" src="{{ asset('/img/ga/stock.jpg')}}" alt="">
	            <div class="card-body">
	                <div class="text-center">
	                    <h4 class="card-widget__title text-dark mt-3">@lang('Finished product')</h4>
	                    <p class="text-muted">Stock</p>
	                    <a class="btn gradient-4 btn-lg border-0 btn-rounded px-5 text-white mb-5" href="{{ route('admin.inventory.stock.index') }}">Inventariar</a>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-4">
	        <div class="card shadow">
	            <img class="img-fluid" src="{{ asset('/img/ga/revision.jpg')}}" alt="">
	            <div class="card-body">
	                <div class="text-center">
	                    <h4 class="card-widget__title text-dark mt-3">@lang('Feedstock')</h4>
	                    <p class="text-muted">Almacén Materia Prima</p>
	                    <a class="btn gradient-4 btn-lg border-0 btn-rounded px-5 text-white mb-5" href="{{ route('admin.inventory.feedstock.index') }}">Inventariar</a>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-4">
	        <div class="card shadow">
	            <img class="img-fluid" src="{{ asset('/img/ga/store.jpg')}}" alt="">
	            <div class="card-body">
	                <div class="text-center">
	                    <h4 class="card-widget__title text-dark mt-3">@lang('Store')</h4>
	                    <p class="text-muted">Stock Tienda</p>
	                    <a class="btn gradient-4 btn-lg border-0 btn-rounded px-5 text-white mb-5" href="{{ route('admin.inventory.store.index') }}">Inventariar</a>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

@endsection
