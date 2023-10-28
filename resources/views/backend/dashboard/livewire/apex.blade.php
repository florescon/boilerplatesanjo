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
      height: 580,
      stacked: true,
      events: {
        dataPointSelection: function(event, chartContext, opts) {
          let id = opts.w.config.si.categories[opts.dataPointIndex];
          window.open('order/' + id + '/edit', '_blank');
        },
        dataPointMouseEnter: function(event, chartContext, config) {
          event.target.style.cursor = 'pointer';
        },
      },
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
        margin: 40,
        offsetX: 0,
        offsetY: 0,
        floating: false,
        style: {
          fontSize:  '15px',
          fontWeight:  'normal',
          fontFamily:  undefined,
          color:  '#9699a2'
        },
    },
    si: {
      categories: {!! $ids !!},
    },
    xaxis: {
      categories: {!! $categories !!},
      title: {
        text: 'Cantidad de productos'
      },
      labels: {
        formatter: function (val) {
          return val
        }
      }
    },
    yaxis: {
      title: {
        text: 'Pedidos'
      },
      labels: {
          show: true,
          align: 'left',
          minWidth: 0,
          maxWidth: 550,
          style: {
              fontSize: '11px',
              fontFamily: 'Helvetica, Arial, sans-serif',
              fontWeight: 900,
              cssClass: 'apexcharts-yaxis-label',
          },
        formatter: function (value) {
            return value;
        }
      },
      axisBorder: {
          show: true,
          color: '#78909C',
          offsetX: 0,
          offsetY: 0
      },
      axisTicks: {
          show: true,
          borderType: 'solid',
          color: '#78909C',
          width: 380,
          offsetX: 0,
          offsetY: 0
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
      horizontalAlign: 'center',
      offsetX: 40,
      offsetY: -30,
    }
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();

</script>
@endpush