<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>{{ $order->request ?? '' }} {{ $order->comment ?? '' }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  {{-- <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> --}}
  
  {{-- <style type="text/css">
    body {
      font-family: 'Karla', sans-serif !important;
    }
  </style> --}}

</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb10">

          <div class="cs-invoice_left" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo2.svg') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">{{ $order->type_order_clear }} No:</b> #{{ $order->characters_type_order }}{!! $order->folio_or_id !!}</p>
          </div>
          <div class="cs-invoice_right cs-text_center">
            <b class="cs-primary_color cs-f16">{{ __(appName()) }}</b>
            <p>
              {{ setting('site_address') }} <br/>
              {{ setting('site_email') }} <br/>
              {{ setting('site_whatsapp') }}
            </p>
          </div>

          <div class="cs-invoice_right cs-text_right" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/bacapro.png') }}" width="130" alt="Logo"></div>
          </div>

        </div>

        <div class="cs-style1 cs-f18 cs-primary_color cs-mb10 cs-semi_bold">@lang('Customer Information')</div>
        <ul class="cs-grid_row cs-col_3 cs-mb5">
          <li>
            <p class="cs-mb10"><b class="cs-primary_color">@lang('Customer'):</b> <br><span class="cs-primary_color">{{ optional($order->user)->name . optional($order->departament)->name }}</span></p>
          </li>
          <li>
            @if(optional($order->user)->customer)
              @if(optional($order->user)->customer['phone'])
                <p class="cs-mb10"><b class="cs-primary_color">@lang('Phone'):</b> <br><span class="cs-primary_color">{!! optional($order->user)->customer['phone'] ?? '' !!}</span></p>
              @endif
            @endif

            @if(optional($order->user)->customer)
              @if(optional($order->user)->customer['address'])
                <p class="cs-mb10"><b class="cs-primary_color">@lang('Address'):</b> <br><span class="cs-primary_color">{!! optional($order->user)->customer['address'] ?? '' !!}</span></p>
              @endif
            @endif
          </li>
          @if(optional($order->user)->customer)
            @if(optional($order->user)->customer['rfc'])
            <li>
              <p class="cs-mb10"><b class="cs-primary_color">@lang('RFC'):</b> <br><span class="cs-primary_color">{!! optional($order->user)->customer['rfc'] ?? '' !!}</span></p>
            </li>
            @endif
          @endif

        </ul>

        <div class="cs-invoice_head">
          <div class="cs-invoice_right">
            @if($order->info_customer)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Info customer'):</b> <br>{{ $order->info_customer }}</p>
            @endif
          </div>
        </div>

        @if($order->comment)
          <div class="cs-invoice_head">
            <div class="cs-invoice_right">
              <b class="cs-primary_color">@lang('Comment'):</b>
              <p class="cs-mb8">{{ $order->comment ?? '--'}}</p>
            </div>
          </div>
        @endif

        <div class="cs-invoice_head">
          <div class="cs-invoice_right cs-text_center">
            <p><b class="cs-primary_color cs-semi_bold">@lang('Date Issued'): <br>{{ $order->date_entered_or_created }}</p></b>
          </div>
          @if(!$order->isQuotation() && $order->quotation !== 0)
            <div class="cs-invoice_right cs-text_center">
                <p><b class="cs-primary_color cs-semi_bold">@lang('Quotation'): <br>{{ $order->quotation }}</p></b>
            </div>
          @endif
          <div class="cs-invoice_right cs-text_center">
            @if($order->request)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Request number'): <br>{{ $order->request ?? '' }}</p></b>
            @endif
          </div>
          <div class="cs-invoice_right cs-text_center">
            @if($order->purchase)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Purchase order'): <br>{{ $order->purchase ?? '' }}</p></b>
            @endif
          </div>
          @if($order->invoice)
            <div class="cs-invoice_right cs-text_center">
                <p><b class="cs-primary_color cs-semi_bold">@lang('Invoice'):<br>{{ $order->invoice ?? '' }}</p></b>
            </div>
          @endif
        </div>

        @if($grouped)
          <div class="cs-table cs-style2">
            <div class="cs-round_border">
              <div class="cs-table_responsive">
                <table>
                  <thead>
                    <tr class="cs-focus_bg">
                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">@lang('Quantity')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color">@lang('Code')</th>
                      <th class="cs-width_6 cs-semi_bold cs-primary_color">@lang('Description')</th>
                      <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Price')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">@lang('Total')</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orderGroup as $product)
                      @if($product->product_name != null && $product->sum != null)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->sum }}</td>
                        <td class="cs-width_2">{{ $product->product_code ?? '--' }}</td>
                        <td class="cs-width_6">  {{ $product->product_name }} - {{ $product->color_name }} {!! '<strong>'.$product->brand_name.'</strong>' !!}</td>
                        <td class="cs-width_1 cs-text_center cs-primary_color">
                          @if($product->omg)
                            ${{ priceWithoutIvaIncluded($product->min_price) }}
                            -
                          @endif
                            ${{ priceWithoutIvaIncluded($product->max_price) }}
                        </td>
                        <td class="cs-width_2 cs-text_right cs-primary_color">${{ priceWithoutIvaIncluded($product->sum_total) }}</td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          <div class="cs-table cs-style2">
            <div class="cs-round_border">
              <div class="cs-table_responsive">
                <table>
                  <thead>
                    <tr class="cs-focus_bg">
                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">@lang('Quantity')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color">@lang('Code')</th>
                      <th class="{{ !$emptyPrices ? 'cs-width_9' : 'cs-width_7' }} cs-semi_bold cs-primary_color">@lang('Description')</th>
                      @if(!$order->isOutputProducts() && !$emptyPrices)
                        <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Price')</th>
                        <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_right">@lang('Total')</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @php($total = 0)

                    @foreach($order->product_suborder->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->quantity }}</td>
                        <td class="cs-width_2">{{ $product->product->code_subproduct_clear }}</td>
                        <td class="{{ !$emptyPrices ? 'cs-width_9' : 'cs-width_6' }}">
                          
                          {{ $product->product->only_name }}
                          <div class="small text-muted"> {!! $product->product->only_parameters !!} </div>
                          {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                        </td>
                        @if(!$emptyPrices)
                          <td class="cs-width_1 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($product->price) : $product->price }}</td>
                          <td class="cs-width_2 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($totalprod = $product->price * $product->quantity) : $totalprod = $product->price * $product->quantity }}</td>
                        @endif
                      </tr>
                      @if($product->comment)
                        <tr>
                          <td>
                          </td>
                          <td style="text-align: left;" colspan="4">
                            <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                            {{ $product->comment }}
                          </td>
                        </tr>
                      @endif
                    @php($total += $totalprod)
                    @endforeach

                    @foreach($order->product_request->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->quantity }}</td>
                        <td class="cs-width_2">{{ $product->product->code_subproduct_clear }}</td>
                        <td class="{{ !$emptyPrices ? 'cs-width_9' : 'cs-width_6' }}">
                          {{ $product->product->only_name }}
                          <div class="small text-muted"> {!! $product->product->only_parameters !!} </div>
                          {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}

                        </td>
                        @if(!$emptyPrices)
                          <td class="cs-width_1 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($product->price) : $product->price }}</td>
                          <td class="cs-width_2 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($totalprod = $product->price * $product->quantity) : $totalprod = $product->price * $product->quantity }}</td>
                        @endif
                      </tr>
                      @if($product->comment)
                        <tr>
                          <td>
                          </td>
                          <td style="text-align: left;" colspan="4">
                            <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                            {{ $product->comment }}
                          </td>
                        </tr>
                      @endif
                    @endforeach

                    @foreach($order->product_order->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->quantity }}</td>
                        <td class="cs-width_2">{{ $product->product->code_subproduct_clear }}</td>
                        <td class="{{ !$emptyPrices ? 'cs-width_9' : 'cs-width_6' }}">
                          {{ $product->product->only_name }}
                          <div class="small text-muted"> {!! $product->product->only_parameters !!} </div>
                          {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                        </td>
                        @if(!$emptyPrices)
                          <td class="cs-width_1 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($product->price) : $product->price }}</td>
                          <td class="cs-width_2 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($totalprod = $product->price * $product->quantity) : $totalprod = $product->price * $product->quantity }}</td>
                        @endif
                      </tr>
                      @if($product->comment)
                        <tr>
                          <td>
                          </td>
                          <td style="text-align: left;" colspan="4">
                            <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                            {{ $product->comment }}
                          </td>
                        </tr>
                      @endif
                    @endforeach

                    @foreach($order->product_sale->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->quantity }}</td>
                        <td class="cs-width_2">{{ $product->product->code_subproduct_clear }}</td>
                        <td class="{{ !$emptyPrices ? 'cs-width_9' : 'cs-width_6' }}">
                          {{ $product->product->only_name }}
                          <div class="small text-muted"> {!! $product->product->only_parameters !!} </div>
                          {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                        </td>
                        @if(!$emptyPrices)
                          <td class="cs-width_1 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($product->price) : $product->price }}</td>
                          <td class="cs-width_2 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($totalprod = $product->price * $product->quantity) : $totalprod = $product->price * $product->quantity }}</td>
                        @endif
                      </tr>
                      @if($product->comment)
                        <tr>
                          <td>
                          </td>
                          <td style="text-align: left;" colspan="4">
                            <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                            {{ $product->comment }}
                          </td>
                        </tr>
                      @endif
                    @endforeach

                    @foreach($order->product_quotation->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->quantity }}</td>
                        <td class="cs-width_2">{{ $product->product->code_subproduct_clear }}</td>
                        <td class="cs-width_6">
                           {{ $product->product->only_name }}
                          <div class="small text-muted"> {!! $product->product->only_parameters !!} </div>
                          {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                        </td>
                        <td class="cs-width_1 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($product->price) : $product->price }}</td>
                        <td class="cs-width_2 cs-text_right cs-primary_color">${{ !$breakdown ? priceWithoutIvaIncluded($totalprod = $product->price * $product->quantity) : $totalprod = $product->price * $product->quantity }}</td>
                      </tr>
                      @if($product->comment)
                        <tr>
                          <td>
                          </td>
                          <td style="text-align: left;" colspan="4">
                            <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                            {{ $product->comment }}
                          </td>
                        </tr>
                      @endif
                    @endforeach

                    @foreach($order->product_output->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->quantity }}</td>
                        <td class="cs-width_2">{{ $product->product->code_subproduct_clear }}</td>
                        <td class="cs-width_6">
                          {{ $product->product->only_name }}
                          <div class="small text-muted"> {!! $product->product->only_parameters !!} </div>
                          {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                        </td>
                      </tr>
                      @if($product->comment)
                        <tr>
                          <td>
                          </td>
                          <td style="text-align: left;" colspan="4">
                            <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                            {{ $product->comment }}
                          </td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif

        <div style="page-break-inside:avoid;">
          <div class="cs-table cs-style2">
            <div class="cs-table_responsive">
              <table>
                <tbody>
                  <tr class="cs-table_baseline">
                    <td class="cs-width_5">
                      {!! $order->total_products_and_services_label !!}
                    </td>
                    @if(!$order->isOutputProducts() && !$emptyPrices)
                    <td class="cs-width_5 cs-text_right">
                      @if(!$breakdown)
                        <p class="cs-primary_color cs-bold cs-f14 cs-m0">@lang('Subtotal'):</p>
                      @endif
                      @if($order->discount)
                        <p class="cs-primary_color cs-bold cs-f14 cs-m0">@lang('Discount'):</p>
                      @endif
                      @if(!$breakdown)
                        <p class="cs-mb5 cs-mb5 cs-f14 cs-primary_color cs-semi_bold">IVA:</p>
                      @endif
                      <p class="cs-primary_color cs-bold cs-f14 cs-m0">@lang('Total'):</p>
                    </td>
                    <td class="cs-width_2 cs-text_rightcs-f14">
                      @if(!$breakdown)
                        <p class="cs-primary_color cs-bold cs-f14 cs-m0 cs-text_right">${{ count($order->product_suborder) ? '--' : number_format($order->subtotal_by_all, 2)  }}</p>
                      @endif
                      @if($order->discount)
                        <p class="cs-mb5 cs-mb5 cs-text_right cs-f14 cs-primary_color cs-semi_bold">
                          @if(!$breakdown)
                            ${{ number_format($order->calculate_discount_all, 2)}}
                          @else
                            %{{ $order->discount }}
                          @endif
                        </p>
                      @endif
                      @if(!$breakdown)
                        <p class="cs-mb5 cs-mb5 cs-text_right cs-f14 cs-primary_color cs-semi_bold">${{ count($order->product_suborder) ? '--' : calculateIva($order->subtotal_less_discount) }}</p>
                      @endif
                      <p class="cs-primary_color cs-bold cs-f14 cs-m0 cs-text_right">${{ number_format(count($order->product_suborder) ? $total : $order->total_by_all_with_discount, 2) }}</p>
                    </td>
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          @if($order->audi_id)
            <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Captured by'):</b> {{ $order->audi->name }}</p>
          @endif



          @if($order->orders_payments()->count())
          <br>
          <div class="cs-table cs-style2">
              <div>
                  <div class="cs-table_responsive">
                      <table>
                          <thead>
                              <tr class="cs-focus_bg">
                                  <th class="cs-width_3 cs-semi_bold cs-primary_color">@lang('Payment method')</th>
                                  <th class="cs-width_2 cs-semi_bold cs-primary_color">@lang('Amount')</th>
                                  <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_center">@lang('Comment')</th>
                                  <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_right ">@lang('Created at')</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($order->orders_payments()->get() as $record)
                              <tr>
                                  <td>{{ $record->payment_method }}</td>
                                  <td>${{ $record->amount }}</td>
                                  <td class="cs-text_center">{{ $record->comment }}</td>
                                  <td class="cs-text_right cs-primary_color">{{ $record->created_at }}0</td>
                              </tr>
                              @endforeach
                                <tr class="cs-focus_bg">
                                    <td class="cs-bold cs-primary_color cs-text_center">Total:</td>
                                    <td class="cs-bold cs-primary_color">${{ number_format((float)$order->total_payments, 2) }}</td>
                                    <td>Pendiente -> <strong style="color:red;">${{  number_format((float)$order->total_payments_remaining, 2) }}</strong></td>
                                    <td class="cs-bold cs-primary_color cs-text_center"></td>
                                </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          @endif


          <div class="cs-note">
            {!! QrCode::size(80)->eye('circle')->generate(route('frontend.track.show', $order->slug)); !!}
            <div class="cs-note_right" style="margin-left: 20px;">
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'):</b></p>
              <p class="cs-m0">
                {!! $order->notes ? $order->notes.'<br>' : '' !!}

                {!! $order->isQuotation() && ($order->branch_id > 0) ? setting('footer_quotation').'<br>' :'' !!} {{ $order->branch_id > 0 ?  setting('footer') : '' }}


                {!! $order->isQuotation() && ($order->branch_id == 0) ? setting('footer_quotation_production').'<br>' :'' !!}
              </p>
            </div>
          </div><!-- .cs-note -->

          @if($order->from_store)
            <div class="cs-invoice_right cs-text_center">
              <div class="cs-note_right" style="margin-left: 20px; margin-top: 20px;">
                <p class="cs-mb0 cs-text_center">
                  <b class="cs-primary_color cs-bold ">
                    ________________________________________________________<br>
                    Recibo de conformidad
                  </b>
                  <br>
                  @if($order->user_id)
                    <strong>{{ optional($order->user)->name . optional($order->departament)->name }}</strong>
                  @endif
                </p>
              </div>
            </div><!-- .cs-note -->
          @endif
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