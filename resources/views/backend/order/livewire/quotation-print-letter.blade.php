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

        <button wire:click="$toggle('actualStock')" class="cs-invoice_btn {{ !$actualStock ? '' : 'cs-color1' }}">
          @if(!$actualStock)
            Mostrar existencia
          @else
            Ocultar existencia
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
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f18"><b class="cs-primary_color">Captura Cotización </b> </p>
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


          </div>
        </div>

        @if($summary)
        <div class="cs-style1 cs-f18 cs-primary_color cs-mb10 cs-semi_bold">@lang('Customer Information')</div>
        <ul class="cs-grid_row cs-col_3 cs-f18 cs-mb5">
          <li>
            {{ $summary->customer ? optional($summary->customer)->name  : '' }}
          </li>
        </ul>
        @endif



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
                            ${{ $priceIVaIncluded ? $product->min_price : priceWithoutIvaIncluded($product->min_price) }}
                            -
                          @endif
                            ${{ $priceIVaIncluded ? $product->max_price : priceWithoutIvaIncluded($product->max_price) }}
                        </td>
                        <td class="cs-width_2 cs-text_right cs-primary_color">${{ $priceIVaIncluded ? $product->sum_total : priceWithoutIvaIncluded($product->sum_total) }}</td>
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
            </div>
          </div>


        </div>
        @endif


        
        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb20 cs-semi_bold">@lang('Bom of Materials')</div>


        <div class="cs-table cs-style2 cs-mb50 cs-table cs-style1  cs-mb30">
          <div class="cs-round_border tm-border-radious-12">
                <div class="cs-table_responsive">
                    <table>
                        <thead>
                            <tr class="cs-focus_bg tm-bg-gray">
                                <th class="cs-width_2 cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">@lang('Quantity')</th>
                                @if($actualStock)
                                <th class="cs-width_2 cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">Existencia</th>
                                @endif
                                <th class="cs-width_2 cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">@lang('Code')</th>
                                <th class="cs-width_6  cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">Materia Prima</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($allMaterials as $key => $material)
                          <tr class="{{ $loop->odd ? '' : 'cs-accent_10_bg' }}" style="{{ $material['quantity'] == 0 ? 'text-decoration: line-through;' : '' }}">
                              <td>
                                  {{ $material['quantity'] .'  '.$material['unit_measurement'] }}
                              </td>
                              @if($actualStock)
                              <td>{{ $material['stock'] .'  '.$material['unit_measurement']  }}</td>
                              @endif
                              <td>{{ $material['part_number'] }}</td>
                              <td class="cs-primary_color">
                                {{  $material['material_name'] }} 
                              </td>
                          </tr>

                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div style="page-break-inside:avoid;">


          <div class="cs-note cs-f18">
            <div class="cs-note_right" style="margin-left: 20px;">
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'): 
              </b></p>
            </div>
          </div><!-- .cs-note -->
        </div>
      </div>
    </div>


</div>