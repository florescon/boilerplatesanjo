@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush


<div>

<div wire:ignore style="height:200px;">
    <div id="heatmapProductos" style="height:200;"></div>
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
		        <div class="col-md-3 col-sm-6 mb-3">
		            <a href="#" wire:click="selectProduct({{ $product['id'] }})" class="text-decoration-none">
		                <div class="card card-flyer card-product h-100 {{ $product['type'] == false ? 'bg-dark text-white' : '' }}">
		                    <div class="card-body d-flex flex-column justify-content-between">
		                        <div>
		                            <h5 class="card-title text-center">
		                                <strong>{{ $product['name'] }}</strong>
		                            </h5>

		                            <h6 class="text-muted text-center">
		                                {{ $product['code'] }}
		                            </h6>
		                        </div>

		                        <div class="text-center">
		                            <h2 class="text-primary">
		                                ${{ priceIncludeIva($product['price']) ?? 0 }}
		                            </h2>

		                            <div class="small text-muted">
		                                {{ $product['price'] ? '$'.$product['price'] : 'undefined price' }}
		                            </div>
		                        </div>

		                        @if($product['brand_id'])
		                            <div class="text-center mt-3">
		                                <span class="badge badge-dark px-3 py-2">
		                                    {{ $product['brand']['name'] }}
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




    <div class="row mb-6">
		<div class="col-md-6">
		    <select wire:model="periodo" class="form-control text-center">
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
		    	@if($selectedProduct) 
                    <strong class="text-danger">
                        {{ $selectedProduct->code }} 
                    </strong>
                    <strong class="text-primary">
	       	    		{{ $selectedProduct->name }}
                    </strong>
		    	@endif 
		    </legend>

        </div>
    </div>

        <div class="row">

            <!-- Ventas -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Ventas</h6>
                                <div class="kpi-value">{{ number_format($ventasTotales, 2) }}</div>
	                            </div>
                            <i class="fas fa-dollar-sign kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendidos -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-green">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Productos Vendidos</h6>
                                <div class="kpi-value">{{ $productosVendidos }}</div>
                            </div>
                            <i class="fas fa-shopping-cart kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Utilidad Total -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-orange">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Utilidad Total</h6>
                                <div class="kpi-value">{{ number_format($utilidadTotal, 2) }}</div>
                            </div>
                            <i class="fas fa-chart-line kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- M. de utilidad -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-red">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Margen de utilidad</h6>
                                <div class="kpi-value"> {{ number_format($margenUtilidad, 2) }}%</div>
                            </div>
                            <i class="fas fa-chart-line kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

</div>


@push('after-scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

document.addEventListener('livewire:load', function () {

	let heatmap = new ApexCharts(
	    document.querySelector("#heatmapProductos"),
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
                            name: 'Sin ventas'
                        },
                        {
                            from: 1,
                            to: 5,
                            color: '#C8E6C9',
                            name: '1 - 5'
                        },
                        {
                            from: 6,
                            to: 15,
                            color: '#81C784',
                            name: '6 - 15'
                        },
                        {
                            from: 16,
                            to: 30,
                            color: '#4CAF50',
                            name: '16 - 30'
                        },
                        {
                            from: 31,
                            to: 999999,
                            color: '#1B5E20',
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
		                <strong>${point.y} piezas</strong><br>
		                ${fecha}
		            </div>
		        `;
		    }
		},
        title: {
            text: 'Ventas en el último año. Gráfica fija.'
        },

        noData: {
            text: 'Busque el producto para mostrar el mapa de calor'
        }
    });

    heatmap.render();

    Livewire.on('heatmapProductos', function (series) {
        heatmap.updateSeries(series);
    });

});
</script>
@endpush	