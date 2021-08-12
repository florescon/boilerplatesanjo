@extends('frontend.layouts.app_ga', ['headerDark' => 'header-dark'])

@section('title', __('Enable Two Factor Authentication'))

@section('content')

        <div class="section over-hide padding-top-120 padding-top-mob-nav"> 
            <div class="top-header-parallax-section section-background-4 background-img-center parallax-hero-1200"></div>
            <div class="background-dark-blue-over-darker"></div>
            <div class="section z-bigger">
                <div class="section-1400 padding-top-bottom-120">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 col-xl-9">
                                 <x-frontend.card>
                                    <x-slot name="header">
                                        @lang('Enable Two Factor Authentication')
                                    </x-slot>

                                    <x-slot name="body">
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <h5><strong>@lang('Step 1: Configure your 2FA app')</strong></h5>

                                                <p>@lang('To enable 2FA, you\'ll need a 2FA authenticator app on your phone. Examples include: Google Authenticator, FreeOTP, Authy, andOTP, and Microsoft Authenticator (Just to name a few).')</p>

                                                <p>@lang('Most applications will let you set up by scanning the QR code from within the app. If you prefer, you may type the key below the QR code in manually.')</p>
                                            </div><!--col-->

                                            <div class="col-xl-8">
                                                <div class="text-center">
                                                    {!! $qrCode !!}

                                                    <p><i class="fa fa-key"> {{ $secret }}</i></p>
                                                </div>
                                            </div><!--col-->
                                        </div><!--row-->

                                        <hr/>

                                        <h5><strong>@lang('Step 2: Enter a 2FA code')</strong></h5>

                                        <p>@lang('Generate a code from your 2FA app and enter it below:')</p>

                                        <livewire:frontend.two-factor-authentication />
                                    </x-slot>
                                </x-frontend.card>


                            </div>      
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
