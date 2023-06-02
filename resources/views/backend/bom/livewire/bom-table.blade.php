<div class="row">
    <div class="col-md-8 no-print">
        <div class="chat-module-top">
            <form>
                <div class="input-group input-group-round mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="cil-search mr-1"></i>
                        </span>
                    </div>
                    <input type="search" wire:model.debounce.350ms="searchTerm" class="form-control filter-list-input"
                        placeholder="{{ __('Search order or quotation') }}" aria-label="Search order or quotation">
                </div>
            </form>

            @if (
                $selectedtypes &&
                    $orders->count() &&
                    ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.bom.list')))
                <div class="dropdown table-export mb-4">

                    <button type="button" wire:click="sendMaterials" onmousedown="party.confetti(this)"
                        class="buttonn btn btn-light mr-2"> @lang('Explode') </button>

                    @error('selectedtypes')
                        <span class="error" style="color: red;">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror

                    {{-- <strong>@lang('Selected'):</strong>

                    @foreach ($selectedtypes as $link)
                        <span class="badge badge-dark mt-2" style="font-size: 1rem;">#{{ $link }}</span>
                    @endforeach --}}

                    <div wire:loading wire:target="exportMaatwebsite" class="text-nowrap ml-3">
                        <strong>@lang('Processing')...</strong>
                    </div>

                </div>
                <!--export-dropdown-->
            @endif

            <div class="chat-module-body">
                @foreach ($orders as $order)
                    <div class="media chat-item">
                        <div class="media-body">
                            <form class="checklist">
                                <div class="row">
                                    <div class="form-group col">
                                        <span class="checklist-reorder">
                                            <i class="cil-aperture"></i>
                                        </span>
                                        <div class="custom-control custom-checkbox col">
                                            <input type="checkbox" class="custom-control-input"
                                                value="{{ $order->id }}" wire:model="selectedtypes"
                                                id="checklist-item-{{ $order->id }}">
                                            <label class="custom-control-label"
                                                for="checklist-item-{{ $order->id }}"></label>
                                            <div>
                                                <a href="{{ route('admin.order.edit', $order->id) }}"
                                                    target="_blank"><strong>{{ typeOrderCharacters($order->type) }}{{ $order->folio }}</strong></a>
                                                <input type="text" placeholder="{{ __('undefined customer') }}"
                                                    value="{{ Str::limit($order->customer, 50) }}"
                                                    data-filter-by="value" />
                                                {{ $order->comment }}
                                                <div class="checklist-strikethrough"></div>
                                            </div>
                                        </div>
                                        <span data-filter-by="text">
                                            <span class="badge"
                                                style="background-color: {{ typeOrderColor($order->type) }}">{{ __(typeOutOrder($order->type)) }}</span>
                                            <span class="badge badge-secondary">{{ $order->name_status }}</span>
                                            <br>
                                            {{ $order->created_at }}
                                        </span>
                                    </div>
                                    <!--end of form group-->
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                @if ($orders->hasMorePages())
                    <br>
                    <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .8;">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary"
                                wire:click="$emit('load-more')">@lang('Load more')</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sidebar-content">
            <div class="chat-team-sidebar text-small">
                <div class="chat-team-sidebar-top">
                    <div class="media align-items-center">
                        <a href="#" class="mr-2">
                            <img alt="SJ" src="{{ asset('/img/sj.jpg') }}" class="avatar avatar-lg" />
                        </a>
                        <div class="media-body">
                            <h5 class="mb-1">@lang('Bill of Materials')</h5>
                            <p>@lang('A collection of materials for explode orders')</p>
                        </div>
                    </div>
                    <div class="loading mt-2" wire:loading wire:target="sendMaterials">
                        @lang('Processing')
                    </div>

                    <div class=" align-items-center mt-3">
                        @if ($orderCollection)
                            @lang('Orders'):
                            @foreach ($orderCollection as $order)
                                <span class="badge badge-warning ml-1 mr-1 mt-1"
                                    style="font-size: 1rem;">{{ $order['type'] }}{{ $order['folio'] ?? '' }}</span>
                            @endforeach
                        @else
                            <p>@lang('Nothing processed')</p>
                        @endif
                    </div>

                    @if ($orderCollection)
                        <ul class="nav nav-tabs nav-justified no-print" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'members' ? 'active' : '' }}"
                                    wire:click="$set('tab', 'members')" id="members-tab" data-toggle="tab"
                                    href="#members" role="tab" aria-controls="members"
                                    aria-selected="true">@lang('Feedstocks')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'products' ? 'active' : '' }}"
                                    wire:click="$set('tab', 'products')" id="products-tab" data-toggle="tab"
                                    href="#products" role="tab" aria-controls="products"
                                    aria-selected="true">@lang('Products')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'files' ? 'active' : '' }}"
                                    wire:click="$set('tab', 'files')" id="files-tab" data-toggle="tab" href="#files"
                                    role="tab" aria-controls="files" aria-selected="false">@lang('Export')</a>
                            </li>
                        </ul>
                    @endif
                </div>
                @if ($orderCollection)
                    <div class="chat-team-sidebar-bottom">
                        <div class="tab-content">
                            <div class="tab-pane fade show {{ $tab == 'members' ? 'show active' : '' }}"
                                id="members" role="tabpanel" data-filter-list="list-group">
                                <form class="px-3 mb-3 no-print">
                                    <div class="input-group input-group-round">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="cil-filter mr-1"></i>
                                            </span>
                                        </div>
                                        <input type="search" wire:model.debounce.350ms="searchFeedstock"
                                            class="form-control filter-list-input" placeholder="@lang('Filter feedstock')"
                                            aria-label="Filter feedstock">
                                    </div>
                                </form>
                                <div class="list-group list-group-flush">

                                    @if ($materialsCollection)
                                        <a class="list-group-item text-center">
                                            {{ count($materialsCollection) . ' ' . __('Records') }}
                                        </a>
                                        @foreach ($materialsCollection as $material)
                                            <a class="list-group-item list-group-item-action" href="#">
                                                <div class="media media-member mb-0">
                                                    <div class="media-body">
                                                        <h6 class="mb-0" data-filter-by="text">
                                                            {!! $material['material_name'] !!}</h6>
                                                        <span data-filter-by="text">
                                                            {!! $material['part_number'] !!}

                                                            {!! $material['vendor'] ? '<span class="badge badge-primary"> ' . $material['vendor'] . '</span>' : '' !!}

                                                            {!! $material['family'] ? '<span class="badge badge-dark"> ' . $material['family'] . '</span>' : '' !!}
                                                        </span>
                                                    </div>
                                                    {!! ' <strong>' . $material['quantity'] . '</strong>&nbsp;' . $material['unit_measurement'] !!}
                                                </div>
                                            </a>
                                        @endforeach
                                    @endif

                                </div>
                            </div>



                            <div class="tab-pane fade show {{ $tab == 'products' ? 'show active' : '' }}"
                                id="products" role="tabpanel" data-filter-list="list-group">
                                {{-- <form class="px-3 mb-3">
                <div class="input-group input-group-round">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="cil-filter mr-1"></i>
                    </span>
                  </div>
                  <input type="search" wire:model.debounce.350ms="searchProduct" class="form-control filter-list-input" placeholder="@lang('Filter product')" aria-label="Filter product">
                </div>
              </form> --}}

                                <div class="px-3 mb-3 no-print">

                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title">@lang('Export products')</h5>

                                            <div class="btn-group m-2" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    @lang('Detailed')
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a class="dropdown-item"
                                                        wire:click="exportProductsCustom('xlsx', 'detailed')"
                                                        href="#">Excel</a>
                                                    <a class="dropdown-item"
                                                        wire:click="exportProductsCustom('csv', 'detailed')"
                                                        href="#">CSV</a>
                                                    <a class="dropdown-item"
                                                        wire:click="exportProductsCustom('html', 'detailed')"
                                                        href="#">HTML</a>
                                                </div>
                                            </div>

                                            <a href="javascript:window.print()" type="button"
                                                class="btn btn-primary">@lang('Grouped') - @lang('Direct') <i
                                                    class="cil-print"></i> </a>

                                        </div>
                                    </div>

                                </div>

                                <div class="list-group list-group-flush">

                                    @if ($productsCollectionGrouped)

                                        @foreach ($productsCollectionGrouped as $product)
                                            @foreach ($product as $key => $p)
                                                @php
                                                    $sum = 0;
                                                @endphp

                                                @foreach ($p as $pp)
                                                    @php($sum += $pp['productQuantity'])
                                                @endforeach

                                                <a class="list-group-item list-group-item-action" href="#">
                                                    <div class="media media-member mb-0">
                                                        <div class="media-body">
                                                            <h6 class="mb-0" data-filter-by="text">
                                                                {!! $p[0]['productParentName'] . ' - ' . $p[0]['productColorName'] !!}</h6>
                                                            <span data-filter-by="text">{!! $p[0]['productParentCode'] !!}</span>
                                                        </div>
                                                        {!! '&nbsp;<strong>' . $sum . '</strong>' !!}
                                                    </div>
                                                </a>

                                                {{-- @json($p) --}}
                                            @endforeach
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                            <div class="tab-pane fade {{ $tab == 'files' ? 'show active' : '' }}" id="files"
                                role="tabpanel" data-filter-list="dropzone-previews">

                                <ul class="list-group list-group-activity dropzone-previews flex-column-reverse list-group-flush"
                                    style="margin-bottom: 100px;">

                                    <li class="list-group-item ">
                                        <div class="media align-items-center">
                                            <ul class="avatars">
                                                <li>
                                                    <div class="avatar bg-primary">
                                                        <i class="cil-file"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="media-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <a href="#"
                                                        wire:click="exportMaatwebsiteCustom('xlsx', 'family')"
                                                        data-filter-by="text">Exportar por familia</a>
                                                    <br>
                                                    <span class="text-small" data-filter-by="text">Excel</span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn-options" type="button" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="cil-list-rich"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right ">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('xlsx', 'family')">Excel</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('csv', 'family')">CSV</a>
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('html', 'family')">HTML</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item ">
                                        <div class="media align-items-center">
                                            <ul class="avatars">
                                                <li>
                                                    <div class="avatar bg-primary">
                                                        <i class="cil-file"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="media-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <a href="#" wire:click="exportMaatwebsiteCustom('xlsx')"
                                                        data-filter-by="text">Exportar por unidad de medida</a>
                                                    <br>
                                                    <span class="text-small" data-filter-by="text">Excel</span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn-options" type="button" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="cil-list-rich"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right ">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('xlsx')">Excel</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('csv')">CSV</a>
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('html')">HTML</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item ">
                                        <div class="media align-items-center">
                                            <ul class="avatars">
                                                <li>
                                                    <div class="avatar bg-primary">
                                                        <i class="cil-file"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="media-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <a href="#"
                                                        wire:click="exportMaatwebsiteCustom('xlsx', 'vendor')"
                                                        data-filter-by="text">Exportar por proveedor</a>
                                                    <br>
                                                    <span class="text-small" data-filter-by="text">Excel</span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn-options" type="button" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="cil-list-rich"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right ">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('xlsx', 'vendor')">Excel</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('csv', 'vendor')">CSV</a>
                                                        <a class="dropdown-item" href="#"
                                                            wire:click="exportMaatwebsiteCustom('html', 'vendor')">HTML</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
