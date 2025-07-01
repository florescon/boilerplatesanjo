<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" style="background: linear-gradient(to top, #000, #052c65);" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none" style="background-color: white;">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <img width="30"  src="{{ asset('/img/logo.png')}}" alt="">
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
                                :text="__('Users')"
                                :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                        </li>

                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.user.index_customer')"
                                class="c-sidebar-nav-link"
                                :text="__('Customers')"
                                :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess())
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.role.index')"
                                class="c-sidebar-nav-link"
                                :text="__('Roles')"
                                :active="activeClass(Route::is('admin.auth.role.*'), 'c-active')" />
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() && $logged_in_user->isMasterAdmin())
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
        
        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.departament.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.departament.index')"
                    :active="activeClass(Route::is('admin.departament.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-grid"
                    :text="__('Departaments')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.order.quotations_chart'))

            <li class="c-sidebar-nav-item">
                <x-utils.link
                    :href="route('admin.order.quotations_chart')"
                    class="c-sidebar-nav-link"
                    :text="__('Quotations')"
                    icon="c-sidebar-nav-icon cil-description"
                    :active="activeClass(Route::is('admin.order.quotations_chart'), 'c-active')" />
            </li>

        @endif

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.order.request_chart'))

            <li class="c-sidebar-nav-item">
                <x-utils.link
                    :href="route('admin.order.request_chart_work')"
                    class="c-sidebar-nav-link"
                    new
                    :text="__('Requests')"
                    icon="c-sidebar-nav-icon cil-briefcase"
                    :active="activeClass(Route::is('admin.order.request_chart_work'), 'c-active')" />
            </li>

        @endif


        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.order.request_chart'))

            <li class="c-sidebar-nav-item">
                <x-utils.link
                    :href="route('admin.order.request_chart')"
                    class="c-sidebar-nav-link"
                    :text="__('Requests')"
                    old
                    icon="c-sidebar-nav-icon cil-briefcase"
                    :active="activeClass(Route::is('admin.order.request_chart'), 'c-active')" />
            </li>

        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.product.modify')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.product.index')"
                    :active="activeClass(Route::is('admin.product.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-tag"
                    :text="__('Products')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.station.index') || $logged_in_user->can('admin.station.index_production'))

            <li class="c-sidebar-nav-item">
                <x-utils.link
                    :href="route('admin.station.index_production')"
                    class="c-sidebar-nav-link"
                    :text="__('Workstations')"
                    new
                    icon="c-sidebar-nav-icon cil-compress"
                    :active="activeClass(Route::is('admin.station.index_production', 'admin.station.deleted_production'), 'c-active')" />
            </li>

            <li class="c-sidebar-nav-item">
                <x-utils.link
                    :href="route('admin.station.index')"
                    class="c-sidebar-nav-link"
                    :text="__('Workstations')"
                    old
                    icon="c-sidebar-nav-icon cil-compress"
                    :active="activeClass(Route::is('admin.station.index', 'admin.station.deleted'), 'c-active')" />
            </li>

        @endif

        {{-- @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.information.view')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.information.index')"
                    :active="activeClass(Route::is('admin.information.index'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-newspaper"
                    :text="__('Information')" />
            </li>
        @endif --}}

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.information.view')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.information.chart')"
                    :active="activeClass(Route::is('admin.information.chart'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-chart"
                    :text="__('Charts')" />
            </li>
        @endif


        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.service.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.service.index')"
                    :active="activeClass(Route::is('admin.service.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-task"
                    :text="__('Services')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.material.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.material.index')"
                    :active="activeClass(Route::is('admin.material.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-color-fill"
                    :text="__('Feedstocks')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.bom.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.bom.index')"
                    :active="activeClass(Route::is('admin.bom.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-blur-circular"
                    :text="__('Bom of Materials')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.report.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.report.index')"
                    :active="activeClass(Route::is('admin.report.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-blur-circular"
                    :text="__('Reports')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() 
                || $logged_in_user->can('admin.dashboard_old') 
                || $logged_in_user->can('admin.access.order.order')
            )

            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.dashboard_old') || Route::is('admin.order.index') || Route::is('admin.order.quotation') || Route::is('admin.batch.*') || Route::is('admin.batch.conformed') || Route::is('admin.ticket.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    old
                    icon="c-sidebar-nav-icon cil-speedometer"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Dashboard old')" />

                <ul class="c-sidebar-nav-dropdown-items">

                        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.order.order')))

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    class="c-sidebar-nav-link"
                                    :href="route('admin.dashboard_old')"
                                    :active="activeClass(Route::is('admin.dashboard_old'), 'c-active')"
                                    :text="__('Dashboard old')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    class="c-sidebar-nav-link"
                                    :href="route('admin.order.index')"
                                    :active="activeClass(Route::is('admin.order.index'), 'c-active')"
                                    :text="__('Orders')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    class="c-sidebar-nav-link"
                                    :href="route('admin.order.quotation')"
                                    :active="activeClass(Route::is('admin.order.quotation'), 'c-active')"
                                    :text="__('Quotation')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.batch.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Cutting')"
                                    :active="activeClass(Route::is('admin.batch'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.batch.manufacturing')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Manufacturing')"
                                    :active="activeClass(Route::is('admin.batch.manufacturing'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    class="c-sidebar-nav-link"
                                    :href="route('admin.ticket.index')"
                                    :active="activeClass(Route::is('admin.ticket.*'), 'c-active')"
                                    :text="__('Tickets')" />
                            </li>

                        @endif

                </ul>

            </li>

        @endif


        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.color.list') || $logged_in_user->can('admin.access.size.list') || $logged_in_user->can('admin.access.cloth.list') || $logged_in_user->can('admin.access.line.list') || $logged_in_user->can('admin.access.unit.list') || $logged_in_user->can('admin.access.brand.list') || $logged_in_user->can('admin.access.family.list') || $logged_in_user->can('admin.access.model_product.list') ))
            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.color.*')  || Route::is('admin.size.*') || Route::is('admin.cloth.*') || Route::is('admin.line.*') || Route::is('admin.unit.*') || Route::is('admin.brand.*') || Route::is('admin.image.*') ||  Route::is('admin.servicetype.*') || Route::is('admin.family.*') ||  Route::is('admin.model.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-library"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Parameters')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.color.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.color.index')"
                                :active="activeClass(Route::is('admin.color.*'), 'c-active')"
                                :text="__('Colors')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.size.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.size.index')"
                                :active="activeClass(Route::is('admin.size.*'), 'c-active')"
                                :text="__('Sizes')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.cloth.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.cloth.index')"
                                :active="activeClass(Route::is('admin.cloth.*'), 'c-active')"
                                :text="__('Cloths')" />
                        </li>
                    @endif

                    {{-- @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.thread.index')"
                                :active="activeClass(Route::is('admin.thread.*'), 'c-active')"
                                :text="__('Threads')" />
                        </li>
                    @endif --}}

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.line.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.line.index')"
                                :active="activeClass(Route::is('admin.line.*'), 'c-active')"
                                :text="__('Lines')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.unit.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.unit.index')"
                                :active="activeClass(Route::is('admin.unit.*'), 'c-active')"
                                :text="__('Units')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.brand.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.brand.index')"
                                :active="activeClass(Route::is('admin.brand.*'), 'c-active')"
                                :text="__('Brands')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.family.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.family.index')"
                                :active="activeClass(Route::is('admin.family.*'), 'c-active')"
                                :text="__('Families')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.model_product.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.model.index')"
                                :active="activeClass(Route::is('admin.model.*'), 'c-active')"
                                :text="__('Models')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.vendor.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.vendor.index')"
                                :active="activeClass(Route::is('admin.vendor.*'), 'c-active')"
                                :text="__('Vendors')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.servicetype.list')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.servicetype.index')"
                                :active="activeClass(Route::is('admin.servicetype.*'), 'c-active')"
                                :text="__('Service Type')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.index')))
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                class="c-sidebar-nav-link"
                                :href="route('admin.image.index')"
                                :active="activeClass(Route::is('admin.image.*'), 'c-active')"
                                :text="__('Services Images')" />
                        </li>
                    @endif
                </ul>

            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.list') || $logged_in_user->can('admin.access.store.list_finance') || $logged_in_user->can('admin.access.store.create_finance')))
            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.store.product.index') || Route::is('admin.store.quotation') || Route::is('admin.store.dashboard')  || Route::is('admin.store.request') || Route::is('admin.store.order') || Route::is('admin.store.sale') || Route::is('admin.store.all.*') || Route::is('admin.serviceorder.index') || Route::is('admin.store.finances.*') || Route::is('admin.store.box.*') || Route::is('admin.store.report.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon fas fa-store"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Shop')" />

                <ul class="c-sidebar-nav-dropdown-items">

                        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.list')))

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.dashboard')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Dashboard')"
                                    :active="activeClass(Route::is('admin.store.dashboard.*'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.product.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Products')"
                                    :active="activeClass(Route::is('admin.store.product.*'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.quotation')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Quotation')"
                                    :active="activeClass(Route::is('admin.store.quotation.*'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.request')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Request')"
                                    :active="activeClass(Route::is('admin.store.request.*'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.sale')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Sale')"
                                    :active="activeClass(Route::is('admin.store.sale.*'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.output_products')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Output products')"
                                    :active="activeClass(Route::is('admin.store.output_products.*'), 'c-active')" />
                            </li>
                            
                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.all.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Requests/Sales')"
                                    :active="activeClass(Route::is('admin.store.all.*'), 'c-active')" />
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.serviceorder.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Service Order')"
                                    :active="activeClass(Route::is('admin.serviceorder.index'), 'c-active')" />
                            </li>

                        @endif

                        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.list_finance')))
                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.finances.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Incomes and expenses')"
                                    :active="activeClass(Route::is('admin.store.finances.*'), 'c-active')"/>
                            </li>
                        @endif

                        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.create_finance')))
                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.box.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Daily cash closing')"
                                    :active="activeClass(Route::is('admin.store.box.*'), 'c-active')"/>
                            </li>
                        @endif

                        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.store.create_finance')))
                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.store.report.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Reports')"
                                    :active="activeClass(Route::is('admin.store.report.*'), 'c-active')"/>
                            </li>
                        @endif

                </ul>
            </li>
        @endif

        <li class="c-sidebar-nav-title">@lang('Others')</li>

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.settings.list') || $logged_in_user->can('admin.access.settings.list_pages')))
            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.setting.index') || Route::is('admin.auth.pages'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-settings"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Settings')" />

                <ul class="c-sidebar-nav-dropdown-items">

                        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.settings.list')))
                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.setting.index')"
                                    class="c-sidebar-nav-link"
                                    :text="__('General Settings')"
                                    :active="activeClass(Route::is('admin.setting.index'), 'c-active')" />
                            </li>
                        @endif

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.setting.banner')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Banner images')"
                                    :active="activeClass(Route::is('admin.setting.banner'), 'c-active')"/>
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.setting.logos')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Brands images')"
                                    :active="activeClass(Route::is('admin.setting.logos'), 'c-active')"/>
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.setting.images_ai')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Product images')"
                                    :active="activeClass(Route::is('admin.setting.images_ai'), 'c-active')"/>
                            </li>

                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.setting.gallery')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Gallery')"
                                    :active="activeClass(Route::is('admin.setting.gallery'), 'c-active')"/>
                            </li>

                        {{-- @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.settings.list_pages')))
                            <li class="c-sidebar-nav-item">
                                <x-utils.link
                                    :href="route('admin.setting.pages')"
                                    class="c-sidebar-nav-link"
                                    :text="__('Pages')"
                                    :active="activeClass(Route::is('admin.setting.pages'), 'c-active')"/>
                            </li>
                        @endif --}}

                </ul>
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.inventories.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.inventory.index')"
                    :active="activeClass(Route::is('admin.inventory.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-grid"
                    :text="__('Make inventory')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.document.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.document.index')"
                    :active="activeClass(Route::is('admin.document.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-file"
                    :text="__('Documents_')" />
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.states_production.list')))
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.status.index')"
                    :active="activeClass(Route::is('admin.status.*'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-brightness"
                    :text="__('Order states')" />
            </li>
        @endif

    </ul>

    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div><!--sidebar-->
