@extends('backend.layouts.app')

@section('title', __('Year-over-year comparison'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')

    <div class="page">

        <livewire:backend.charts.graph-comparative />

    </div>

@endsection
