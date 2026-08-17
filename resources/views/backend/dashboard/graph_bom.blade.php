@extends('backend.layouts.app')

@section('title', __('Bom Quotations'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')

    <div >

        <livewire:backend.charts.graph-bom />

    </div>

@endsection
