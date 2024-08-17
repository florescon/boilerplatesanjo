<x-backend.card>

    <x-slot name="body">

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>¡Estás en un apartado antiguo!</strong> Ir al nuevo apartado: <a href="{{ route('admin.order.request_chart') }}"> click aquí </a>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-12">
                <ul class="nav nav-tabs nav-fill mt-1 no-print" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.order.all') }}"
                            role="tab">@lang('all')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'quotations' ? 'active' : '' }}"
                            href="{{ route('admin.order.quotations') }}" role="tab">@lang('Quotations')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == '' ? 'active' : '' }}" href="{{ route('admin.order.index') }}"
                            role="tab">@lang('Orders')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'sales' ? 'active' : '' }}"
                            href="{{ route('admin.order.sales') }}" role="tab">@lang('Sales')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'mix' ? 'active' : '' }}" href="{{ route('admin.order.mix') }}"
                            role="tab">@lang('Mix')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'suborders' ? 'active' : '' }}"
                            href="{{ route('admin.order.suborders') }}" role="tab">@lang('Outputs')</a>
                    </li>
                </ul>

                @if ($status == 'suborders')
                    <div class="page-header-subtitle text-right mt-4 mb-2">
                        <x-utils.link style="color: purple;" target="_blank" icon="c-icon cil-plus" class="card-header-action"
                            :href="route('admin.order.createsuborder')" :text="__('Create output')" />
                    </div>
                @endif

                <div class="page-header-subtitle mt-4 mb-2 no-print">
                    <em>
                        @lang('Filter by created order date range')
                    </em>
                </div>

                <div class="row input-daterange mb-3 no-print">
                    <div class="col-md-3 mb-2">
                        <x-input.date wire:model="dateInput" id="dateInput" borderClass="{{ $title['color'] }}"
                            placeholder="{{ __('From') }}" />
                    </div>

                    <div class="col-md-3 mb-2">
                        <x-input.date wire:model="dateOutput" id="dateOutput" borderClass="{{ $title['color'] }}"
                            placeholder="{{ __('To') }}" />
                    </div>
                    &nbsp;

                    <div class="col-md-5 mb-2 text-right">
                        <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-outline-{{ $title['color'] }}"
                                wire:click="clearFilterDate" class="btn btn-default">@lang('Clear date')</button>
                            <button type="button" class="btn btn-outline-{{ $title['color'] }}" wire:click="clearAll"
                                class="btn btn-default">@lang('Clear all')</button>
                        </div>
                    </div>
                    &nbsp;
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="teams" role="tabpanel"
                        data-filter-list="content-list-body">
                        <div class="row content-list-head no-print">
                            <div class="col-auto">
                                <div class="col form-inline">
                                    @lang('Per page'): &nbsp;

                                    <select wire:model="perPage" class="form-control ml-4 ">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>25</option>
                                        <option>50</option>
                                        <option>100</option>
                                    </select>

                                    @if ($status == '' || $status == 'all')
                                        <div class="ml-4 mt-2">
                                            <livewire:backend.attributes.status-change />
                                        </div>
                                        @if ($statusOrder)
                                            <button class="btn btn-danger btn-sm ml-4 pb-2 mt-2"
                                                wire:click="clearFilterStatusOrder">
                                                @lang('Clear status order')
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                <!--col-->

                            </div>
                        </div>

                        <div class="row content-list-head no-print">
                            <div class="col-auto">
                            </div>
                            <form class="col-lg-auto">
                                <div class="input-group input-group-round">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="cil-search"></i>
                                        </span>
                                    </div>
                                    <input type="search" wire:model.debounce.350ms="searchTerm"
                                        class="form-control filter-list-input"
                                        placeholder="{{ __('Search by folio, tracking number or comment') }}"
                                        aria-label="{{ __('Search by folio, tracking number or comment') }}">
                                </div>
                            </form>
                        </div>

                        @error('selectedtypes')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                        <!--end of content list head-->
                        <div class="content-list-body row">
                            <div class="col">
                                <div class="card-list">
                                    <div class="card-list-head">
                                        <h6 class="ml-4 mb-4">@lang($title['title']) {{ $nameStatus ?? '' }}
                                            {{ ' — ' . now()->isoFormat('D, MMM, YY - h:mm a') }}</h6>

                                        @if ($selectedtypes)
                                            <div class="button no-print">
                                                <a class="dropdown-item text-danger" wire:click="productGrouped"
                                                    href="#">@lang('Export') PDF</a>
                                            </div>
                                        @endif
                                    </div>
                                    @foreach ($orders as $order)
                                        <div class="card card-task">

                                            <div class="form-check ml-1">
                                                <input type="checkbox" value="{{ $order->id }}"
                                                    wire:model="selectedtypes" class="form-check-input"
                                                    id="exampleCheck{{ $order->id }}">
                                            </div>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $order->last_status_order_percentage }}%; background-color: {{ $order->last_status_order_color }};"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="card-body ml-3">
                                                <div class="card-title">
                                                    <a target="_blank"
                                                        href="{{ route('admin.order.edit', $order->id) }}"
                                                        style="text-decoration: none !important">
                                                        <h6 data-filter-by="text" style="display: inline;">
                                                            <strong>#{!! $order->folio_or_id !!}</strong>
                                                            {!! $order->user_name !!} {!! Str::limit($order->info_customer, 100) ?? '' !!} </h6>
                                                    </a>
                                                    @if ($order->comment)
                                                        <em class="text-small"
                                                            style="">{!! Str::limit($order->comment, 100) !!}</em>
                                                    @endif
                                                    &nbsp;
                                                        <span class="badge badge-pill badge-secondary">
                                                            {!! $order->date_for_humans !!}
                                                        </span>
                                                    <br>

                                                    {!! $order->type_order !!}

                                                    @if (!$order->isSuborder())
                                                        <strong>Totales:
                                                            {{ $order->total_products_by_all }}
                                                        </strong>
                                                    @else
                                                        <strong>Totales:
                                                            {{ $order->total_products_suborder }}
                                                        </strong>
                                                    @endif
                                                    @if ($order->purchase)
                                                        <strong>#O. DE COMPRA:</strong>
                                                        {{ $order->purchase }}
                                                    @endif
                                                    @if ($order->request)
                                                        <strong>#SOLICITUD:</strong>
                                                        {{ $order->request }}
                                                    @endif
                                                    @if ($order->invoice)
                                                        <strong>FACTURA:</strong>
                                                        {{ $order->invoice }}
                                                    @endif
                                                </div>
                                                <div class="card-meta" style="border-bottom: 1px solid;">
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-center">
                                                            {!! $order->last_status_order_id === 2 && $order->to_customer
                                                            ? __('Delivered')
                                                            : $order->last_status_order_label !!}

                                                            {!! $order->to_stock_final !!}
                                                            
                                                            {!! $order->to_customer
                                                                ? '<i class="cil-check" style="color: blue;"></i>'
                                                                : '<i class="cil-minus" style="color:red;"></i>' !!}
                                                        </span>
                                                    </div>
                                                    <div class="dropdown card-options no-print">
                                                        <button class="btn-options" type="button"
                                                            id="task-dropdown-button-1" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="cil-options"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" target="_blank"
                                                                href="{{ route('admin.order.edit', $order->id) }}">@lang('Show')</a>
                                                            <div class="dropdown-divider"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($orders->count())
                                        <div class="row">
                                            <div class="col no-print">
                                                <nav>
                                                    {{ $orders->onEachSide(1)->links() }}
                                                </nav>
                                            </div>
                                            <div class="col-sm-3 mb-2 text-muted text-right">
                                                Mostrando {{ $orders->firstItem() }} - {{ $orders->lastItem() }} de
                                                {{ $orders->total() }} resultados
                                            </div>
                                        </div>
                                    @else
                                        @lang('No search results')
                                        @if ($searchTerm)
                                            "{{ $searchTerm }}"
                                        @endif

                                        @if ($dateInput)
                                            @lang('from') {{ $dateInput }}
                                            {{ $dateOutput ? __('To') . ' ' . $dateOutput : __('to this day') }}
                                        @endif

                                        @if ($page > 1)
                                            {{ __('in the page') . ' ' . $page }}
                                        @endif
                                    @endif


                                </div>

                            </div>
                            <!--end of content-list-body-->
                        </div>
                    </div>
                </div>
            </div>

    </x-slot>

</x-backend.card>

@push('after-scripts')
    <script type="text/javascript">
        $(function() {
            $(".js-table").on("click", "tr[data-url]", function() {
                window.location = $(this).data("url");
            });
        });
    </script>
@endpush
