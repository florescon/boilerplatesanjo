@push('after-styles')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush


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

<script>

let months = @json($months);
let products = @json($products);
let services = @json($services);

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
        '#008FFB', // Productos
        '#00E396'  // Servicios
    ],

    stroke: {
        width: [0, 4], // Sin línea para columnas, línea de 4px para servicios
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
        text: 'Pedidos Procesados: Productos vs Servicios'
    }
};

let chart = new ApexCharts(
    document.querySelector("#ordersQuantityChart"),
    options
);

chart.render();

</script>
@endpush