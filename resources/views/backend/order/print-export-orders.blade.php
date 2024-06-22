<div class="cs-container" style="{{ $width ? 'max-width: 2080px;' : '' }}">
    @if($ordercollection)
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_btns cs-hide_print">   

        <a href="{{ route('admin.dashboard') }}" class="cs-invoice_btn cs-color1" style="margin-right: 10px;">
          <<---
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
        <div class="cs-invoice_head cs-mb25">
          <div class="cs-invoice_left">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo22.png') }}" width="100" alt="Logo"></div>
          </div>
          <div class="cs-invoice_right cs-text_right">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/bacapro.png') }}" width="130" alt="Logo"></div>
          </div>
        </div>

        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="legend">
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('Created'):</b> 
              {{ now()->isoFormat('D, MMM, YY - h:mm a') }}
            </p>

            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color"> {{ count($ordercollection) }} @lang('Orders'):</b> 
              @foreach($ordercollection as $key => $order)
                #{{ $order['folio']}}
                {{ !$loop->last ? ',' : '' }}
              @endforeach
            </p>
          </div>
          <div class="cs-invoice_right cs-text_right">
          </div>
        </div>

        <div class="cs-invoice_head cs-mb10">
          <div class="legend">
            <b class="cs-primary_color">@lang('About it'):</b>
            <p class="cs-mb8">
              @foreach($orderCollection as $order)
                <span class="badge badge-warning ml-1 mr-1 mt-1" style="font-size: 1rem;">
                  #{!! 
                    $order['folio'].' => '.
                    $order['comment'].' '
                  !!}

                    <a target="_blank"
                      href="#"
                      style="text-decoration: none !important">
                      {{ $order['user'] }}
                    </a>

                  @if($order['request'])
                    <strong class="cs-accent_color_second">{{ $order['request'] }}</strong>
                  @endif
                  &nbsp;&nbsp;&nbsp;&nbsp;
                </span>
              @endforeach
            </p>
          </div>
        </div>

        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">@lang('Summary')</div>
        <div class="cs-table cs-style2">
          <div class="cs-round_border">
            <div class="cs-table_responsive">
              <table>
                <thead>
                  <tr class="cs-focus_bg2">
                    @if(count($ordercollection) > 1)
                    <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_center"># - @lang('Customer')</th>
                    @endif
                    @foreach($products as $key => $product)
                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">
                        {{-- {{ $key+1 }}.- --}}

                        @foreach($product->first() as $key2 => $parentProduct)
                          @if ($loop->first)
                            {{ $parentProduct['productParentCode'] ?: '-' }}
                          @endif
                        @endforeach

                      </th>
                    @endforeach
                    <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">@lang('Total')</th>
                  </tr>
                </thead>
                <tbody>

                  @php($totalAllGrouped = 0)

                  @foreach($ordercollection as $order)

                    @php($totalRowGrouped = 0)

                    <tr class="cs-focus_bg2">
                      @if(count($ordercollection) > 1)
                      <td class="cs-width_3 cs-text_center cs-accent_color">
                        @if($order['folio'])
                          <a target="_blank"
                            href="#"
                            style="text-decoration: none !important">
                            {!! '#'.$order['folio'].' - '.$order['user'] !!}
                          </a>
                        @endif
                      </td>
                      @endif
                      @foreach($products as $key => $product)
    
                        @php($totalProductGrouped = 0)

                        <td class="cs-width_1 cs-text_center cs-primary_color">

                          @foreach($product as $key2 => $parentProduct)

                            @foreach($parentProduct as $al)
                              @if($al['productOrder'] === $order['folio'])
                                @php($totalProductGrouped += $al['productQuantity'])
                              @endif
                            @endforeach

                          @endforeach

                          {{ $totalProductGrouped ?: '-' }}
                          @php($totalRowGrouped += $totalProductGrouped)
                        </td>
                      @endforeach
                      <th class="cs-width_1 cs-text_center cs-primary_color">
                        {{ $totalRowGrouped }}
                        @php($totalAllGrouped += $totalRowGrouped)
                      </th>
                    </tr>
                  @endforeach
                  <tr class="cs-focus_bg2">
                    @if(count($ordercollection) > 1)
                      <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_center"></th>
                    @endif
                    @foreach($products as $or)

                      @php($totalByParentProduct = 0)

                      <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">
                        @foreach($or as $omgg)
                          @foreach($omgg as $sa1)
                            @php($totalByParentProduct += $sa1['productQuantity'])
                          @endforeach
                        @endforeach
                        {{ $totalByParentProduct }}
                      </th>
                    @endforeach
                    <th class="cs-width_1 cs-semi_bold cs-primary_color cs-text_center">
                      <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_center"><kbd>{{ $totalAllGrouped }}</kbd> </p>

                    </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold" style="margin-top: 20px;">@lang('Details') </div>
        
        @php($totalAll = 0)

        @foreach($products as $key => $product)
          @foreach($product as $key2 => $parentProduct)

          <div
            style="page-break-inside:avoid; padding-top: 15px;"
          >
            <strong style="color: #003075">{{ $key2 }}</strong>

            <div class="cs-table cs-style cs-mb10">
              <div class="cs-round_border">
                <div class="cs-table_responsive">
                  <table>
                    <thead>
                      <tr class="cs-accent_10_bg">
                        @if(count($ordercollection) > 1)
                          <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_center"># - @lang('Customer')</th>
                        @endif
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
                          @if(count($ordercollection) > 1)
                          <td class="cs-width_3 cs-text_center cs-accent_color">
                            @if($pp[0]['productOrder'])
                              <a target="_blank"
                                href="#"
                                style="text-decoration: none !important">
                                {!! '#'.$pp[0]['productOrder'].' - '.$pp[0]['customer'] !!}
                              </a>
                            @endif
                           
                          </td>
                          @endif

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
                        @if(count($ordercollection) > 1)
                          <th class="cs-width_3 cs-semi_bold cs-primary_color cs-text_center"></th>
                        @endif
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
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'): --  </b></p>
            </div>
          </div><!-- .cs-note -->
        </div>
      </div>
    </div>
    @else

    <div class="cs-invoice cs-style1">
      
      <div class="cs-note_right">
        <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'):</b></p>
        <p class="cs-m0">@lang('Nothing processed'). :)</p>
      </div>      
      <div class="cs-invoice_btns cs-hide_print">   
        <a href="{{ route('admin.order.index') }}" class="cs-invoice_btn cs-color1">
          <<
          <span>&nbsp; @lang('Back')</span>
        </a>
      </div>
    </div>

    @endif
</div>