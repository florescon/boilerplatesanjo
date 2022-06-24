@extends('backend.layouts.app')

@section('title', __('History'))

@section('content')

    <livewire:backend.ticket.assignment-history :user="$user"/>

@endsection
