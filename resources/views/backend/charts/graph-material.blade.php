@push('after-styles')

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
        .card-product {
            height: 110px;
            font-size: 12px;
        }

        .card-product .product-name {
            font-size: 13px;
            line-height: 1.1;
        }

        .card-product .product-part {
            font-size: 10px;
            line-height: 1.1;
        }

        .card-product .product-price {
            font-size: 20px;
            font-weight: 600;
            line-height: 1;
        }

        .card-product .product-old-price {
            font-size: 9px;
            line-height: 1;
        }

        .card-product .product-family {
            font-size: 9px;
            line-height: 1;
        }

        .card-product .card-body {
            padding: 8px !important;
        }
    </style>
@endpush

<div>

    <div wire:ignore style="height:200px;">
        <div id="heatmapMaterials" style="height:200;"></div>
    </div>

    <div class=" row mb-4 justify-content-md-center">
      <div class="col-8">
        <div class="input-group">
          <input
            wire:model="query" 
            type="text" 
            class="input-search"
            placeholder="{{ __('Search') }}..."
            wire:keydown.escape="reset_search"
            {{-- wire:keydown.tab="reset_search" --}}
            wire:keydown.ArrowUp="decrementHighlight"
            wire:keydown.ArrowDown="incrementHighlight"
           />
            <span class="border-input-search"></span>

        </div>
        <div wire:loading wire:target="query">@lang('Searching')...</div>
      </div>

      @if(!empty($query))
        <div class="input-group-append">
          <button type="button" wire:click="reset_search" class="close" aria-label="Close">
            <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
          </button>
        </div>
      @endif

    </div>

    @if(!empty($query))
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 col-sm-6 mb-2">
                    <a href="#"
                       wire:click="selectProduct({{ $product['id'] }})"
                       class="text-decoration-none">

                        <div class="card card-flyer card-product">
                            <div class="card-body py-2 px-2">

                                {{-- Nombre y código --}}
                                <div>
                                    <div class="product-name text-center">
                                        <strong>{{ $product['name'] }}</strong>
                                    
                                        @if(!empty($product['color']['color']))
                                            <div
                                                class="box-color justify-content-md-center"
                                                style="background-color: {{ $product['color']['color'] }}; display: inline-block;">
                                            </div>
                                            {{ $product['color']['name'] }}
                                        @endif

                                    </div>

                                    <div class="product-part text-muted text-center">
                                        {{ $product['part_number'] }}
                                    </div>
                                </div>

                                {{-- Precio --}}
                                <div class="text-center mt-1">
                                    <div class="product-price text-primary">
                                        ${{ $product['price']  ?? 0 }}
                                    </div>

                                </div>

                                {{-- Familia --}}
                                @if($product['family_id'])
                                    <div class="text-center mt-1">
                                        <span class="badge badge-dark px-2 py-1 product-family">
                                            {{ $product['family']['name'] }}
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif



    @if($selectedMaterial) 
        <div class="row mb-6">
            <div class="col-md-6">
                <select wire:model="periodo" class="form-control text-center select-azul">
                    <option value="7days">Últimos 7 días</option>
                    <option value="15days">Últimos 15 días</option>
                    <option value="30days">Últimos 30 días</option>
                    <option value="3months">Últimos 3 meses</option>
                    <option value="6months">Últimos 6 meses</option>
                    <option value="1year">Último año</option>
                    <option value="2years">Últimos 2 años</option>
                </select>
            </div>

            <div class="col-md-6 text-center">
                <legend> 
                        <strong class="text-danger">
                            {{ $selectedMaterial->part_number }} 
                        </strong>
                        <strong class="text-primary">
                            {{ $selectedMaterial->name }}
                        </strong>

                        @if(!empty($selectedMaterial->color_id))
                            <div
                                class="box-color justify-content-md-center"
                                style="background-color: {{ $selectedMaterial->color->color }}; display: inline-block;">
                            </div>
                            {{ $selectedMaterial->color->name }}
                        @endif

                </legend>

            </div>
        </div>
    @endif 


        <div class="row">

            <!-- Vendidos -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-green custom-hover-card" style="background-color: #FFF0C1">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Costo Consumo</h6>
                                <div class="kpi-value">
                                   $ {{ number_format($consumptionP, 1) }}
                                </div>
                            </div>
                            <i class="fas fa-dollar-sign kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-blue custom-hover-card" style="background-color: #FFF0C1">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Consumido</h6>
                                <div class="kpi-value">
                                    {{ number_format($consumptionQ, 1) }}
                                    @if(!empty($selectedMaterial->unit_id))
                                        {{ optional($selectedMaterial->unit)->name }}
                                    @endif
                                </div>
                            </div>
                            <i class="fas fa-boxes kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Utilidad Total -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-orange custom-hover-card" style="background-color: #FFF0C1">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Compras</h6>
                                <div class="kpi-value">
                                    {{ number_format($historiesQ, 1) }}
                                    @if(!empty($selectedMaterial->unit_id))
                                        {{ optional($selectedMaterial->unit)->name }}
                                    @endif

                                </div>
                            </div>
                            <i class="fas fa-boxes kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- M. de utilidad -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-red custom-hover-card" style="background-color: #FFF0C1">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Gastos</h6>
                                <div class="kpi-value">$ {{ number_format($historiesP, 1) }}</div>
                            </div>
                            <i class="fas fa-shopping-cart kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Contenido principal -->
            <div class="col-lg-8">

                <div wire:ignore id="lineMaterials"></div>

            </div>

            <!-- Tasks -->
            <div class="col-lg-4">
                <div class="card mt-4">

                    <!-- Card header -->

                    <!-- Card body -->
                    <div class="card-body p-0">

                        <ul class="list-group list-group-flush">

                            <!-- Task 1 -->
                            <li class="list-group-item mr-4 ml-4 py-3 d-flex align-items-center">

                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        Stock actual
                                    </div>
                                    <small class="text-muted">
                                        Existencia disponible
                                    </small>
                                </div>

                                <div class="ml-3 text-info font-weight-bold"
                                     style="font-size: 1.3rem; line-height: 1;">
                                    {{ $currentStock }}

                                    @if(!empty($selectedMaterial->unit_id))
                                        {{ optional($selectedMaterial->unit)->name }}
                                    @endif

                                </div>

                            </li>
                            <!-- Task 2 -->
                            <li class="list-group-item mr-4 ml-4 py-3 d-flex align-items-center">

                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        Costo unitario
                                    </div>
                                    <small class="text-muted">
                                        Precio actual de la materia prima sin IVA
                                    </small>
                                </div>

                                <div class="ml-3 text-info font-weight-bold"
                                     style="font-size: 1.3rem; line-height: 1;">
                                    ${{ $unitCost }}
                                </div>

                            </li>

                            <!-- Task 3 -->
                            <li class="list-group-item mr-4 ml-4 py-3 d-flex align-items-center">

                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        Valor del inventario
                                    </div>
                                    <small class="text-muted">
                                        Stock × costo unitario
                                    </small>
                                </div>

                                <div class="ml-3 text-info font-weight-bold"
                                     style="font-size: 1.3rem; line-height: 1;">
                                    ${{ number_format($inventoryValue, 1) }}
                                </div>

                            </li>
                            <!-- Task 4 -->
                            <li class="list-group-item mr-4 ml-4 py-3 d-flex align-items-center">

                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        Última compra
                                    </div>
                                    <small class="text-muted">
                                        Fecha por factura ingresada.
                                    </small>
                                </div>

                                <div class="ml-3 text-info font-weight-bold"
                                     style="font-size: 1.3rem; line-height: 1;">
                                    {{ $lastPurchase ? $lastPurchase : '--' }}
                                </div>

                            </li>


                            <!-- Task 5 -->

                            <li class="list-group-item mr-4 ml-4 py-3 d-flex align-items-center">

                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        % Variación de precio
                                    </div>
                                    <small class="text-muted">
                                        Cambio contra periodo anterior
                                    </small>
                                </div>

                                <div class="ml-3 text-info font-weight-bold"
                                     style="font-size: 1.3rem; line-height: 1;">
                                    {{ number_format($priceVariation, 1) }}%
                                </div>

                            </li>

                        </ul>

                    </div>
                </div>
            </div>

        </div>


{{-- <div id="sankey" wire:ignore></div> --}}

</div>


@push('after-scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('livewire:load', function () {

    let heatmap = new ApexCharts(
        document.querySelector("#heatmapMaterials"),
        {
        chart: {
            type: 'heatmap',
            height: 200,
            toolbar: {
                show: true
            },
            animations: {
                enabled: true
            }
        },

        series: [],

        dataLabels: {
            enabled: false
        },

        colors: ['#008FFB'],

        stroke: {
            width: 1
        },

        plotOptions: {
            heatmap: {
                shadeIntensity: 0.6,
                radius: 2,

                colorScale: {
                    ranges: [
                        {
                            from: 0,
                            to: 0,
                            color: '#F3F3F3',
                            name: 'Sin consumos'
                        },
                        {
                            from: 1,
                            to: 5,
                            color: '#FFF0C1',
                            name: '1 - 5'
                        },
                        {
                            from: 6,
                            to: 15,
                            color: '#FFE082',
                            name: '6 - 15'
                        },
                        {
                            from: 16,
                            to: 30,
                            color: '#FFC107',
                            name: '16 - 30'
                        },
                        {
                            from: 31,
                            to: 999999,
                            color: '#F57F17',
                            name: '+30'
                        }
                    ]
                }
            }
        },

        xaxis:{
            type:'datetime',
            labels:{
                format:'MMM'
            }
        },

        tooltip:{
            custom:function({seriesIndex,dataPointIndex,w}){
                let point = w.config.series[seriesIndex].data[dataPointIndex];
                let fecha = new Date(point.date).toLocaleDateString();
                return `
                    <div class="p-2">
                        <strong>${point.y}</strong><br>
                        ${fecha}
                    </div>
                `;
            }
        },
        title: {
            text: 'Consumos en el último año. Gráfica fija.'
        },

        noData: {
            text: 'Busque y seleccione la materia prima para mostrar el mapa de calor'
        }
    });



    let line = new ApexCharts(
        document.querySelector("#lineMaterials"),
        {
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: false
                }
            },

            series: [],

            xaxis: {
                categories: []
            },

            dataLabels: {
                enabled: false,

                style: {
                    fontSize: '11px',
                    fontWeight: 'bold'
                }
            },

            stroke: {
                curve: 'smooth',
                width: 3
            },

            markers: {
                size: 5
            },

            colors: [
                '#008FFB', // Solicitado
                '#00E396'  // Consumo
            ],

            legend: {
                position: 'top'
            },

            tooltip: {
                y: {
                    formatter: function (value) {
                        return value;
                    }
                }
            },

            noData: {
                text: 'Sin información'
            }
        }
    );

    heatmap.render();
    line.render();

    Livewire.on('heatmapMaterials', function (series) {
        heatmap.updateSeries(series);
    });

    Livewire.on('lineMaterials', function (data) {

        line.updateOptions({
            xaxis: {
                categories: data.categories
            },
            series: data.series
        });
    });

});

</script>

@endpush