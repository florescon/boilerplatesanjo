@push('after-styles')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

<div>
  <div id="chart" width="800" height="550"></div>
</div>

@push('after-scripts')
<script>

  var captura = {!! $captura !!};
  var corte = {!! $corte !!};
  var confeccion = {!! $confeccion !!};
  var conformado = {!! $conformado !!};
  var personalizacion = {!! $personalizacion !!};
  var embarque = {!! $embarque !!};

  var options = {
      series: [{
      name: 'captura',
      data: captura
    }, {
      name: 'corte',
      data: corte
    }, {
      name: 'confeccion',
      data: confeccion
    }, {
      name: 'conformado',
      data: conformado
    }, {
      name: 'personalizacion',
      data: personalizacion
    }, {
      name: 'embarque',
      data: embarque
    }],
      chart: {
      type: 'bar',
      height: 380,
      stacked: true,
    },
    plotOptions: {
      bar: {
        horizontal: true,
        dataLabels: {
          total: {
            enabled: true,
            offsetX: 0,
            style: {
              fontSize: '13px',
              fontWeight: 900
            }
          }
        }
      },
    },
    stroke: {
      width: 1,
      colors: ['#fff']
    },
    title: {
      text: 'Pedidos en proceso'
    },
    subtitle: {
        text: 'Listado de pendientes',
        align: 'left',
        margin: 30,
        offsetX: 0,
        offsetY: 0,
        floating: false,
        style: {
          fontSize:  '12px',
          fontWeight:  'normal',
          fontFamily:  undefined,
          color:  '#9699a2'
        },
    },
    xaxis: {
      categories: {!! $categories !!},
      labels: {
        formatter: function (val) {
          return val + " Prod."
        }
      }
    },
    yaxis: {
      title: {
        text: 'Pedidos'
      },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val
        }
      }
    },
    fill: {
      opacity: 1
    },
    legend: {
      position: 'top',
      horizontalAlign: 'left',
      offsetX: 40
    }
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();

</script>
@endpush