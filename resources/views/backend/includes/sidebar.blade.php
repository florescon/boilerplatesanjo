<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none" style="background-color: white;">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <img width="70"  src="{{ asset('/img/ga/logo22.png')}}" alt="Porto Logo">
        </a>
    </div><!--c-sidebar-brand-->


    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.dashboard')"
                :active="activeClass(Route::is('admin.dashboard'), 'c-active')"
                icon="c-sidebar-nav-icon cil-speedometer"
                :text="__('Dashboard')" />
        </li>

        @if (
            $logged_in_user->hasAllAccess() ||
            (
                $logged_in_user->can('admin.access.user.list') ||
                $logged_in_user->can('admin.access.user.deactivate') ||
                $logged_in_user->can('admin.access.user.reactivate') ||
                $logged_in_user->can('admin.access.user.clear-session') ||
                $logged_in_user->can('admin.access.user.impersonate') ||
                $logged_in_user->can('admin.access.user.change-password')
            )
        )
            <li class="c-sidebar-nav-title">@lang('System')</li>

            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-user"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Access')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    @if (
                        $logged_in_user->hasAllAccess() ||
                        (
                            $logged_in_user->can('admin.access.user.list') ||
                            $logged_in_user->can('admin.access.user.deactivate') ||
                            $logged_in_user->can('admin.access.user.reactivate') ||
                            $logged_in_user->can('admin.access.user.clear-session') ||
                            $logged_in_user->can('admin.access.user.impersonate') ||
                            $logged_in_user->can('admin.access.user.change-password')
                        )
                    )
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.user.index')"
                                class="c-sidebar-nav-link"
                                :text="__('User Management')"
                                :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess())
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.role.index')"
                                class="c-sidebar-nav-link"
                                :text="__('Role Management')"
                                :active="activeClass(Route::is('admin.auth.role.*'), 'c-active')" />
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
            <li class="c-sidebar-nav-dropdown">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-list"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Logs')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::dashboard')"
                            class="c-sidebar-nav-link"
                            :text="__('Dashboard')" />
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::logs.list')"
                            class="c-sidebar-nav-link"
                            :text="__('Logs')" />
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.activity.index')"
                            class="c-sidebar-nav-link"
                            :text="__('Activity panel')"
                            :active="activeClass(Route::is('admin.activity.*'), 'c-active')" />
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.logged.index')"
                            class="c-sidebar-nav-link"
                            :text="__('Session logins')"
                            :active="activeClass(Route::is('admin.logged.*'), 'c-active')" />
                    </li>
                </ul>
            </li>
        @endif

        <li class="c-sidebar-nav-item">
            <x-utils.link
                new="true"
                class="c-sidebar-nav-link"
                :href="route('admin.departament.index')"
                :active="activeClass(Route::is('admin.departament.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-grid"
                :text="__('Departaments')" />
        </li>

        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.order.index')"
                :active="activeClass(Route::is('admin.order.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-cash"
                :text="__('Orders')" />
        </li>

        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.product.index')"
                :active="activeClass(Route::is('admin.product.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-tag"
                :text="__('Products')" />
        </li>

        <li class="c-sidebar-nav-item">
            <x-utils.link
                new="true"
                class="c-sidebar-nav-link"
                :href="route('admin.service.index')"
                :active="activeClass(Route::is('admin.service.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-task"
                :text="__('Services')" />
        </li>

        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.material.index')"
                :active="activeClass(Route::is('admin.material.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-color-fill"
                :text="__('Feedstocks')" />
        </li>

        <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.color.*')  || Route::is('admin.size.*') || Route::is('admin.cloth.*') || Route::is('admin.line.*') || Route::is('admin.unit.index') || Route::is('admin.brand.index') || Route::is('admin.model.index'), 'c-open c-show') }}">
            <x-utils.link
                new="true"
                href="#"
                icon="c-sidebar-nav-icon cil-library"
                class="c-sidebar-nav-dropdown-toggle"
                :text="__('Parameters')" />

            <ul class="c-sidebar-nav-dropdown-items">

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.color.index')"
                        :active="activeClass(Route::is('admin.color.*'), 'c-active')"
                        :text="__('Colors')" />


                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.size.index')"
                        :active="activeClass(Route::is('admin.size.*'), 'c-active')"
                        :text="__('Sizes')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.cloth.index')"
                        :active="activeClass(Route::is('admin.cloth.*'), 'c-active')"
                        :text="__('Cloths')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.line.index')"
                        :active="activeClass(Route::is('admin.line.*'), 'c-active')"
                        :text="__('Lines')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.unit.index')"
                        :active="activeClass(Route::is('admin.unit.index'), 'c-active')"
                        :text="__('Units')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        :href="route('admin.brand.index')"
                        :active="activeClass(Route::is('admin.brand.index'), 'c-active')"
                        :text="__('Brands')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        new="true"
                        class="c-sidebar-nav-link"
                        :href="route('admin.model.index')"
                        :active="activeClass(Route::is('admin.model.index'), 'c-active')"
                        :text="__('Models')" />
                </li>

            </ul>

        </li>

        <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.store.pos.*') || Route::is('admin.store.finances.*') || Route::is('admin.store.box.*'), 'c-open c-show') }}">
            <x-utils.link
                new="true"
                href="#"
                icon="c-sidebar-nav-icon fas fa-store"
                class="c-sidebar-nav-dropdown-toggle"
                :text="__('Shop')" />

            <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            new="true"
                            :href="route('admin.store.pos')"
                            class="c-sidebar-nav-link"
                            :text="__('Shop panel')"
                            :active="activeClass(Route::is('admin.store.pos.*'), 'c-active')" />
                    </li>

                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            new="true"
                            :href="route('admin.store.finances.index')"
                            class="c-sidebar-nav-link"
                            :text="__('Incomes and expenses')"
                            :active="activeClass(Route::is('admin.store.finances.*'), 'c-active')"/>
                    </li>

                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            new="true"
                            :href="route('admin.store.box.index')"
                            class="c-sidebar-nav-link"
                            :text="__('Daily cash closing')"
                            :active="activeClass(Route::is('admin.store.box.*'), 'c-active')"/>
                    </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-title">@lang('Others')</li>

        <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.setting.index.*') || Route::is('admin.auth.pages.*'), 'c-open c-show') }}">
            <x-utils.link
                href="#"
                icon="c-sidebar-nav-icon cil-settings"
                class="c-sidebar-nav-dropdown-toggle"
                :text="__('Settings')" />

            <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.setting.index')"
                            class="c-sidebar-nav-link"
                            :text="__('General Settings')"
                            :active="activeClass(Route::is('admin.setting.index.*'), 'c-active')" />
                    </li>

                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.setting.pages')"
                            class="c-sidebar-nav-link"
                            :text="__('Pages')"
                            :active="activeClass(Route::is('admin.setting.pages.*'), 'c-active')"/>
                    </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <x-utils.link
                new="true"
                class="c-sidebar-nav-link"
                :href="route('admin.document.index')"
                :active="activeClass(Route::is('admin.document.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-file"
                :text="__('Documents')" />
        </li>

        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.status.index')"
                :active="activeClass(Route::is('admin.status.*'), 'c-active')"
                icon="c-sidebar-nav-icon cil-brightness"
                :text="__('Order states')" />
        </li>

    </ul>

    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div><!--sidebar-->
