@extends('frontend.layouts.app_ga')

@section('title', __('Dashboard'))

@section('content')
    <div class="section over-hide height-80 section-background-20"> 
        <div class="hero-center-section">
            <div class="section-1400">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="display-8 text-center mb-4">
                                @lang('Dashboard')
                            </h2>
                            <p class="lead text-center mb-0">
                                @lang('You are logged in!')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
