<header class="c-header c-header-light c-header-fixed">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <i class="c-icon c-icon-lg cil-menu"></i>
    </button>

    <a class="c-header-brand d-lg-none" href="{{ route('admin.dashboard') }}">
        <img width="60" src="{{ asset('/img/ga/logo22.png')}}" alt="Porto Logo">
    </a>

    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
        <i class="c-icon c-icon-lg cil-menu"></i>
    </button>

    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{ route('frontend.index') }}">@lang('Starter store')</a></li>


        <li class="c-header-nav-item dropdown">
            <x-utils.link
                :text="__('Orders/Sales')"
                class="c-header-nav-link dropdown-toggle"
                id="navbarDropdownLanguageLink2"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false" />

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLanguageLink2">
                      <x-utils.link class="dropdown-item pt-1 pb-1 text-dark" :href="route('admin.order.all')" :text="__('all')" />
                      <x-utils.link class="dropdown-item pt-1 pb-1 text-primary" :href="route('admin.order.index')" :text="__('Orders')" />
                      <x-utils.link class="dropdown-item pt-1 pb-1 text-success" :href="route('admin.order.sales')" :text="__('Sales')" />
                      <x-utils.link class="dropdown-item pt-1 pb-1 text-warning" :href="route('admin.order.mix')" :text="__('Mix')" />
                      <x-utils.link class="dropdown-item pt-1 pb-1 text-info" :href="route('admin.order.suborders')" :text="__('Suborders')" />
                </div><!--dropdown-menu-->
        </li>

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

        @livewire('backend.header.header-cart')

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
