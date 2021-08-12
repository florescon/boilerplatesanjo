           <div class="padding-on-scroll">
                <div class="section-1400">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <nav class="navbar navbar-expand-xl navbar-light">

                                    <a class="navbar-brand animsition-link" href="{{ url('/') }}"><img src="{{ asset('/img/logo22.png') }}"
                                            alt="logo">
                                    </a>

                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>

                                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                        <ul class="navbar-nav mr-xl-4 ml-auto pt-4 pt-xl-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" role="button" aria-haspopup="true" aria-expanded="false" href="{{ url('/') }}">@lang('Home')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" role="button" aria-haspopup="true" aria-expanded="false" href="{{ url('/') }}">@lang('Order track')</a>
                                            </li>
                                            @auth
                                            @if ($logged_in_user->isAdmin())
                                            <li class="nav-item">
                                                <a class="nav-link" role="button" aria-haspopup="true" aria-expanded="false" href="{{ route('admin.dashboard') }}">
                                                    <span class="link link-purple" data-hover="{{ __('Administration') }}">@lang('Administration')</span>
                                                </a>
                                            </li>
                                            @endif
                                            <li class="nav-item">
                                                <a class="nav-link" style="color:red" href="#" role="button" aria-haspopup="true" aria-expanded="false" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                    @lang('Logout')
                                                    <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                                                </a>  
                                            </li>
                                            @endauth
                                            <li class="nav-item">
                                                <a href="{{ Auth::check() ? route('frontend.user.account') : route('frontend.auth.login') }}" type="button" class="btn btn-light btn-44 mr-1 mb-1"
                                                    @if(Auth::check())
                                                       data-toggle="tooltip" data-html="true" data-placement="bottom" title="
                                                       <div class='pt-4'>
                                                        {{ Auth::user()->name }}
                                                       </div
                                                       "
                                                    @endif
                                                >
                                                <i class="uil uil-user-circle size-20 "></i>
                                                </a>               
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('frontend.shop.index') }}" class="btn btn-dark-primary mt-4 mt-xl-0">@lang('Shop')</a>
                                            </li>

                                            @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
                                                <li class="nav-item dropdown">
                                                    <x-utils.link
                                                        :text="__(getLocaleName(app()->getLocale()))"
                                                        class="nav-link dropdown-2"
                                                        id="navbarDropdownLanguageLink"
                                                        data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false" />

                                                    @include('includes.partials.lang')
                                                </li>
                                            @endif


                                        </ul>

                                        <a href="#"
                                            class="btn btn-icon-transparent btn-44 mt-4 mt-xl-0">
                                            <i class="uil uil-heart size-20"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-icon-transparent btn-44 mt-4 mt-xl-0 position-relative" data-toggle="modal" data-target="#modalCart">
                                            <i class="uil uil-cart size-20"></i>
                                            @livewire('frontend.header.header-cart-porto')
                                        </a>
                                        <a href="#"
                                            class="btn btn-icon-transparent btn-44 mt-4 mt-xl-0" data-toggle="modal" data-target="#modalSearch">
                                            <i class="uil uil-search size-20"></i>
                                        </a>
                                        <div class="pb-3 pb-xl-0"></div>
                                    </div>

                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 