@extends('backend.layouts.app')

@section('title', __('Activity panel'))

@section('content')
    <livewire:backend.activity.activity-table />
@endsection
