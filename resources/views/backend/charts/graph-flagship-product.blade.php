@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">

    <style>
        .kpi-card {
            border: none;
            border-radius: 12px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }

        .kpi-icon {
            font-size: 40px;
            opacity: .8;
        }

        .kpi-value {
            font-size: 32px;
            font-weight: bold;
        }

        .bg-gradient-blue {
            background: linear-gradient(45deg, #007bff, #00c6ff);
        }

        .bg-gradient-green {
            background: linear-gradient(45deg, #28a745, #7bed9f);
        }

        .bg-gradient-orange {
            background: linear-gradient(45deg, #fd7e14, #ffc107);
        }

        .bg-gradient-red {
            background: linear-gradient(45deg, #dc3545, #ff7675);
        }
    </style>
@endpush

<div>

    <legend>@lang('Flagship Product')</legend>

	<div class="alert alert-primary text-center" role="alert">
	  Productos provenientes de pedidos,
	</div>

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
		        <option value="4years">Últimos 4 años</option>
		    </select>
		</div>

        <div class="col-md-6">
            <select wire:model="limitProducts" class="form-control text-center select-azul">
                <option value="3">3 Productos</option>
                <option value="6">6 Productos</option>
                <option value="12">12 Productos</option>
                <option value="24">24 Productos</option>
            </select>
        </div>
    </div>

        <div class="row">

            <!-- Ventas -->
            {{-- <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Ventas</h6>
                                <div class="kpi-value">$125K</div>
                                <small>+12% este mes</small>
                            </div>
                            <i class="fas fa-dollar-sign kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-green">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Clientes</h6>
                                <div class="kpi-value">3,250</div>
                                <small>+8% crecimiento</small>
                            </div>
                            <i class="fas fa-users kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pedidos -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-orange">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Pedidos</h6>
                                <div class="kpi-value">856</div>
                                <small>Hoy</small>
                            </div>
                            <i class="fas fa-shopping-cart kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversión -->
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card kpi-card bg-gradient-red">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Conversión</h6>
                                <div class="kpi-value">24%</div>
                                <small>+3% vs anterior</small>
                            </div>
                            <i class="fas fa-chart-line kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>


    <div wire:ignore id="treemapProductos"></div>
    <div wire:ignore id="averagePriceProductos"></div>
</div>

@push('after-scripts')

{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

<script>

	document.addEventListener('livewire:load', function () {

	    let treemap = new ApexCharts(
	        document.querySelector("#treemapProductos"),
	        {
	            chart:{
	                type:'treemap',
	                height:450
	            },
	            legend:{
	                show:false
	            },
		        tooltip:{
		            y:{
		                formatter:function(value){
		                    return new Intl.NumberFormat('es-ES').format(value);
		                }
		            }
		        },
				dataLabels:{
				    enabled:true,
				    formatter:function(text, op){
				        let value = op.w.config.series[op.seriesIndex].data[op.dataPointIndex].y;

				        return [
				            text,
				            new Intl.NumberFormat('es-ES').format(value)
				        ];
				    }
				},
	            series:[{
		            data: @json($treemap)
	            }]
	        }
	    );

	    let average = new ApexCharts(
	        document.querySelector("#averagePriceProductos"),
	        {
	            chart:{
		            type: 'line',
	                height:450
	            },
	            legend:{
	                show:false
	            },

		        series: [

		            {
		                name: 'P̄ Precio compra',
		                type: 'column',
		                data: @json($average['precioCompra'])
		            },

		            {
		                name: 'P̄ Precio venta',
		                type: 'column',
		                data: @json($average['precioVenta'])
		            },

		            {
		    	            name: '% Ganancias',
		                type: 'line',
		                data: @json($average['utilidad'])
		            },


		        ],

		        stroke: {
		            width: [0,0,4]
		        },

		        xaxis: {
		            categories: @json($average['categories'])
		        },


		        yaxis: [

		            {
		                title:{
		                    text:'P̄'
		                }
		            },

		        ],
		        tooltip:{
		            shared:true,
		            intersect:false,
		        },

		        dataLabels:{
				    enabled: true,
				    enabledOnSeries: [2],
		        }

	        }
	    );

	    treemap.render();
	    average.render();

		Livewire.on('treemapProductos', function(data){
		    treemap.updateSeries([{data:data}]);
		});

		Livewire.on('averagePriceProductos', function(data){

		    average.updateOptions({
		        xaxis:{
		            categories: data.categories
		        }
		    });

		    average.updateSeries([
		        {
		            name: 'P̄ Precio compra',
		            type: 'column',
		            data: data.precioCompra
		        },
		        {
		            name: 'P̄ Precio venta',
		            type: 'column',
		            data: data.precioVenta
		        },
		        {
		            name: '% Ganancias',
		            type: 'line',
		            data: data.utilidad
		        },
		    ]);
		});
	});

</script>
@endpush	