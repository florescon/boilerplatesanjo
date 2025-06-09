<div class="cs-container" style="{{ $width ? 'max-width: 2080px;' : '' }}">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_btns cs-hide_print mb-2">   

        <a href="{{ route('admin.dashboard') }}" class="cs-invoice_btn cs-color1" style="margin-right: 10px;">
          <span>&nbsp; @lang('Home')</span>
        </a>

        <button wire:click="$toggle('width')" class="cs-invoice_btn {{ !$width ? '' : 'cs-color1' }}">
          @if(!$width)
            @lang('Fullscreen')
          @else
            @lang('Exit Fullscreen')
          @endif
        </button>

        <a href="javascript:window.print()" class="cs-invoice_btn cs-color2">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>@lang('Print')</span>
        </a>
      </div>

      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb10">

          <div class="cs-invoice_left" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo2.svg') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f18"><b class="cs-primary_color">{{ $order->type_order_clear }} No:</b> #{{ $order->characters_type_order }}{!! $order->folio_or_id !!}</p>
          </div>
          <div class="cs-invoice_right cs-text_center">
            <b class="cs-primary_color cs-f18">{{ __(appName()) }}</b>
            <p class="cs-f18">
              {{ setting('site_address') }} <br/>
              {{ setting('site_email') }} <br/>
              {{ setting('site_whatsapp') }}
            </p>
          </div>

          <div class="cs-invoice_right cs-text_right" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/bacapro.png') }}" width="130" alt="Logo"></div>
          </div>

        </div>

        @if(!$width)
          <div class="alert alert-primary text-center cs-hide_print" role="alert">
            Si las columnas exceden el ancho de página, seleccione 'Pantalla Completa'
          </div>
        @endif

        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="legend">
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('File generated'):</b> 
              {{ now()->isoFormat('D, MMM, YY - h:mm a') }}
          </div>
          <div class="cs-invoice_right cs-text_right cs-hide_print">
            <button wire:click="$toggle('prices')" class="cs-invoice_btn {{ !$prices ? '' : 'cs-color1' }}">
              @if(!$prices)
                @lang('Prices')
              @else
                @lang('Without prices')
              @endif
            </button>

            <button wire:click="$toggle('general')" class="cs-invoice_btn {{ !$general ? '' : 'cs-color1' }}">
              @if(!$general)
                @lang('General')
              @else
                @lang('Sin General')
              @endif
            </button>

            <button wire:click="$toggle('details')" class="cs-invoice_btn {{ !$details ? '' : 'cs-color1' }}">
              @if(!$details)
                @lang('Detalles')
              @else
                @lang('Sin Detalles')
              @endif
            </button>

          </div>
        </div>

        <div class="cs-style1 cs-f18 cs-primary_color cs-mb10 cs-semi_bold">@lang('Customer Information')</div>
        <ul class="cs-grid_row cs-col_3 cs-f18 cs-mb5">
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

        <div class="cs-invoice_head cs-f18">
          <div class="cs-invoice_right">
            @if($order->info_customer)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Info customer'):</b> <br>{{ $order->info_customer }}</p>
            @endif
          </div>
        </div>

        @if($order->comment)
          <div class="cs-invoice_head cs-f18">
            <div class="cs-invoice_right">
              <b class="cs-primary_color">@lang('Comment'):</b>
              <p class="cs-mb8">{{ $order->comment ?? '--'}}</p>
            </div>
          </div>
        @endif

        <div class="cs-invoice_head cs-f18">
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

        @if($general)
        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold" style="margin-top: 20px;">@lang('General')
          <br><br>
          <div class="cs-table cs-style2">
            <div class="cs-round_border">
              <div class="cs-table_responsive">
                <table>
                  <thead>
                    <tr class="cs-focus_bg">
                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">@lang('Quantity')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color">@lang('Code')</th>
                      <th class="cs-width_6 cs-semi_bold cs-primary_color">@lang('Description')</th>
                      @if($prices)
                      <th class="cs-width_1 cs-semi_bold cs-primary_color">@lang('Price')</th>
                      <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">@lang('Total')</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orderGroup as $product)
                      @if($product->product_name != null && $product->sum != null)
                      <tr>
                        <td class="cs-width_1 cs-text_center cs-accent_color">{{ $product->sum }}</td>
                        <td class="cs-width_2">{{ $product->product_code ?? '--' }}</td>
                        <td class="cs-width_6"> {!! '<strong class="text-primary">'.$product->brand_name.'</strong>' !!} {{ $product->product_name }} - {{ $product->color_name }}</td>
                        @if($prices)
                        <td class="cs-width_1 cs-text_center cs-primary_color">
                          @if($product->omg)
                            ${{ priceWithoutIvaIncluded($product->min_price) }}
                            -
                          @endif
                            ${{ priceWithoutIvaIncluded($product->max_price) }}
                        </td>
                        <td class="cs-width_2 cs-text_right cs-primary_color">${{ priceWithoutIvaIncluded($product->sum_total) }}</td>
                        @endif
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="cs-table cs-style2">
            <div class="cs-table_responsive">
              <table class="table">
                <tbody>
                  <tr class="cs-table_baseline">
                    <td class="cs-width_5">
                      {!! $order->total_products_and_services_label !!}
                    </td>
                    @if($prices)
                    <td class="cs-width_5 cs-text_right">
                      <p class="cs-primary_color cs-bold cs-f19 cs-m0">@lang('Subtotal'):</p>
                      @if($order->discount)
                        <p class="cs-primary_color cs-bold cs-f19 cs-m0">@lang('Discount'):</p>
                      @endif
                      <p class="cs-mb5 cs-mb5 cs-f19 cs-primary_color cs-semi_bold">IVA:</p>
                      <p class="cs-primary_color cs-bold cs-f19 cs-m0">@lang('Total'):</p>
                    </td>
                    <td class="cs-width_2 cs-text_rightcs-f19">
                        <p class="cs-primary_color cs-bold cs-f19 cs-m0 cs-text_right">${{ count($order->product_suborder) ? '--' : number_format($order->subtotal_by_all, 2)  }}</p>
                      @if($order->discount)
                        <p class="cs-mb5 cs-mb5 cs-text_right cs-f19 cs-primary_color cs-semi_bold">
                          @if(!$breakdown)
                            ${{ number_format($order->calculate_discount_all, 2)}}
                          @else
                            %{{ $order->discount }}
                          @endif
                        </p>
                      @endif
                        <p class="cs-mb5 cs-mb5 cs-text_right cs-f19 cs-primary_color cs-semi_bold">${{ count($order->product_suborder) ? '--' : calculateIva($order->subtotal_less_discount) }}</p>
                      <p class="cs-primary_color cs-bold cs-f19 cs-m0 cs-text_right">${{ number_format(count($order->product_suborder) ? $total : $order->total_by_all_with_discount, 2) }}</p>
                    </td>
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
          </div>


        </div>
        @endif


        @if($details)
        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold" style="margin-top: 20px;">@lang('Details')
          <br><br>
          @foreach($tablesData as $parentId => $tableData)
          <div class="table-responsive">

              <div class="product-group mb-1">
                  <h5 class="mb-3"> <strong class="text-primary">{{ $tableData['parent_code'] }}</strong> {{ $tableData['parent_name'] }}</h5>
                  
                  <table class="table table-bordered table-sm">
                      <thead>
                          <tr>
                              @if($tableData['rows'][0]['no_size'])
                              <th>Código</th>
                              @endif
                              <th style="width: 250px !important;">Color</th>
                              @foreach($tableData['headers'] as $header)
                                  <th class="text-center">{{ $header['name'] }}</th>
                              @endforeach
                              @if($tableData['rows'][0]['no_size'])
                                <th></th>
                              @endif
                              <th class="text-center">Total</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($tableData['rows'] as $row)
                              <tr>
                                  @if($row['no_size'])
                                    <td style="width: 250px !important;">{{ $row['general_code'] }}</td>
                                  @endif
                                  <td>{{ $row['color_product'] ?: 'N/A' }}</td>
                                  
                                  @foreach($tableData['headers'] as $header)
                                      <td class="text-right text-center">
                                          @if(isset($row['sizes'][$header['id']]))
                                              {!! $prices ? $row['sizes'][$header['id']]['display'] : $row['sizes'][$header['id']]['only_display'] !!}
                                          @endif
                                      </td>
                                  @endforeach
                                  
                                  @if($row['no_size'])
                                  <td class="text-center">
                                      {!! $prices ? $row['no_size']['display'] : $row['no_size']['only_display'] !!}
                                  </td>
                                  @endif
                                  <td class="text-center font-weight-bold">
                                      {{ $row['row_quantity'] }}
                                      @if($prices)
                                      &nbsp;
                                      <small class="font-italic text-primary">
                                        {{ $row['row_total_display'] }}
                                      </small>
                                      @endif
                                  </td>
                              </tr>
                          @endforeach
                          
                          <!-- Fila de totales -->
                          <tr class="table-active">
                              @if($tableData['rows'][0]['no_size'])
                                <td class="font-weight-bold"></td>
                              @endif
                              <td class="font-weight-bold"></td>
                              
                              @foreach($tableData['headers'] as $header)
                                  <td class="text-center font-weight-bold">
                                      @if(isset($tableData['totals']['size_totals'][$header['id']]))
                                          {{ $tableData['totals']['size_totals'][$header['id']]['quantity'] }} 
                                          {{-- ×
                                          {{ number_format($tableData['totals']['size_totals'][$header['id']]['amount'] / $tableData['totals']['size_totals'][$header['id']]['quantity'], 2) }}
                                          <br>
                                          = {{ number_format($tableData['totals']['size_totals'][$header['id']]['amount'], 2) }} --}}
                                      @endif
                                  </td>
                              @endforeach

                              @if($row['no_size'])                    
                              <td class="text-center font-weight-bold">
                                  @if($tableData['totals']['no_size_total']['quantity'] > 0)
                                      {{ $tableData['totals']['no_size_total']['quantity'] }} 

                                      {{-- @if($prices)
                                        ×
                                        {{ number_format($tableData['totals']['no_size_total']['amount'] / $tableData['totals']['no_size_total']['quantity'], 2) }}
                                        <br>
                                        = {{ number_format($tableData['totals']['no_size_total']['amount'], 2) }}
                                      @endif --}}
                                  @endif
                              </td>
                              @endif
                              <td class="text-center font-weight-bold text-danger">
                                  {{ $tableData['totals']['row_quantity'] }}
                                  &nbsp;
                                  @if($prices)
                                    <small class="font-italic">
                                      {{ $tableData['totals']['grand_total'] }}
                                    </small>
                                  @endif
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
            </div>
          @endforeach
        </div>
        @endif

        <div style="page-break-inside:avoid;">

          @if(!$general)
          <div class="cs-table cs-style2 cs-f18">
            <div class="cs-table_responsive">
              <table>
                <tbody>
                  <tr class="cs-table_baseline">
                    <td class="cs-width_5">
                      {!! $order->total_products_and_services_label !!}
                    </td>
                    @if($prices)
                    <td class="cs-width_5 cs-text_right">
                      <p class="cs-primary_color cs-bold cs-f19 cs-m0">@lang('Subtotal'):</p>
                      @if($order->discount)
                        <p class="cs-primary_color cs-bold cs-f19 cs-m0">@lang('Discount'):</p>
                      @endif
                      <p class="cs-mb5 cs-mb5 cs-f19 cs-primary_color cs-semi_bold">IVA:</p>
                      <p class="cs-primary_color cs-bold cs-f19 cs-m0">@lang('Total'):</p>
                    </td>
                    <td class="cs-width_2 cs-text_rightcs-f19">
                        <p class="cs-primary_color cs-bold cs-f19 cs-m0 cs-text_right">${{ count($order->product_suborder) ? '--' : number_format($order->subtotal_by_all, 2)  }}</p>
                      @if($order->discount)
                        <p class="cs-mb5 cs-mb5 cs-text_right cs-f19 cs-primary_color cs-semi_bold">
                          @if(!$breakdown)
                            ${{ number_format($order->calculate_discount_all, 2)}}
                          @else
                            %{{ $order->discount }}
                          @endif
                        </p>
                      @endif
                        <p class="cs-mb5 cs-mb5 cs-text_right cs-f19 cs-primary_color cs-semi_bold">${{ count($order->product_suborder) ? '--' : calculateIva($order->subtotal_less_discount) }}</p>
                      <p class="cs-primary_color cs-bold cs-f19 cs-m0 cs-text_right">${{ number_format(count($order->product_suborder) ? $total : $order->total_by_all_with_discount, 2) }}</p>
                    </td>
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          @endif

          <div class="cs-note cs-f18">
            <div class="cs-note_right" style="margin-left: 20px;">
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'): 
                {!! $order->isQuotation() && ($order->branch_id == 0) ? setting('footer_quotation_production').'<br>' :'' !!}
              </b></p>
            </div>
          </div><!-- .cs-note -->
        </div>
      </div>
    </div>


</div>