@extends('frontend.layouts.app_ga')

@section('title', __('Cart'))

@section('content')

    <div class="section over-hide padding-top-120 padding-top-mob-nav padding-bottom-120 section-background-24 background-img-top"> 
        <div class="section-1400 pt-xl-4">

        @auth
            @if (auth()->user()->isAdmin())
            <div class="col-12">   
                <div class="alert alert-danger" role="alert">
                    @lang('If you are an administrator, it will be generated in your name. Go to administratrion panel')
                </div>
            </div>
            @endif
        @endauth

            <div class="container-fluid padding-top-bottom-80">
                <div class="row">
                    <div class="col-lg">
                        <h2 class="display-8 mb-3">
                            @lang('Shopping cart')
                        </h2>
                        <p class="lead mb-0 title-text-left-line-small">
                            {{ appName() }} 
                        </p>
                    </div>
                    <div class="col-lg-auto align-self-center mt-4 mt-lg-0">
                        @if (config('boilerplate.frontend_breadcrumbs'))
                            @include('frontend.includes_ga.partials.breadcrumbs')
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="section-1400">
            <div class="container-fluid">
                <livewire:frontend.cart.cart-list />
            </div>
        </div>

    </div>

@endsection