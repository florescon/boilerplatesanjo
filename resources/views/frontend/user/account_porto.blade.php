@extends('frontend.layouts.app_porto')

@section('title', __('My Account'))

@section('content')
    <div class="container py-4">
        <div class="product-single-collapse" id="productAccordion">
            <div class="product-collapse-panel">
                <h3 class="product-collapse-title">
                    <a data-toggle="collapse" href="#product-collapse-desc" role="button" aria-expanded="true" aria-controls="product-collapse-desc">@lang('My Profile')</a>
                </h3>

                <div class="product-collapse-body collapse show" id="product-collapse-desc" data-parent="#productAccordion">
                    <div class="collapse-body-wrapper">
                        <div class="product-desc-content">
                        
                    @include('frontend.user.account.tabs.profile')

                        </div><!-- End .product-desc-content -->
                    </div><!-- End .collapse-body-wrapper -->
                </div><!-- End .product-collapse-body -->
            </div><!-- End .product-collapse-panel -->

            <div class="product-collapse-panel">
                <h3 class="product-collapse-title">
                    <a class="collapsed" data-toggle="collapse" href="#product-collapse-tags" role="button" aria-expanded="false" aria-controls="product-collapse-tags">@lang('Edit Information')</a>
                </h3>

                <div class="product-collapse-body collapse" id="product-collapse-tags" data-parent="#productAccordion">
                    <div class="collapse-body-wrapper">
                        <div class="product-tags-content">
                            @include('frontend.user.account.tabs.information')
                        </div><!-- End .product-tags-content -->
                    </div><!-- End .collapse-body-wrapper -->
                </div><!-- End .product-collapse-body -->
            </div><!-- End .product-collapse-panel -->

            @if (! $logged_in_user->isSocial())
            <div class="product-collapse-panel">
                <h3 class="product-collapse-title">
                    <a class="collapsed" data-toggle="collapse" href="#product-collapse-reviews" role="button" aria-expanded="false" aria-controls="product-collapse-reviews">@lang('Password')</a>
                </h3>

                <div class="product-collapse-body collapse" id="product-collapse-reviews" data-parent="#productAccordion">
                    <div class="collapse-body-wrapper">
                        <div class="product-reviews-content">
                            @include('frontend.user.account.tabs.password')
                        </div><!-- End .product-reviews-content -->
                    </div><!-- End .collapse-body-wrapper -->
                </div><!-- End .product-collapse-body -->
            </div><!-- End .product-collapse-panel -->
            @endif

            <div class="product-collapse-panel">
                <h3 class="product-collapse-title">
                    <a class="collapsed" data-toggle="collapse" href="#product-collapse-2fa" role="button" aria-expanded="false" aria-controls="product-collapse-2fa">@lang('Two Factor Authentication')</a>
                </h3>

                <div class="product-collapse-body collapse" id="product-collapse-2fa" data-parent="#productAccordion">
                    <div class="collapse-body-wrapper">
                        <div class="product-reviews-content">
                            @include('frontend.user.account.tabs.two-factor-authentication')
                        </div><!-- End .product-reviews-content -->
                    </div><!-- End .collapse-body-wrapper -->
                </div><!-- End .product-collapse-body -->
            </div><!-- End .product-collapse-panel -->

        </div><!-- End .product-single-collapse -->
    </div><!--container-->
@endsection
