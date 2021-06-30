@extends('frontend.layouts.app_porto')

@section('title', __('Show shop'))

@section('content')

	<livewire:frontend.shop.shop-show-component :product="$shop"/>

@endsection