@extends('frontend.layouts.app')

@section('title', __('Please confirm your password before continuing.'))

@section('content')
    <div class="section over-hide padding-top-120 padding-top-mob-nav section-background-24 background-img-top">    
        <div class="section-1400 padding-top-bottom-120">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-xl-5">
                        <div class="section py-4 py-md-5 px-3 px-sm-4 px-lg-5 over-hide border-4 section-shadow-blue bg-white section-background-24 background-img-top form">
                            <h4 class="mb-4 text-sm-center">
                                @lang('Please confirm your password before continuing.')
                            </h4>

                            <x-forms.post :action="route('frontend.auth.password.confirm')">
                                <div class="form-group">
                                    <input type="password" name="password" class="form-style big gray-version no-shadow section-shadow-blue" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="current-password" />
                                </div><!--form-group-->

                                <div class="row mt-4">  
                                    <div class="col-12 text-sm-center">
                                        <button type="submit" class="btn btn-dark-primary">@lang('Confirm Password')<i class="uil uil-arrow-right size-22 ml-3"></i></button>  
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
