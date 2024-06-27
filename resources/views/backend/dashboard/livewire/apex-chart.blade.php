@push('after-styles')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

<div class="mt-4">
  <div id="chart" width="800" height="550"></div>
</div>

@push('after-scripts')
<script>

  var options = {
    series: @json($series),
    chart: {
      type: 'bar',
      height: 580,
      stacked: true,
      events: {
        dataPointSelection: function(event, chartContext, opts) {
          let id = opts.w.config.si.categories[opts.dataPointIndex];
          window.open('order/' + id + '/edit_chart', '_blank');
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
    colors: [
      "#3ABEF9",
      "#3572EF",
      "#050C9C",
      "#FFFF80",
      "#FFE6E6",
      "#E1AFD1",
      "#AD88C6",
      "#7469B6",
      "#EEEEEE",
    ],    
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
      categories: {!! $ordercollection !!},
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