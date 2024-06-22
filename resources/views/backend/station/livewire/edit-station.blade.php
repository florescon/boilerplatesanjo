<div class="container">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="page-content page-container" id="page-content">
                <div class="padding">
                    <div class="row container d-flex justify-content-center">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card {{ $station->card_secondary }}">
                                <div class="card-body">
                                    <ul id="docs-nav-pills" class="nav nav-pills mb-4" role="tablist">
                                        <li class="nav-item">
                                            <a id="docs-tab-overview" class="nav-link active px-5 font-weight-bold" href="#!">ID: #{{ $station->id }} </a>
                                        </li>
                                        <li class="nav-item">
                                            <a id="docs-tab-overview" class="nav-link px-5 font-weight-bold" href="#!">f.º: #{{ $station->folio }} </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-5 font-weight-bold" href="#!"> Orden: #{{ $station->order_id }}</a>
                                        </li>
                                                
                                        <li class="nav-item">
                                            <a class="nav-link px-5 font-weight-bold" href="#!">Fecha creado: {{ $station->created_at_for_humans }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link px-5 font-weight-bold" href="#!">
                                                <i class="fas fa-comments" aria-hidden="true"></i> @lang('Comment')
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-5 font-weight-bold" href="#!">
                                                Lapso de creado a última actualización: &nbsp;&nbsp;&nbsp; {{ $station->getDifferenceForHumans() }}
                                            </a>
                                        </li>
                                        @if($station->active)
                                            <li class="nav-item">
                                                <a class="nav-link px-5 font-weight-bold" href="#!">
                                                    Tiempo transcurrido: &nbsp;&nbsp;&nbsp; {{ $station->getElapsedForHumans() }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="page-content page-container" id="page-content">
                <div class="padding">
                    <div class="row container d-flex justify-content-center">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card {{ $station->card_secondary }}">
                                <div class="card-body">
                                    <h4 class="card-title">{{ ucfirst(optional($station->status)->name) }}</h4>
                                    <p class="card-description">
                                        {{-- Basic table with card --}}
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <caption><em>@lang('Captured by'): {{ optional($station->audi)->name }}</em></caption>
                                            <thead>
                                                <tr>
                                                    <th>@lang('Qty')</th>
                                                    <th>@lang('Concept')</th>
                                                    <th>@lang('Input')</th>
                                                    <th>@lang('Output')</th>
                                                    <th>@lang('Created at')</th>
                                                    <th>@lang('Last Updated')</th>
                                                    <th>@lang('Details')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($station->product_station->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product_station)
                                                    <tr>
                                                        <th width="10%">{{ $product_station->quantity }}</th>
                                                        <td width="44%" class="{{ $product_station->line_through }}">{!! $product_station->product->full_name_link !!}</td>
                                                        <td width="5%">{{ $product_station->metadata['open'] ?? '' }}</td>
                                                        <td width="5%">{{ $product_station->metadata['closed'] ?? '' }}</td>
                                                        <td width="13%">{{ $product_station->created_at_for_humans }}</td>
                                                        <td width="13%">{{ $product_station->updated_at_for_humans }}</td>
                                                        <td width="10%">
                                                            @if($product_station->active)
                                                                <label class="badge badge-success">@lang('Active')</label>
                                                            @else
                                                                <label class="badge badge-danger">@lang('Inactive')</label>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @foreach($product_station->product_station_receiveds as $product_station_received)
                                                    <tr>
                                                        <td></td>
                                                        <td colspan="6"><em>{!! $product_station_received->quantity.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp Recibido '.$product_station->created_at_for_humans !!}</em></td>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endforeach
                                                <tr>
                                                    <th class="text-primary"> {{ $station->total_products_station }} </th>
                                                    <th></th>
                                                    <th class="text-primary"> {{ $station->total_products_station_open }} </th>
                                                    <th class="text-primary"> {{ $station->total_products_station_closed }} </th>
                                                    <th colspan="3"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>        
