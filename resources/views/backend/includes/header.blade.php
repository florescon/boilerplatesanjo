<header class="c-header c-header-light c-header-fixed">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <i class="c-icon c-icon-lg cil-menu"></i>
    </button>

    {{-- <a class="c-header-brand d-lg-none" href="{{ route('admin.dashboard') }}">
        <img width="30" src="{{ asset('/img/logo.png')}}" alt="">
    </a> --}}

    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
        <i class="c-icon c-icon-lg cil-menu"></i>
    </button>

    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('admin.dashboard') }}">@lang('Dashboard')</a></li>

        @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
            <li class="c-header-nav-item dropdown">
                <x-utils.link
                    :text="__(getLocaleName(app()->getLocale()))"
                    class="c-header-nav-link dropdown-toggle"
                    id="navbarDropdownLanguageLink"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false" />

                @include('includes.partials.lang')
            </li>
        @endif
    </ul>

    <ul class="c-header-nav ml-auto mr-4">

        <a href="{{ route('frontend.index') }}">
           <img src="{{ asset('img/bacapro.png') }}" width="80" alt="Logo">
        </a>

        <li class="c-header-nav-item px-3">
            <a class="c-header-nav-link" href="{{ route('admin.documentation.index') }}" target="_blank">
                Docs
            </a>
        </li>
        <li class="c-header-nav-item px-3">
            <a class="header-nav-link" href="javascript:window.print()">
              <i class="cil-print"></i>
              <span>@lang('Print')</span>
            </a>
        </li>
        
        {{-- @livewire('backend.header.header-cart') --}}

        <li class="c-header-nav-item dropdown">
            <x-utils.link class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <x-slot name="text">
                    <div class="c-avatar">
                        <img class="c-avatar-img" src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->email ?? '' }}">
                    </div>
                </x-slot>
            </x-utils.link>

            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-light py-2">
                    <strong>@lang('Account')</strong>
                </div>

                <x-utils.link
                    class="dropdown-item"
                    icon="c-icon mr-2 cil-account-logout"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <x-slot name="text">
                        @lang('Logout')
                        <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                    </x-slot>
                </x-utils.link>
            </div>
        </li>
    </ul>

    <div class="c-subheader justify-content-between px-3 shadow">
        @include('backend.includes.partials.breadcrumbs')

        <div class="c-subheader-nav mfe-2">
            @yield('breadcrumb-links')
        </div>
    </div><!--c-subheader-->
</header>
