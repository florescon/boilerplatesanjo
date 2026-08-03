@extends('backend.layouts.app')

@section('title', __('By Product'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')

    <div class="page">

        <livewire:backend.charts.graph-by-product />

    </div>

@endsection

@section('after-scripts')


@endsection