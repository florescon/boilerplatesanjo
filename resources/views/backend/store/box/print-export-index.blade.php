<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="ThemeMarch">
  <!-- Site Title -->
  <title>@lang('Daily cash closing') #</title>
  <link href="{{ mix('css/backend.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />

</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">

        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="cs-invoice_left">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo22.png') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('Daily cash closing') No:</b> 
              @if($json_decode)
                @foreach($json_decode as $js)
                  <em class="cs-accent_color">#{{ $js }}</em>
                @endforeach
              @endif
            </p>
          </div>
          <div class="cs-invoice_right cs-text_right">
            <b class="cs-primary_color">{{ __(appName()) }}</b>
            <p>
              Sucursal principal <br/>
              Margarito González Rubio #822, C.P. 47470 <br/>
            </p>          
          </div>
        </div>
        <div class="cs-invoice_head cs-mb10">
          <div class="row py-2">
              <div class="col-md-6 py-1">
                  <div class="card">
                      <div class="card-body">
                          <canvas id="chDonut1"></canvas>
                          <ul class="cs-bar_list">
                            <li><b class="cs-primary_color">Ingresos:</b> ${{ $totalIncomes }}</li>
                            <li><b class="cs-primary_color">Egresos:</b> {{ '-$'.$totalExpenses }}</li>
                            <li><b class="cs-primary_color">Balance:</b> ${{ $totalIncomes - $totalExpenses }}</li>
                          </ul>

                      </div>
                  </div>
              </div>
              <div class="col-md-6 py-1">
                  <div class="card">
                      <div class="card-body">
                          <canvas id="chDonut2"></canvas>
                          <strong>De los ingresos:</strong>
                          <ul class="cs-bar_list">
                            <li><b class="cs-primary_color">Efectivo:</b> ${{ $totalCash }}</li>
                            <li><b class="cs-primary_color">Otros:</b> ${{ $totalAnotherPayment }}</li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>



          <div class="cs-invoice_right cs-text_right">
            <div class="cs-invoice_right">
              <ul class="cs-bar_list">
                {{-- <li><b class="cs-primary_color">Ingresos:</b> ${{ number_format($boxes->amount_incomes, 2) }}</li> --}}
                {{-- <li><b class="cs-primary_color">Egresos:</b> {{ $boxes->amount_expenses != 0 ? '-$'. number_format($box->amount_expenses, 2) : '--' }}</li> --}}
                {{-- <li><b class="cs-primary_color">Balance:</b> ${{ $boxes->daily_cash_closing }}</li> --}}
              </ul>
            </div>
            <div class="cs-invoice_right">
              <br>
              <ul class="cs-bar_list">
                {{-- <li><b class="cs-primary_color">Efectivo:</b> ${{ $boxes->total_amount_cash_finances }}</li> --}}
                {{-- <li><b class="cs-primary_color">Otros:</b> ${{ $boxes->total_amount_cash_different_finances }}</li> --}}
              </ul>
            </div>
          </div>
        </div>

        <div class="cs-note">
          <div class="cs-note_right">
            <p class="cs-mb2"><b class="cs-primary_color cs-bold">Flujo de efectivo:</b></p>
          </div>
        </div><!-- .cs-note -->

        <div class="cs-table cs-style2">
          <div class="cs-round_border">
            <div class="cs-table_responsive">

              <table class="table table-sm">
                <thead>
                  <tr class="cs-focus_bg">
                    <th class="cs-width_1">#</th>
                    <th >@lang('Name')</th>
                    <th >@lang('Details')</th>
                    <th class="cs-width_1">@lang('Quantity')</th>
                  </tr>
                </thead>
                <tbody>
                  @if($boxes)
                    @foreach($boxes as $box)
                        <tr >
                          <th colspan="4" class="cs-text_center cs-accent_color">@lang('Daily cash closing') #{{ $box->id }}</th>
                        </tr>
                      @foreach($box->finances as $finance)
                        <tr>
                          <th scope="row" class="cs-width_1">#{{ $finance->id }}</th>
                          <td>{{ $finance->name }}</td>
                          <td>{!! '<ins>'.$finance->payment_method.'</ins>' ?: '--' !!} {!! $finance->details !!}</td>
                          <td class="cs-width_1">
                            {{ $finance->finance_sign }}${{ $finance->amount }}
                          </td>
                        </tr>
                      @endforeach
                    @endforeach
                  @endif
                </tbody>
              </table>

            </div>
          </div>
        </div>

        <div class="cs-note">
          <div class="cs-note_right">
            <p class="cs-mb2"><b class="cs-primary_color cs-bold">Movimientos:</b></p>
          </div>
        </div><!-- .cs-note -->

        <div class="cs-table cs-style2">
          <div class="cs-round_border">
            <div class="cs-table_responsive">

              <table class="table table-sm">
                <thead>
                  <tr class="cs-focus_bg">
                    <th class="cs-width_1">#</th>
                    <th >@lang('User')</th>
                    <th >@lang('Comment')</th>
                    <th >@lang('Type')</th>
                  </tr>
                </thead>
                <tbody>
                  @if($boxes)
                    @foreach($boxes as $box)
                        <tr >
                          <th colspan="4" class="cs-text_center cs-accent_color_second">@lang('Daily cash closing') #{{ $box->id }}</th>
                        </tr>
                      @foreach($box->orders as $order)
                        <tr>
                          <th scope="row" class="cs-width_1">#{!! $order->folio_or_id !!}</th>
                          <td>{!! $order->user_name !!}</td>
                          <td>
                            {!! $order->details_for_box.' '.$order->comment ?: '--' !!} =>
                            @foreach($order->products as $product)
                              {!! optional($product->product)->full_name_clear_line.', <strong><u>'.$product->quantity.'</u></strong>;'  !!}
                            @endforeach
                          </td>
                          <td>
                            {{  $order->type_order_clear }}
                          </td>
                        </tr>
                      @endforeach
                    @endforeach
                  @endif
                </tbody>
              </table>

            </div>
          </div>
        </div>

        @php($incomes)

      </div>
      <div class="cs-invoice_btns cs-hide_print">
        <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>@lang('Print')</span>
        </a>
      </div>
    </div>
  </div>

  <script src="{{ asset('/js_custom/vendor.min.js') }}"></script>

  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14
          , height: 14
        });
      }
    })
  </script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>

  <script type="text/javascript">
    
  /* chart.js chart examples */

  // chart colors
  var colors = ['#007bff','red','#c3e6cb','#333333','#dc3545','#6c757d'];

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

  var totalIncomes = @json($totalIncomes);
  var totalExpenses = @json($totalExpenses);

  // donut 1
  var chDonutData1 = {
      labels: ['Ingresos', 'Egresos'],
      datasets: [
        {
          backgroundColor: colors.slice(0,3),
          borderWidth: 0,
          data: [totalIncomes, totalExpenses]
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

  var totalCash = @json($totalCash);
  var totalAnotherPayment = @json($totalAnotherPayment);

  // donut 2
  var chDonutData2 = {
      labels: ['Efectivo', 'Otros m. de pago'],
      datasets: [
        {
          backgroundColor: colors.slice(2,5),
          borderWidth: 0,
          data: [totalCash, totalAnotherPayment]
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
</body>
</html>