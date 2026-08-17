<div class="dropdown table-export text-center">
    <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      @lang('Export')        
    </button>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" wire:click="exportMaatwebsite('xlsx')">Excel</a>
    </div>
</div><!--export-dropdown-->

<div id="ordersQuantityChart"></div>

@push('after-scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

let products = @json($products);
let services = @json($services);
let months = @json($monthss);

let options = {
    chart: {
        height: 350,
        type: 'line'
    },

    series: [
        {
            name: 'Productos',
            type: 'column',
            data: products
        },
        {
            name: 'Servicios',
            type: 'line',
            data: services
        }
    ],

    xaxis: {
        categories: months
    },

    colors: [
        '#008FFB',
        '#00E396'
    ],

    stroke: {
        width: [0, 4],
        curve: 'smooth'
    },

    plotOptions: {
        bar: {
            columnWidth: '45%'
        }
    },

    markers: {
        size: 5
    },

    dataLabels: {
        enabled: true
    },

    title: {
        text: 'Últimos 12 meses de Pedidos Procesados: Productos vs Servicios'
    }
};

let chart = new ApexCharts(
    document.querySelector("#ordersQuantityChart"),
    options
);

chart.render();

</script>
@endpush