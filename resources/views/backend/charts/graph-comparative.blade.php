<div>
    <div id="orders-chart"></div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

document.addEventListener('livewire:load', function () {


    let options = {

        chart: {
            type: 'line',
            height: 380
        },

		title: {
			text: 'Productos asociados a Pedidos. Año actual vs Año anterior',
			align: 'left',
		},

        series: @json($chartData),


        stroke: {
            curve: 'smooth',
            width: 3
        },


        fill: {
        },


        xaxis: {
            type: 'datetime'
        },


        tooltip: {
            x: {
                format: 'dd MMM'
            }
        },


        colors: [
            '#008FFB',
            '#FF4560'
        ],


        legend: {
            position: 'top'
        }

    };


    let chart = new ApexCharts(
        document.querySelector("#orders-chart"),
        options
    );


    chart.render();


});

</script>