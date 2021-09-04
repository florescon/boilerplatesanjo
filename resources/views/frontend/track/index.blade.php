@extends('frontend.layouts.app_ga')

@section('title', __('Track Order'))

@section('content')
    <div class="section over-hide height-80 section-background-10 mb-5"> 
        <div class="hero-center-section">
            <div class="section-1400">
                <div class="container-fluid" data-scroll-reveal="enter right move 40px over 1.5s after 0.3s">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="display-8 text-center mb-4">
                                Seguimiento de orden/venta
                            </h2>
                            <p class="lead text-center mb-0">
                                Dar seguimiento sin iniciar sesión, <br>con un límite de 30 días para visualizarlo en este apartado <br> a partir de que se ha expedido.
                                <br> Si se ha vencido, inicie sesión.
                            </p>

                            <div class="col-12 text-center" data-scroll-reveal="enter right move 40px over 2.2s after 0.3s">
                                <button data-toggle="modal" data-target="#modal-1" type="button" class="btn mt-3 btn-purple-gradient"><i class="uil uil-arrow-circle-right size-22 mr-2"></i>@lang('Go to track')</button>           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    
    <div class="modal fade modal-small" id="modal-1" tabindex="-1" role="dialog" aria-labelledby="modal-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body z-bigger">
                    <div class="container-fluid">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="uil uil-multiply"></i>
                        </button>
                        <div class="row justify-content-center">
                            <div class="col-7 col-sm-6 col-lg-5 text-center img-wrap mb-5">
                                <img src="{{ asset('/ga/img/search.gif') }}" alt="modal">
                            </div>
                            <form action="{{ route('frontend.track.search') }}" method="GET">
                                <div class="col-12 text-center">
                                    <div class="form-group">
                                        <input type="text" name="slug" class="form-style big with-border form-style-with-icon" placeholder="{{ __('Enter order or sale') }}" id="slug" autocomplete="off" required>
                                        <i class="input-icon big uil uil-enter"></i>
                                    </div>  
                                </div>
                                <div class="col-12 text-center mt-5">
                                    <h5 class="mb-3">@lang('Enter track number!')</h5>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
