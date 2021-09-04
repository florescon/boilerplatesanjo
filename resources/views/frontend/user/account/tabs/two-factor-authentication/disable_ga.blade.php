@extends('frontend.layouts.app_ga', ['headerDark' => 'header-dark'])

@section('title', __('Disable Two Factor Authentication'))

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
                                    <h5 class="text-md-center">@lang('Disable Two Factor Authentication')</h5>
                                </x-slot>

                                <x-slot name="body">
                                    <p class="text-md-center">@lang('Generate a code from your 2FA app and enter it below:')</p>

                                    <x-forms.delete :action="route('frontend.auth.account.2fa.destroy')" name="confirm-item">
                                        <div class="form-group row">
                                            <label for="code" class="col-md-12 col-form-label text-md-center">@lang('Authorization Code')</label>

                                            <div class="col-md-12 mb-4">
                                                <input type="text" name="code" id="code" maxlength="10" class="form-control" placeholder="{{ __('Authorization Code') }}" required />
                                            </div>
                                        </div><!--form-group-->

                                        <button class="btn btn-sm btn-block btn-danger" type="submit">@lang('Remove Two Factor Authentication')</button>
                                    </x-forms.delete>
                                </x-slot>
                            </x-frontend.card>
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
