@extends('frontend.layouts.app_ga')

@section('title', __('Reset Password'))

@section('content')
<div class="section over-hide padding-top-120 padding-top-mob-nav section-background-24 background-img-top">    
    <div class="section-1400 padding-top-bottom-120">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-5">
                    <div class="section py-4 py-md-5 px-3 px-sm-4 px-lg-5 over-hide border-4 section-shadow-blue bg-white section-background-24 background-img-top form">
                        <h4 class="mb-4 text-sm-center">
                            @lang('Reset Password').
                        </h4>

                        <x-forms.post :action="route('frontend.auth.password.email')">

                            <div class="form-group">
                                <input class="form-style big gray-version no-shadow form-style-with-icon section-shadow-blue"
                                    type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('E-mail Address') }}" maxlength="255" required  autocomplete="email" 
                                >
                                <i class="input-icon big uil uil-user"></i>
                            </div>  


                            <div class="row mt-4 mb-4">  
                                <div class="col-12 text-sm-center">
                                    <button type="submit" class="btn btn-dark-primary">
                                        @lang('Send Password Reset Link')
                                        <i class="uil uil-arrow-right size-22 ml-3"></i>
                                    </button>  
                                </div>
                            </div>
                        </x-forms.post>

                    </div>  
                </div>      
            </div>
        </div>
    </div>
</div>
@endsection
