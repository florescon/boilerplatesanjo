@extends('backend.layouts.app')

@section('title', __('Status records'))

@section('content')
        
    <livewire:backend.document.document-threads :document="$document" />

@endsection
