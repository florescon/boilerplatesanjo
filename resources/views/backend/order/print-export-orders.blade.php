<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>@lang('Export orders by products') {{ now() }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">
</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_btns cs-hide_print">
   
        <a href="{{ route('admin.order.index') }}" class="cs-invoice_btn cs-color1">
          <
          <span>&nbsp; @lang('Back')</span>
        </a>

        <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>@lang('Print')</span>
        </a>
      </div>

      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="cs-invoice_left">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo22.png') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('Orders') No:</b> {{ $orders }}</p>
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
                  <span class="badge badge-warning ml-1 mr-1 mt-1" style="font-size: 1rem;">#{{ $order['id'].' => '.$order['comment'].'; ' ?? '' }}</span>
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
        
        @php($totalAll = 0)

        @foreach($products as $key => $product)
          @foreach($product as $key2 => $parentProduct)

          <strong>{{ $key2 }}</strong>

          <div class="cs-table cs-style cs-mb10">
            <div class="cs-round_border">
              <div class="cs-table_responsive">
                <table>
                  <thead>
                    <tr class="cs-accent_10_bg">
                      <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_center"># - @lang('Customer')</th>
                      @foreach($parentProduct->unique('productSize')->sortBy('productSizeSort') as $app)
                        <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">{{ $app['productSizeName'] }}</th>
                      @endforeach
                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">@lang('Total')</th>
                    </tr>
                  </thead>
                  <tbody>

                    @php($totalAllByProduct = 0)

                    @foreach($parentProduct->groupBy('productOrder') as $keyp => $pp)

                      <tr>
                        <td class="cs-width_3 cs-text_center cs-accent_color">{!! '#'.$pp[0]['productOrder'].' - '.$pp[0]['customer'] !!}</td>

                          @php($totalRow = 0)

                          @foreach($parentProduct->unique('productSize')->sortBy('productSizeSort') as $keyn => $app)
                            <td class="cs-width_1 cs-primary_color cs-text_center">

                              @php($totalByProduct = 0)

                              {{-- @php($ss = $pp->pluck('productQuantity', 'productSize')) --}}

                              @foreach($pp as $key => $si)

                                @if($si['productSize'] === $app['productSize'])

                                  @php($totalByProduct += $si['productQuantity'])

                                @endif

                              @endforeach

                              <em>{{ $totalByProduct ?: '-' }}</em>

                              @php($totalRow += $totalByProduct)

                            </td>
                          @endforeach

                          <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center cs-focus_bg">
                            {{ $totalRow }}
                            @php($totalAllByProduct += $totalRow)
                          </th>
                      </tr>
                    @endforeach
                    <tr class="cs-focus_bg">
                      <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_center"></th>
                      @foreach($parentProduct->unique('productSize')->sortBy('productSizeSort') as $app)
                        <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">

                          @php($totalBySize = 0)

                          @foreach($parentProduct as $pro)
                            @if($pro['productSizeName'] === $app['productSizeName'])
                              @php($totalBySize += $pro['productQuantity'])
                            @endif
                          @endforeach

                          {{ $totalBySize }}

                        </th>
                      @endforeach
                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">
                        <u>{{ $totalAllByProduct }}</u>
                        @php($totalAll += $totalAllByProduct)
                      </th>
                    </tr>
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
                      {{-- <b class="cs-primary_color">@lang('Articles')</b> --}}
                      <br/>
                      --
                    </td>
                    <td class="cs-width_5 cs-text_right">
                      <p class="cs-primary_color cs-bold cs-f16 cs-m0">@lang('Total'):</p>
                    </td>
                    <td class="cs-width_2 cs-text_rightcs-f16">
                      <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_center"><kbd>{{ $totalAll }}</kbd> </p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="cs-note">
            <div class="cs-note_right" style="margin-left: 20px;">
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'): --</b></p>
            </div>
          </div><!-- .cs-note -->
        </div>
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