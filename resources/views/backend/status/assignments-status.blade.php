@extends('backend.layouts.app')

@section('title', __('Assignments'))

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> Productos asignados al personal en estados de ordenes de produccion </strong>
            <h4>{{ $status->name }}</h4>
        </x-slot>

        <x-slot name="body">
            <livewire:backend.status.assignments-status-table status="{{ $status->id }}" />
        </x-slot>

    </x-backend.card>

@endsection
