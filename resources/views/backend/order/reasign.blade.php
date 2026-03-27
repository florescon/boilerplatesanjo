@extends('backend.layouts.app')

@section('title', __('Status records'))

@push('after-styles')
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}"> --}}
@endpush

@section('content')

<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-11 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                          <a href="{{ !$order->from_store ? route('admin.order.edit', $order->id) :  route('admin.store.all.edit', $order->id) }}" class="btn btn-primary" >
                           @lang('Go to edit order')
                          </a>
                      </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

