@extends('backend.layouts.app')

@section('title', __('Associates'))

@push('after-styles')
  <link rel="stylesheet" href="{{ asset('/css_custom/associates.css')}}">
@endpush

@section('content')

  <livewire:backend.product.associates-products :attribute="$attribute" :link="$link" :nameModel="$nameModel" :subproduct="$subproduct ?? false" />

@endsection
