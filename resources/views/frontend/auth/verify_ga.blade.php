@extends('frontend.layouts.app_ga')

@section('title', __('Verify Your E-mail Address'))

@section('content')
    <div class="section over-hide height-80 section-background-20"> 
        <div class="hero-center-section">
            <div class="section-1400">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="display-8 text-center mb-4">
                                @lang('Verify Your E-mail Address')
                            </h2>
                            <p class="lead text-center mb-0">
                                @lang('Before proceeding, please check your email for a verification link.')<br>
                                @lang('If you did not receive the email')...
                            </p>

                            <div class="col-12 text-center pt-5"> 
                                <x-forms.post :action="route('frontend.auth.verification.resend')" class="d-inline">
                                    <button class="btn btn-dark-primary" type="submit">@lang('Click here to request another').</button>
                                </x-forms.post>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
