@extends('frontend.layouts.app_ga', ['headerDark' => 'header-dark'])

@section('title', __('Two Factor Recovery Codes'))

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
                                    @lang('Two Factor Recovery Codes')
                                </x-slot>

                                <x-slot name="body">
                                    <h5>@lang('Save your two factor recovery codes:')</h5>

                                    <p>@lang('Recovery codes are used to access your account in the event you no longer have access to your authenticator app.')</p>

                                    <p class="text-danger"><strong>@lang('Save these codes! If you lose your device and don\'t have the recovery codes you will lose access to your account forever!')</strong></p>

                                    <x-forms.patch :action="route('frontend.auth.account.2fa.update')" name="confirm-item">
                                        <button class="btn btn-sm btn-block btn-danger mb-3" type="submit">@lang('Generate New Backup Codes')</button>
                                    </x-forms.patch>

                                    <p><strong>@lang('Each code can only be used once!')</strong></p>

                                    <div class="row">
                                        @foreach (collect($recoveryCodes)->chunk(5) as $codes)
                                            <div class="col-6">
                                                <ul>
                                                    @foreach ($codes as $code)
                                                        <li>
                                                            {{ $code['code'] }} -

                                                            @if ($code['used_at'])
                                                                <strong class="text-danger">
                                                                    @lang('Used'): @displayDate(carbon($code['used_at']))
                                                                </strong>
                                                            @else
                                                                <em>@lang('Not Used')</em>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div><!--col-->
                                        @endforeach
                                    </div><!--row-->

                                    <a href="{{ route('frontend.user.account', ['#two-factor-authentication']) }}" class="btn btn-sm btn-block btn-success">@lang('I have stored these codes in a safe place')</a>
                                </x-slot>
                            </x-frontend.card>

                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
