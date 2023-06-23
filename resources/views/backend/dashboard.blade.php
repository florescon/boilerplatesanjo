@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@endpush

@section('content')
    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.dashboard.kanban')))
    <x-backend.card>
        <x-slot name="body">

              <div>
                <div id="chart" width="800" height="550"></div>
              </div>

            {{-- @lang('Welcome to the Dashboard') --}}
        
            {{-- <livewire:backend.dashboard.kanban /> --}}

        </x-slot>

        <x-slot name="footer">

          <div class="content-list" data-filter-list="content-list-body">
            <!--end of content list head-->
            <div class="content-list-body">

              <div class="card card-note">
                <div class="card-header">
                  <div class="media align-items-center">
                    <div class="media-body">
                      <h6 class="mb-0" data-filter-by="text">Nota:</h6>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <span data-filter-by="text">A considerar</span>
                    <div class="ml-1 dropdown card-options">
                      <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="cil-hand-point-left"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item text-danger" href="#">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body ml-4" data-filter-by="text">
                  <ul>
                    <li>Los listado muestran un límite de diez registros por defecto, para ello considere los totales en los encabezados de cantidades y en totales de artículos</li>
                  </ul>

                </div>
              </div>

            </div>
          </div>

        </x-slot>
    
    </x-backend.card>
    @endif
@endsection

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
    height: 330,
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
  xaxis: {
    categories: [2008, 2009, 2010, 2011, 2012, 2013, 2014],
    labels: {
      formatter: function (val) {
        return val + "K"
      }
    }
  },
  yaxis: {
    title: {
      text: undefined
    },
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + "K"
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