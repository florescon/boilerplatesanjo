@extends('backend.layouts.app')

@section('title', __('Edit'))

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <div class="alert alert-success" role="alert">
              <h4 class="alert-heading">¡Información a considerar!</h4>
              <hr>
              <p>Deben existir por lo menos 2 procesos.</p>
              <p>Si en producción quiero agregar un proceso anterior a la primera en secuencia, no es posible.</p>
              <p>Si en producción quiero agregar un proceso posterior a la última en secuencia, no es posible.</p>
            </div>
            <h4>{{ $status->name }}</h4>
        </x-slot>

        <x-slot name="body">
            <livewire:backend.status.edit-status :process="$status->id" />
        </x-slot>

    </x-backend.card>

@endsection
