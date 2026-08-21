<div>
    {{-- Gráfica de cantidades --}}
    <div id="loadChart"></div>

    {{-- Gráfica de importes --}}
    <div id="loadPriceChart" class="mt-5"></div>
</div>

@push('after-scripts')

{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

<script>
document.addEventListener('livewire:load', function () {

    // ==========================================
    // GRÁFICA DE CANTIDADES
    // ==========================================

    let chart = new ApexCharts(
        document.querySelector("#loadChart"),
        {
            chart: {
                type: 'line',
                height: 380
            },

            title: {
                text: 'Productos asociados a Pedidos. Año actual vs Año anterior ' + @json(generated()),
                align: 'left'
            },

            series: @json($chartData),

            stroke: {
                curve: 'smooth',
                width: 3
            },

            xaxis: {
                type: 'datetime'
            },

            tooltip: {
                x: {
                    format: 'dd MMM'
                },

                y: {
                    formatter: function(value) {
                        return Number(value).toLocaleString('es-MX', {
                            minimumFractionDigits: 0,
                        });
                    }
                }
            },

            colors: [
                '#008FFB',
                '#FF4560'
            ],

            legend: {
                position: 'top'
            }
        }
    );

    chart.render();


    // ==========================================
    // GRÁFICA DE IMPORTES
    // ==========================================

    let priceChart = new ApexCharts(
        document.querySelector("#loadPriceChart"),
        {
            chart: {
                type: 'line',
                height: 380
            },

            title: {
                text: 'Importe de Pedidos. Año actual vs Año anterior ' + @json(generated()),
                align: 'left'
            },

            series: @json($loadPriceChart),

            stroke: {
                curve: 'smooth',
                width: 3
            },

            xaxis: {
                type: 'datetime'
            },

            yaxis: {
                labels: {
                    formatter: function(value) {
                        return '$' + Number(value).toLocaleString('es-MX', {
                            minimumFractionDigits: 0
                        });
                    }
                }
            },

            tooltip: {
                x: {
                    format: 'dd MMM'
                },

                y: {
                    formatter: function(value) {
                        return '$' + Number(value).toLocaleString('es-MX', {
                            minimumFractionDigits: 0
                        });
                    }
                }
            },

            colors: [
                '#00E396',
                '#FEB019'
            ],

            legend: {
                position: 'top'
            }
        }
    );

    priceChart.render();

});
</script>

@endpush