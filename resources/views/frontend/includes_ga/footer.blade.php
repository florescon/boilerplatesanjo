        <div class="section over-hide  padding-top padding-bottom bg-transparent section-background-2" id="footer">
            <div class="section-1400">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-5 text-center text-sm-left">
                            <div class="section border-4 padding-top-bottom-80 px-4 px-md-5 bg-dark">
                                <h5 class="mb-4 color-white">@lang('Contact')</h5>
                                <a href="#" class="link size-18 link-gray mb-1" data-hover="{{ setting('site_address') }}">
                                    {{ setting('site_address') }}
                                </a> 
                                <div class="clearfix w-100"></div>
                                <a href="#" class="link size-18 link-success mb-1" data-hover="{{ setting('site_phone') }}">{{ setting('site_phone') }}</a> 
                                <div class="clearfix w-100"></div>
                                <a href="#" class="link size-18 link-primary mb-fix-22 pb-1" data-hover="{{ setting('site_email') }}">{{ setting('site_email') }}</a>
                            </div>
                        </div>
                        <div class="col-xl-7 mt-4 mt-xl-0 text-center text-sm-left">
                            <div class="section border-4 padding-top-bottom-80 px-4 px-md-5 bg-dark-blue">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="#" class="link size-18 link-gray mb-1 animsition-link" data-hover="{{ __('About us') }}">@lang('About us')</a> 
                                        <div class="clearfix w-100"></div>
                                        <a href="#" class="link size-18 link-gray mb-1 animsition-link" data-hover="{{ __('Order track') }}">@lang('Order track')</a> 
                                        <div class="clearfix w-100"></div>
                                        <a href="#" class="link size-18 link-gray mb-1 animsition-link" data-hover="{{ __('Contact') }}">@lang('Contact')</a> 
                                        <div class="clearfix w-100"></div> 
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <a href="{{ route('frontend.shop.index') }}" class="link size-18 link-gray mb-1" data-hover="{{ __('Shop') }}">@lang('Shop')</a> 
                                        <div class="clearfix w-100"></div>
                                    </div>
                                    <div class="col-lg-5 mt-5 mt-lg-0">
                                        <h5 class="mb-4 color-white">@lang('Join us')</h5>
                                        <div class="row">
                                            <div class="col pr-0">
                                                <div class="form-group just-line-light">
                                                    <input type="email" class="form-style" placeholder="{{ __('Your email') }}" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-primary btn-44"><i class="uil uil-message size-20"></i></button>
                                            </div>
                                        </div>
                                        <p class="mb-0 size-13 color-secondary mt-1 mb-3 text-left">* {{ __('we won\'t spam you') }}.</p>
                                        <a href="{{ setting('site_facebook') }}" class="link link-primary mb-1 mr-2" data-hover="Facebook" target="_blank">Facebook</a> 
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section over-hide padding-top bg-light py-4">
            <div class="section-1400">
                <div class="container-fluid">
                    <div class="row text-center text-md-left">
                        <div class="col-md-auto">
                            {{-- <a href="sitemap.html" class="link link-normal text-center-v animsition-link">Sitemap <i class="uil uil-arrow-right size-20 ml-1"></i></a>  --}}
                        </div>
                        <div class="col-md order-md-first">
                            <p class="mb-0 size-14 mt-1 font-weight-500 text-dark">© {{ date('Y') }} {{ __(appName()) }}. @lang('All Rights Reserved').</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
            </svg>
        </div>
