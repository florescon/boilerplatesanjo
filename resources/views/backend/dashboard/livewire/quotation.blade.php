<div class="" wire:poll.keep-alive.600s>
  @lang('Last Updated'): {{ now()->format('g:i a') }}

  <div class="container-fluid page-header d-flex justify-content-between align-items-start">
    <div>
      <h4>@lang('Quotations') <span class="badge badge-danger">{{ $quotations->total() }}</span></h4>
    </div>
    <div class="d-flex align-items-center">
      <a href="{{ route('admin.order.quotation_chart') }}" class="btn btn-round flex-shrink-0" data-toggle="tooltip" data-placement="top" title="{{ __('Create Quotation') }}">
        <i class="cil-plus"></i>
      </a>
      <input type="text" class="form-control ml-3" wire:model.debounce.350ms="searchTerm" placeholder="{{ __('Search in quotations') }}" aria-label="Recipient's username" aria-describedby="basic-addon2">
    </div>
  </div>

  <div class="kanban-board container-fluid mt-lg-3">

    <div class="kanban-col">
      <div class="card-list text-center">
        <strong> @lang('Articles'): {{ $quotations->sum('sum') }} </strong>
      </div>
      <div class="card-list bg-primary">
        <a href="{{ route('admin.order.quotation_chart') }}" class="btn btn-link text-white btn-sm text-small">@lang('Add quotation') <i class="cil-plus"></i>
        </a>
      </div>
      <div class="card-list bg-primary"> 
        <a href="{{ route('admin.order.quotations_chart') }}" class="btn btn-link text-white btn-sm text-small">@lang('Show all quotations')</a>
      </div>
    </div>

    @foreach($quotations as $quotation)

      <div class="kanban-col">
        <div class="card-list" style="background-color: #F5F5F5;">
          <div class="card-list-header">
            <h6>
              @lang('Quotation') 
              #{{ $quotation->folio ?? $quotation->id }}
            </h6>
            <div class="dropdown">
              <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="cil-list"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
                <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            
              </div>
            </div>
          </div>
          <div class="card-list-body">

              <div class="card card-kanban" style="margin-bottom: 20px;">

                <div class="card-body">
                  <div class="dropdown card-options">
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}">@lang('Edit')</a>
                      <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                    </div>
                  </div>
                  <div class="card-title">
                    <a target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}"><h6>{{ Str::limit($quotation->customer, 22) }}</h6></a>
                    <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}"><h6>{{ Str::limit($quotation->comment, 23) }}</h6></a></p>
                  </div>
                  <div class="card-meta d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                      <span>@lang('Articles'): <strong class="text-danger">{{ $quotation->sum }}</strong></span>
                    </div>
                    <span class="text-small">{{ $quotation->date }}</span>
                  </div>
                </div>

              </div>

          </div>
        </div>
      </div>
    @endforeach

  </div>
</div>


@push('after-scripts')

<script type="text/javascript">
  
/* chart.js chart examples */

// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];



/* bar chart */
var chBar = document.getElementById("chBar");
if (chBar) {
  new Chart(chBar, {
  type: 'bar',
  data: {
    labels: ["S", "M", "T", "W", "T", "F", "S"],
    datasets: [{
      data: [589, 445, 483, 503, 689, 692, 634],
      backgroundColor: colors[0]
    },
    {
      data: [639, 465, 493, 478, 589, 632, 674],
      backgroundColor: colors[1]
    }]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        barPercentage: 0.4,
        categoryPercentage: 0.5
      }]
    }
  }
  });
}

/* 3 donut charts */
var donutOptions = {
  cutoutPercentage: 85, 
  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
};

// donut 1
var chDonutData1 = {
    labels: ['Corte', 'Confeccion', 'Personalizacion'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [74, 11, 40]
      }
    ]
};

var chDonut1 = document.getElementById("chDonut1");
if (chDonut1) {
  new Chart(chDonut1, {
      type: 'pie',
      data: chDonutData1,
      options: donutOptions
  });
}

// donut 2
var chDonutData2 = {
    labels: ['Corte', 'Confeccion', 'Personalizacion'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [40, 45, 30]
      }
    ]
};
var chDonut2 = document.getElementById("chDonut2");
if (chDonut2) {
  new Chart(chDonut2, {
      type: 'pie',
      data: chDonutData2,
      options: donutOptions
  });
}

// donut 3
var chDonutData3 = {
    labels: ['Corte', 'Confeccion', 'Personalizacion', 'Confeccion', 'Personalizacion'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [21, 45, 55, 33, 44, 100]
      }
    ]
};
var chDonut3 = document.getElementById("chDonut3");
if (chDonut3) {
  new Chart(chDonut3, {
      type: 'pie',
      data: chDonutData3,
      options: donutOptions
  });
}

/* 3 line charts */
var lineOptions = {
    legend:{display:false},
    tooltips:{interest:false,bodyFontSize:11,titleFontSize:11},
    scales:{
        xAxes:[
            {
                ticks:{
                    display:false
                },
                gridLines: {
                    display:false,
                    drawBorder:false
                }
            }
        ],
        yAxes:[{display:false}]
    },
    layout: {
        padding: {
            left: 6,
            right: 6,
            top: 4,
            bottom: 6
        }
    }
};

var chLine1 = document.getElementById("chLine1");
if (chLine1) {
  new Chart(chLine1, {
      type: 'line',
      data: {
          labels: ['Jan','Feb','Mar','Apr','May'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [10, 11, 4, 11, 4],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
var chLine2 = document.getElementById("chLine2");
if (chLine2) {
  new Chart(chLine2, {
      type: 'line',
      data: {
          labels: ['A','B','C','D','E'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [4, 5, 7, 13, 12],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

var chLine3 = document.getElementById("chLine3");
if (chLine3) {
  new Chart(chLine3, {
      type: 'line',
      data: {
          labels: ['Pos','Neg','Nue','Other','Unknown'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [13, 15, 10, 9, 14],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

</script>
@endpush