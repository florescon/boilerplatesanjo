@extends('backend.layouts.app')

@section('title', __('Cart'))

@section('content')

	<livewire:backend.cart :fromStore="$fromStore" />

@endsection

