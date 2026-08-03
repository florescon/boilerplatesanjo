@extends('backend.layouts.app')

@section('title', __('Edit'))

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <h4>{{ $status->name }}</h4>
        </x-slot>

        <x-slot name="body">
            <livewire:backend.status.edit-status :process="$status->id" />
        </x-slot>

    </x-backend.card>

@endsection
