@extends('frontend.layouts.app_ga')

@section('title', __('Login'))

@section('content')

        <!-- Hero
        ================================================== -->

            @include('frontend.includes_ga.hero')

        <!-- Rev slider section
        ================================================== -->

            @include('frontend.includes_ga.rev-slider')

        <!-- Logos section
        ================================================== -->

            @include('frontend.includes_ga.logos')

@endsection