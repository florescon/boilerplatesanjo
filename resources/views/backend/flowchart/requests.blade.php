@extends('backend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('title', __('Requests'))

@section('breadcrumb-links')
    @include('backend.order.includes.breadcrumb-links')
@endsection

@section('content')

      <div class="alert alert-danger alert-dismissible fade show " role="alert">
        <strong>¡Estás en un apartado antiguo!</strong> Ir al nuevo apartado: <a href="{{ route('admin.order.request_chart_work') }}"> click aquí </a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <livewire:backend.chart.order.order-table />

@endsection
