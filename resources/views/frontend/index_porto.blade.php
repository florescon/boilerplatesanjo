@extends('frontend.layouts.app_porto')

@section('title', __('Login'))

@section('content')

        <div class="container mb-2">

                @include('frontend.includes_porto.info-boxes')

                <div class="row">

                        <div class="col-lg-9">

                                @include('frontend.includes_porto.home-slider')


                                <h2 class="section-title ls-n-10 m-b-4">Featured Products</h2>

                                @include('frontend.includes_porto.feature-products')


                                @include('frontend.includes_porto.brands-slider')


                                <hr class="mt-1 mb-4">

                                @include('frontend.includes_porto.feature-boxes')
                                                
                        </div><!-- End .col-lg-9 -->

                        <div class="sidebar-overlay"></div>
                        <div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>

                        @include('frontend.includes_porto.sidebar-home')

                </div><!-- End .row -->
        </div>
@endsection