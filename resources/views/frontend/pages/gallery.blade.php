@extends('frontend.layouts.app_ga')

@section('title', __('Gallery'))

@section('content')

        <div class="section over-hide padding-top-120 padding-top-mob-nav padding-bottom-120">  
            <div class="section-1400 pt-xl-4">
                <div class="container-fluid padding-top-bottom-80">
                    <div class="row">
                        <div class="col-lg">
                            <h2 class="display-8 mb-3">
                                @lang('Gallery')
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
                    <div class="row">
                        <div class="col-md-6 img-slice-wrap mb-5 over-hide">
                            <div class="section over-hide border-4">
                                <a href="{{ route('frontend.pages.gallery') }}" class="hover-target animsition-link hover-portfolio-box">
                                    <div class="scroll-img" style="background-image: url({{ asset('/ga/img/slider/baca.jpg') }});"></div>
                                    <p>bacaloni PRO</p>
                                    {{-- <h5>Mobil</h5> --}}
                                </a>
                            </div>
                        </div>
                        <div class="section clearfix"></div>
                        <div class="offset-md-3 col-md-6 img-slice-wrap mb-5 over-hide">
                            <div class="section over-hide border-4">
                                <a href="{{ route('frontend.pages.gallery') }}" class="hover-target animsition-link hover-portfolio-box">
                                    <div class="scroll-img" style="background-image: url({{ asset('/ga/img/slider/bibo.jpg') }});"></div>
                                    <p>BIBO</p>
                                </a>
                            </div>
                        </div>  
                        <div class="section clearfix"></div>
                        <div class="offset-md-6 col-md-6 img-slice-wrap mb-5 over-hide">
                            <div class="section over-hide border-4">
                                <a href="{{ route('frontend.pages.gallery') }}" class="hover-target animsition-link hover-portfolio-box">
                                    <div class="scroll-img" style="background-image: url({{ asset('/ga/img/slider/cocina.jpg') }});"></div>
                                    <p>bacaloni PRO</p>
                                </a>
                            </div>
                        </div>
                        <div class="section clearfix"></div>
                        <div class="offset-md-3 col-md-6 img-slice-wrap mb-5 over-hide">
                            <div class="section over-hide border-4">
                                <a href="{{ route('frontend.pages.gallery') }}" class="hover-target animsition-link hover-portfolio-box">
                                    <div class="scroll-img" style="background-image: url({{ asset('/ga/img/slider/arti.jpg') }});"></div>
                                    <p>PORTWEST</p>
                                </a>
                            </div>
                        </div>
                        <div class="section clearfix"></div>
                        <div class="col-md-6 img-slice-wrap mb-5 over-hide">
                            <div class="section over-hide border-4">
                                <a href="{{ route('frontend.pages.gallery') }}" class="hover-target animsition-link hover-portfolio-box">
                                    <div class="scroll-img" style="background-image: url({{ asset('/ga/img/slider/seg.jpg') }});"></div>
                                    <p>PORTWEST</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection



@push('after-scripts')
    <script src="{{ asset('/ga/js/anime.js') }}"></script> 
    <script src="{{ asset('/ga/js/uncover.js') }}"></script>  
    <script src="{{ asset('/ga/js/sliceRevealer.js') }}"></script> 
@endpush