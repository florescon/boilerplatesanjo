@extends('backend.layouts.app')

@section('title', __('Kardex'))

@push('after-styles')

<style type="text/css">
    table.table-bordered{
        border:1px solid red;
        margin-top:20px;
      }
    table.table-bordered > thead > tr > th{
        border:1px solid red;
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid red;
    }    
    table.table-bordered > tbody > tr > th{
        border:1px solid red;
    }    
</style>

@endpush
    
@section('content')

    <livewire:backend.product.kardex-product :product="$product"/>

@endsection
