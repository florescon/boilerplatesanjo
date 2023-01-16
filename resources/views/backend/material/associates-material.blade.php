@extends('backend.layouts.app')

@section('title', __('Associates'))

@push('after-styles')
  <link rel="stylesheet" href="{{ asset('/css_custom/associates.css')}}">
@endpush

@section('content')

  <livewire:backend.material.associates-materials :attribute="$attribute" :link="$link" :nameModel="$nameModel" />

@endsection
