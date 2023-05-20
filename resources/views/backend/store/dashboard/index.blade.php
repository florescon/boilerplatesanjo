@extends('backend.layouts.app')

@section('title', __('Dashboard store'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')

    <x-backend.card>
        <x-slot name="body">
        
            <livewire:backend.store.kanban-store />

        </x-slot>
    </x-backend.card>

@endsection
