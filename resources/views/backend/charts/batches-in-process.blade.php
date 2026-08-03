@push('after-styles')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

<div>
    <div id="batch-chart"></div>
</div>

@push('after-scripts')

<script>

var options = {

    chart: {
        type: 'bar',
        height: 400,
        stacked: true
    },

    series: @json($chartData['series']),

    xaxis: {
        categories: @json($chartData['categories'])
    },

    colors:[
        '#22c55e',
        '#facc15'
    ],

    plotOptions:{
        bar:{
            horizontal:false
        }
    },

    legend:{
        position:'top'
    }

};


var chart = new ApexCharts(
    document.querySelector("#batch-chart"),
    options
);

chart.render();
</script>
@endpush