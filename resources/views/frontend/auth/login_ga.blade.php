@extends('frontend.layouts.app_ga')

@section('title', __('Login'))

@section('content')

        <div class="section over-hide padding-top-120 padding-top-mob-nav section-background-24 background-img-top">    
            <div class="section-1400 padding-top-bottom-120">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-7 col-xl-5">
                            <div class="section py-4 py-md-5 px-3 px-sm-4 px-lg-5 over-hide border-4 section-shadow-blue bg-white section-background-24 background-img-top form">

                                <form action="{{ route('frontend.auth.login') }}" class="section" id="loginForm" method="post">
                                {{ csrf_field() }}

                                    <h4 class="mb-4 text-sm-center">
                                        @lang('Login')
                                    </h4>
                                    <div class="form-group">
                                        <input type="email" name="email" id="email" class="form-style big gray-version no-shadow form-style-with-icon section-shadow-blue" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required autofocus autocomplete="email">
                                        <i class="input-icon big uil uil-user"></i>
                                    </div>  
                                    <div class="form-group mt-3">
                                        <input type="password" name="password" id="password" class="form-style big gray-version no-shadow form-style-with-icon section-shadow-blue" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="current-password">
                                        <i class="input-icon big uil uil-lock-alt"></i>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col pr-0">  
                                            <div class="form-group">
                                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} >
                                                <label class="checkbox mb-0 font-weight-500 size-15" for="remember">@lang('Remember Me')</label>
                                            </div>
                                        </div>
                                        <div class="col-auto align-self-center text-right pl-0">    
                                            <x-utils.link :href="route('frontend.auth.password.request')" class="link link-primary" :text="__('Forgot Your Password?')" data-hover="{{ __('Forgot Your Password?') }}" />
                                        </div>
                                    </div>


                                    @if(config('boilerplate.access.captcha.login'))
                                        <div class="row">
                                            <div class="col">
                                                @captcha
                                                <input type="hidden" name="captcha_status" value="true" />
                                            </div><!--col-->
                                        </div><!--row-->
                                    @endif


                                    <div class="row mt-4">  
                                        <div class="col-12 text-sm-center">
                                            <button type="submit" id="submitBtn" class="btn btn-dark-primary">@lang('Sign in')<i class="uil uil-arrow-right size-22 ml-3"></i></button>  
                                        </div>
                                    </div>
                                    @if (config('boilerplate.access.user.registration'))
                                        <p class="mt-4 mb-0 text-sm-center size-16">
                                            @lang('Not registered?') <a href="{{ route('frontend.auth.register') }}" class="link link-dark-primary-2 link-normal animsition-link">@lang('Create an account')</a> 
                                        </p>
                                    @endif
                                </form>                                 


                                @if(config('services.google.active') != false || config('services.facebook.active') != false || config('services.twitter.active') != false)
                                    <div class="col-12 mt-4">
                                        <h6 class="mb-3">
                                            @lang('or register with'):
                                        </h6>
                                    </div>
                                @endif

                                <div class="row mt-3">
                                        @include('frontend.auth.includes.social_ga')
                                </div>

                            </div>  
                        </div>      
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('after-scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerText = '@lang('Logging in...')';
        });
    </script>
@endpush
