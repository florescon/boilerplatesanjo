@push('after-styles')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

<div>
  <div id="chart" width="800" height="550"></div>
</div>

@push('after-scripts')
<script>
  var options = {
      series: [{
      name: 'Marine Sprite',
      data: [44, 55, 41, 37, 22, 43, 21]
    }, {
      name: 'Striking Calf',
      data: [53, 32, 33, 52, 13, 43, 32]
    }, {
      name: 'Tank Picture',
      data: [12, 17, 11, 9, 15, 11, 20]
    }, {
      name: 'Bucket Slope',
      data: [9, 7, 5, 8, 6, 9, 4]
    }, {
      name: 'Reborn Kid',
      data: [25, 12, 19, 32, 25, 24, 10]
    }],
      chart: {
      type: 'bar',
      height: 380,
      stacked: true,
      events: {
          dataPointSelection: function(event, chartContext, obj) {
              return document.location.href = obj.w.config.series[obj.seriesIndex].data[obj.dataPointIndex].z;
          }
      }
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
      text: 'Fiction Books Sales'
    },
    subtitle: {
        text: 'subtitle',
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
        text: 'orders'
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