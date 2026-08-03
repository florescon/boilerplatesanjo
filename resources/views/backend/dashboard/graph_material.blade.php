@extends('backend.layouts.app')

@section('title', __('Material'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')

    <div class="page">

        <livewire:backend.charts.graph-material />

    </div>

@endsection
