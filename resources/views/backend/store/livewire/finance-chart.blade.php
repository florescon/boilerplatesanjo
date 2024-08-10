<div>
    <td width="30%" style="text-align: center;">
        <img style="margin-left: 50px; margin-right: 20px;" src="{{ asset('img/logo2.svg') }}" width="90" alt="CoreUI Logo">
	 	{{ generated() }}
    </td>


    <canvas id="financialChart"></canvas>
</div>

@push('after-scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>

	<script>

	    document.addEventListener('livewire:load', function () {
	        var ctx = document.getElementById('financialChart').getContext('2d');
	        var chartData = @json($chartData);

	        new Chart(ctx, {
	            type: 'bar',
	            data: chartData,
	            options: {
				    responsive: true,
			        tooltips: {
			            callbacks: {
			                label: function(tooltipItem, data) {
			                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

			                    if (label) {
			                        label += ': ';
			                    }
			                    label += Math.round(tooltipItem.yLabel * 100) / 100;
			                    return label;
			                }
			            }
			        },
			        layout: {
			            padding: {
			                left: 50,
			                right: 50,
			                top: 0,
			                bottom: 50
			            }
			        },
			        plugins: {
			            title: {
			                display: true,
				            text: 'Ingresos/Egresos - Tienda',
			                padding: {
			                    top: 10,
			                    bottom: 30
			                }
			            },
			            subtitle: {
			                display: true,
			                text: 'Últimos 12 meses',
			                padding: {
			                    bottom: 10
			                }
			            }
			        },
	                scales: {
	                    yAxes: [{
	                        ticks: {
	                            beginAtZero: true,
	                        }
	                    }]
	                }
	            }
	        });
	    });
	</script>
@endpush
