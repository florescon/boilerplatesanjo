<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>#{{ $productionBatch->id }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">

        <div class="cs-invoice_head cs-type1 cs-mb10">

          <div class="cs-invoice_left" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo2.svg') }}" width="100" alt="Logo"></div>
          </div>
          <div class="cs-invoice_right cs-text_center cs-f16">
            <h4 style="padding: 10px;">
              <strong>@lang('Output') No. #{{ $productionBatch->firstFolioMoreOutput() }}</strong>
            </h4>
            <p style="margin-top: -20px;">
              <strong>@lang('Date'): </strong> {{ $productionBatch->date_for_humans }}
            </p>
          </div>

          <div class="cs-invoice_right cs-text_right" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/bacapro.png') }}" width="130" alt="Logo"></div>
          </div>

        </div>


        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left50">
            <b class="cs-primary_color">@lang('Customer Information'):</b>
            <p>
              {!! $productionBatch->order->user_name !!} <br>
              {!! $productionBatch->order->user_details !!}<br>

              {!! $productionBatch->order->info_customer_break !!}
              {!! $productionBatch->order->complementary_break !!}
              {!! $productionBatch->order->notes_break !!}
              {!! $productionBatch->order->observation_break !!}
              {!! $productionBatch->order->comment_break !!}

            </p>
          </div>
          <div class="cs-invoice_right cs-text_right">
            <b class="cs-primary_color">{{ __(appName()) }}</b>
            <p>
              {{ setting('site_address') }} <br/>
              {{ setting('site_email') }} <br/>
              {{ setting('site_whatsapp') }}
            </p>
          </div>
        </div>
        @if($productionBatch->notes)
          <div class="cs-invoice_head cs-mb10">
            <p>
              Comentario: <b class="cs-primary_color">{{ $productionBatch->notes }}</b>
            </p>
          </div>
        @endif
        <ul class="cs-list cs-style2">
          <li>
            <div class="cs-list_left">@lang('Quotation'): <b class="cs-primary_color cs-semi_bold "> #{{ $productionBatch->order->quotation ?? '--'  }}</b></div>
            <div class="cs-list_right">@lang('Request number'): <b class="cs-primary_color cs-semi_bold ">{{ $productionBatch->order->request ?? '--'  }}</b></div>
          </li>
          <li>
            <div class="cs-list_left">@lang('Purchase Order'): <b class="cs-primary_color cs-semi_bold ">{{ $productionBatch->order->purchase ?? '--'  }}</b></div>
            <div class="cs-list_right">@lang('Invoice'): <b class="cs-primary_color cs-semi_bold ">{{ $productionBatch->invoice ?? '--' }}</b></div>
          </li>
          <li>
            <div class="cs-list_left">@lang('Invoice date'): <b class="cs-primary_color cs-semi_bold ">{{ $productionBatch->invoice_date_format }}</b></div>
            <div class="cs-list_right">Expedido por: <b class="cs-primary_color cs-semi_bold ">{{ optional($productionBatch->audi)->name ?? '--'  }}</b></div>
          </li>
        </ul>
        <div class="cs-table cs-style2">
          <div class="">
            <div class="cs-table_responsive">
              <table>
                <thead>
                  <tr class="cs-focus_bg">
                    <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">@lang('Quantity')</th>
                    <th class="cs-width_6 cs-semi_bold cs-primary_color">@lang('Description')</th>
                    <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_right">@lang('Price')</th>
                    <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">@lang('Total')</th>
                  </tr>
                </thead>
                <tbody>
                  @php($total = 0)
                  @php($sum = 0)

            @if($grouped)
                @foreach($orderGroup as $product)
                  @if($product->product_name != null)

                  <tr>
                    <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->sum }}</td>
                    <td class="cs-width_6">{{ $product->product_code ?? '--' }}{!! '<strong> '.$product->brand_name.'</strong>' !!} {{ $product->product_name }} - {{ $product->color_name }}</td>
                    <td class="cs-width_3 cs-text_right">
                      
                          @if($product->omg <> 0)
                            ${{ priceWithoutIvaIncluded($product->min_price) }}
                            -
                          @endif
                            ${{ priceWithoutIvaIncluded($product->max_price) }}

                    </td>
                    @php($toSum = $product->sum_total)

                    @php($sum += $toSum)
                    
                    <td class="cs-width_2 cs-text_right cs-primary_color">${{ priceWithoutIvaIncluded($product->sum_total) }}</td>
                  </tr>
                  @endif

                @endforeach

{{--                   @foreach($productionBatch->items->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $assignUngrouped)
                      @php($totalProd = $assignUngrouped->productOrder->price * $assignUngrouped->output_quantity)

                      @php($total += $totalProd)
                  @endforeach
 --}}
            @else

                  @foreach($productionBatch->items->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $assign)
                    <tr>
                      <td class="cs-width_1 cs-text_center cs-accent_color">{{ $assign->output_quantity }}</td>
                      <td class="cs-width_7">{!! $assign->product->full_name_break !!}</td>
                      <td class="cs-width_2 cs-text_right cs-primary_color">${{ number_format(priceWithoutIvaIncluded($assign->price), 2) }}</td>
                      <td class="cs-width_2 cs-text_right cs-primary_color">${{ number_format(priceWithoutIvaIncluded($assign->price * $assign->output_quantity), 2) }}</td>
                    </tr>

                  @php($totalProd = $assign->product_order->price * $assign->output_quantity)

                  @php($total += $totalProd)
                  @endforeach
          @endif

                  <tr class="cs-no_border cs-table_baseline">
                    <td class="cs-width_10 cs-text_right cs-primary_color cs-semi_bold" colspan="3">Subtotal:</td>
                    <td class="cs-width_2 cs-text_right cs-primary_color cs-semi_bold">${{ number_format(priceWithoutIvaIncluded($sum), 2) }}</td>
                  </tr>
                  <tr class="cs-no_border cs-table_baseline">
                    <td class="cs-width_10 cs-text_right cs-primary_color cs-semi_bold" colspan="3">IVA:</td>
                    <td class="cs-width_2 cs-text_right cs-primary_color cs-semi_bold">${{ number_format(ivaPrice($sum), 2) }}</td>
                  </tr>
                  <tr class="cs-focus_bg cs-no_border">
                    <td class="cs-width_1"><b class="cs-primary_color">@lang('Articles'):</b> <br>{{ $productionBatch->total_products_prod_output }}</td>
                    <td class="cs-width_7 cs-text_right cs-primary_color cs-bold cs-f16"></td>
                    <td class="cs-width_2 cs-text_right cs-primary_color cs-bold cs-f16">Total</td>
                    <td class="cs-width_2 cs-text_right cs-primary_color cs-bold cs-f16">${{ number_format($sum, 2) }}</td>
                  </tr>
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