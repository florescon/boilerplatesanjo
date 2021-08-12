@extends('frontend.layouts.app_ga')

@section('title', __('Two Factor Authentication is required'))

@section('content')

<div class="section over-hide padding-top-120 padding-top-mob-nav section-background-24 background-img-top">    
    <div class="section-1400 padding-top-bottom-120">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-5">
                    <div class="section py-4 py-md-5 px-3 px-sm-4 px-lg-5 over-hide border-4 section-shadow-blue bg-white section-background-24 background-img-top form">
                        <h4 class="mb-4 text-sm-center">
                            @lang('Two Factor Authentication is required').
                        </h4>


                        <x-forms.post :action="$action">
                            @foreach((array)$credentials as $name => $value)
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            @endforeach

                            @if($remember)
                                <input type="hidden" name="remember" value="on">
                            @endif

                            <div class="form-group">
                                <label for="{{ $input }}" class="col-md-12 col-form-label text-md-center">@lang('Authentication Code')</label>

                                <div class="col-md-12">
                                    <input type="text"
                                           name="{{ $input }}"
                                           id="{{ $input }}"
                                           class="form-style form-control big gray-version no-shadow section-shadow-blue {{ $error ? 'is-invalid' : '' }}"
                                           placeholder="123456"
                                           minlength="6"
                                           required />

                                    @if($error)
                                        <div class="invalid-feedback">
                                            {{ __('The Code is invalid or has expired.') }}
                                        </div>
                                    @endif
                                </div>
                            </div><!--form-group-->

                            <div class="row mt-4">  
                                <div class="col-12 text-sm-center">
                                    <button type="submit" class="btn btn-dark-primary">@lang('Confirm Code')<i class="uil uil-arrow-right size-22 ml-3"></i></button>  
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
