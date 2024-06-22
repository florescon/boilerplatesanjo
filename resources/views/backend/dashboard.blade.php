@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')
    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.dashboard.kanban')))
    {{-- <x-backend.card> --}}
        {{-- <x-slot name="body"> --}}

          {{-- <livewire:backend.dashboard.apex /> --}}

          {{-- @lang('Welcome to the Dashboard') --}}
      
          {{-- <livewire:backend.dashboard.kanban /> --}}

        {{-- </x-slot> --}}
    
    {{-- </x-backend.card> --}}
    @endif
@endsection
