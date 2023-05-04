<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>Export Orders</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="cs-invoice_left">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo22.png') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">Orders No:</b> {{ $orders }}</p>
          </div>

          <div class="cs-invoice_right cs-text_right">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/bacapro.png') }}" width="130" alt="Logo"></div>
          </div>
        </div>

        {{-- @json($myCollection) --}}

        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left">
            <b class="cs-primary_color">@lang('About it'):</b>
            <p class="cs-mb8">
              
              @if($orderCollection)
                @lang('Orders'):
                @foreach($orderCollection as $order)
                  <span class="badge badge-warning ml-1 mr-1 mt-1" style="font-size: 1rem;">#{{ $order['id'].' => '.$order['user'].'; ' ?? '' }}</span>
                @endforeach
              @else
                <p>@lang('Nothing processed')</p>
              @endif

            </p>
          </div>
        </div>

        {{-- <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">@lang('Totales')</div>
        <div class="cs-table cs-style2">
          <div class="cs-round_border">
            <div class="cs-table_responsive">
              <table>
                <thead>
                  <tr class="cs-focus_bg">
                    <th class="cs-width_6 cs-semi_bold cs-primary_color cs-text_center">@lang('Description')</th>
                    <th class="cs-width_2 cs-semi_bold cs-primary_color">@lang('Code')</th>
                    <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Description')</th>
                    <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Quantity')</th>
                    <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">@lang('Total')</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($productsCollection as $product)
                    <tr>
                      <td class="cs-width_6 cs-text_center cs-accent_color">{{ $product['productName'] }}</td>
                      <td class="cs-width_2">{{ $product['productParentCode'] }}</td>
                      <td class="cs-width_1">Desc.</td>
                      <td class="cs-width_1 cs-text_center cs-primary_color">
                        {{ $product['productQuantity'] }}
                      </td>
                      <td class="cs-width_2 cs-text_right cs-primary_color"></td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div> --}}

        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">@lang('By product')</div>
        
        @foreach($products as $key => $product)
          @foreach($product as $key2 => $p)
          @lang('Product name')
          <div class="cs-table cs-style2 cs-mb10">
            <div class="cs-round_border">
              <div class="cs-table_responsive">
                <table>
                  <thead>
                    <tr class="cs-focus_bg">
                      <th class="cs-width_6 cs-semi_bold cs-primary_color cs-text_center">@lang('Description')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color">@lang('Code')</th>
                      <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Description')</th>
                      <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Quantity')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">@lang('Total')</th>
                    </tr>
                  </thead>
                  <tbody>
                        @foreach($p as $pp)
                        {{-- @json($pp) --}}
                        <tr>
                          <td class="cs-width_6 cs-text_center cs-accent_color">{{ $pp['productName'] }}</td>
                          <td class="cs-width_2">{{ $pp['productCode'] }}</td>
                          <td class="cs-width_1">{{ $pp['productColorName'].' '.$pp['productSize'].'=> '.$pp['productSizeName'] }}.</td>
                          <td class="cs-width_1 cs-text_center cs-primary_color">{{ $pp['productQuantity'] }}</td>
                          <td class="cs-width_2 cs-text_right cs-primary_color"></td>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endforeach
        @endforeach

        <div style="page-break-inside:avoid;">
          <div class="cs-table cs-style2">
            <div class="cs-table_responsive">
              <table>
                <tbody>
                  <tr class="cs-table_baseline">
                    <td class="cs-width_5">
                      <b class="cs-primary_color">@lang('Articles')</b><br/>
                      nn
                    </td>
                    <td class="cs-width_5 cs-text_right">
                      <p class="cs-primary_color cs-bold cs-f16 cs-m0">@lang('Total'):</p>
                    </td>
                    <td class="cs-width_2 cs-text_rightcs-f16">
                      <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">$</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="cs-note">
            <div class="cs-note_right" style="margin-left: 20px;">
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'):</b></p>
            </div>
          </div><!-- .cs-note -->
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