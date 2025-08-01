<x-backend.card>

    <x-slot name="body">

        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-12">
                <ul class="nav nav-tabs nav-fill mt-1 no-print shadow-sm" role="tablist">
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.order.all_chart') }}"
                            role="tab">@lang('all')</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'quotations' ? 'active' : '' }}"
                            href="{{ route('admin.order.quotations_chart') }}" role="tab">@lang('Quotations')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == '' ? 'active' : '' }}" href="{{ route('admin.order.request_chart') }}"
                            role="tab">@lang('Requests')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.station.index_production', ['selectStatus' => '15', 'history' => true]) }}"
                            role="tab">@lang('Outputs')</a>
                    </li>
                </ul>

                @if ($status == 'quotations')
                    <div class="page-header-subtitle text-right mt-4 mb-2">
                        <x-utils.link style="color: green;" target="_blank" icon="c-icon cil-plus" class="card-header-action"
                            :href="route('admin.order.quotation_chart')" :text="__('Create quotation')" />
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
                    <div wire:ignore class="d-inline mr-4 ">
                        <h1 class="d-inline"><a wire:click="exportMaatwebsite('xlsx')"><span class="badge bg-light text-primary mb-2" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Totales en: </em> <b>Captura</b>">{{ \App\Models\Order::getTotalCaptureProducts() }}</span></a></h1>
                    </div>

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
                                        <option>50</option>
                                    </select>

                                    @if ($status == '' || $status == 'all')
                                        <div class="ml-4 mt-2">
                                          <button type="button" class="m-1 btn {{ $history ? 'btn-warning text-white' : 'btn-secondary' }}" wire:click="isHistory">@lang('History')</button>
                                        </div>
                                        @if($dateInput && $dateOutput && $history)
                                            <button class="btn btn-primary btn-sm ml-4 mt-2"
                                                wire:click="printExportOrdersForDate">
                                                @lang('Export')
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
                            <form class="col-sm-6">
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
                                                        href="{{ route('admin.order.edit_chart', $order->id) }}"
                                                        style="text-decoration: none !important">
                                                        <h6 data-filter-by="text" style="display: inline;">
                                                            <strong>#{!! $order->folio_or_id !!}</strong>
                                                            {!! $order->user_name !!} {!! Str::limit($order->info_customer, 100) ?? '' !!} </h6>
                                                    </a>
                                                    @if ($order->comment)
                                                        <em class="text-small text-primary"
                                                            style=""><strong>{!! Str::limit($order->comment, 100) !!}</strong></em>
                                                    @endif
                                                    &nbsp;
                                                        <span class="badge badge-pill badge-secondary">
                                                            {!! $order->date_for_humans !!}
                                                        </span>
                                                    <br>

                                                    {!! $order->type_order !!}

                                                    @if (!$order->isSuborder())
                                                        {!! $order->total_products_and_services_line_label !!}
                                                    @else
                                                        <strong class="pl-4">Totales:
                                                            {{ $order->total_products_suborder }}
                                                        </strong>
                                                    @endif
                                                    @if ($order->quotation !== 0)
                                                        <strong class="pl-2">@lang('QUOTATION'):</strong>
                                                        #{{ $order->quotation }}
                                                    @endif
                                                    @if ($order->purchase)
                                                        <strong class="pl-2">O. DE COMPRA:</strong>
                                                        #{{ $order->purchase }}
                                                    @endif
                                                    @if ($order->request)
                                                        <strong class="pl-2">SOLICITUD:</strong>
                                                        #{{ $order->request }}
                                                    @endif
                                                    @if ($order->invoice)
                                                        <strong class="pl-2">FACTURA:</strong>
                                                        {{ $order->invoice }}
                                                    @endif
                                                </div>
                                                <div class="card-meta" style="">
                                                    @if($status == '' || $order->complementary)

                                                    <div class="d-flex align-items-center">

                                                        @if($order->complementary)
                                                            <span class="text-center text-danger pr-2"> 
                                                            {{ $order->complementary }}
                                                            </span>
                                                        @endif

                                                        @php
                                                            $lastBatch = $order->productionBatches
                                                                ->where('status_id', 15)
                                                                ->sortByDesc('created_at')
                                                                ->first();
                                                        @endphp

                                                        @if($lastBatch)
                                                        <span class="text-center">
                                                            Últ. Salida
                                                            <br> 
                                                            <span class="text-center text-primary mr-2">
                                                                {{ $lastBatch->date_for_humans }}
                                                            </span>
                                                        </span>
                                                        @endif
                                                        <span class="text-center">
                                                            {!! $order->validateAllExists() 
                                                                ? '<i class="cil-check" style="color: blue;"></i>'
                                                                : '' !!}
                                                        </span>
                                                    </div>

                                                    @endif
                                                    <div class="dropdown card-options no-print">
                                                        <button class="btn-options" type="button"
                                                            id="task-dropdown-button-1" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="cil-options"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" target="_blank"
                                                                href="{{ route('admin.order.edit_chart', $order->id) }}">@lang('Show')</a>
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
