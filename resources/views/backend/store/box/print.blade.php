<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="ThemeMarch">
  <!-- Site Title -->
  <title>@lang('Daily cash closing') #{{ $box->id }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="cs-invoice_left">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo22.png') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('Daily cash closing') No:</b> #{{ $box->id }}</p>
          </div>
          <div class="cs-invoice_right cs-text_right">
            <b class="cs-primary_color">{{ __(appName()) }}</b>
            <p>
              Sucursal principal <br/>
              Margarito González Rubio #822, C.P. 47470 <br/>
            </p>          </div>
        </div>
        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left">
            <b class="cs-primary_color">@lang('Title'):</b>
            <p class="cs-mb8">{{ $box->title ?? '--'}}</p>

            @if($box->comment)
              <b class="cs-primary_color">@lang('Comment'):</b>
              <p class="cs-mb8">{{ $box->comment ?? '--'}}</p>
            @endif

            <p><b class="cs-primary_color cs-semi_bold">@lang('Date Issued'):</b> <br>{{ $box->created_at }}</p>

            <b class="cs-primary_color">@lang('Created by'):</b>
            <p class="cs-mb8">{{ optional($box->audi)->name ?? '--'}}</p>

          </div>
          <div class="cs-invoice_right cs-text_right">
            <div class="cs-invoice_right">
              <ul class="cs-bar_list">
                <li><b class="cs-primary_color">Inicial:</b> ${{ $box->initial }}</li>
                <li><b class="cs-primary_color">Ingresos:</b> ${{ number_format($box->amount_incomes, 2) }}</li>
                <li><b class="cs-primary_color">Egresos:</b> {{ $box->amount_expenses != 0 ? '-$'. number_format($box->amount_expenses, 2) : '--' }}</li>
                <li><b class="cs-primary_color">Balance:</b> ${{ $box->daily_cash_closing }}</li>
              </ul>
            </div>
            <div class="cs-invoice_right">
              <br>
              <ul class="cs-bar_list">
                <li><b class="cs-primary_color">Efectivo:</b> ${{ $box->total_amount_cash_finances }}</li>
                <li><b class="cs-primary_color">Efectivo P/F:</b> ${{ $box->total_amount_cash_finances_invoice }}</li>
                <li><b class="cs-primary_color">Otros:</b> ${{ $box->total_amount_cash_different_finances }}</li>
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
                  @foreach($box->finances as $finance)
                    <tr>
                      <th scope="row" class="cs-width_1">#{{ $finance->id }}</th>
                      <td>{{ $finance->name }}</td>
                      <td>{!! '<ins>'.$finance->payment_method.' '.$finance->is_invoice.'</ins>' ?: '--' !!} {!! $finance->details !!}</td>
                      <td class="cs-width_1">
                        {{ $finance->finance_sign }}${{ $finance->amount }}
                      </td>
                    </tr>
                  @endforeach
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
                </tbody>
              </table>

            </div>
          </div>
        </div>

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
  <script src="{{ asset('/js_custom/app-invoice-print.js') }}"></script>

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

</body>
</html>