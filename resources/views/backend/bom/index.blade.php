@extends('backend.layouts.app')

@section('title', __('Bom of Materials'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')
    <x-backend.card>
        <x-slot name="body">
		    <livewire:backend.bom.bom-table />
    	</x-slot>
    </x-backend.card>
@endsection
