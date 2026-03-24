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
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f14"><b class="cs-primary_color">{{ $order->type_order_clear }} No:</b> #{{ $order->characters_type_order }}{!! $order->folio_or_id !!}</p>
          </div>
          <div class="cs-invoice_right cs-text_center">
            <b class="cs-primary_color cs-f14">{{ __(appName()) }}</b>
            <p class="cs-f14">
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



        <div class="cs-table cs-style1 cs-accent_10_bg cs-mb30">
          <div class="cs-table_responsive">
            <table class="cs-border_less">
              <tbody>
                <tr>
                  <td class="cs-width_4 cs-text_center">
                    <p class="cs-accent_color cs-m0 cs-bold cs-f16 cs-special_item">Lote</p>
                    <p class="cs-m0">#{{ $station->folio ?? $station->id }}</p>
                  </td>
                  <td class="cs-width_8">
                    <div class="cs-table cs-style1">
                      <table>
                        <tbody>
                          <tr>
                            <td class="cs-primary_color cs-semi_bold">Total</td>
                            <td class="cs-primary_color cs-semi_bold">Asignado a</td>
                            <td class="cs-primary_color cs-semi_bold">Archivo generado</td>
                          </tr>
                          <tr>
                            <td> {!! $station->total_products_prod !!}</td>
                            <td>{{ ucwords(strtolower(optional($station->personal)->name)) }}</td>
                            <td>{{ printed() }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="cs-table cs-style1 cs-accent_10_bg cs-mb30">
          <div class="cs-table_responsive">
            <table class="cs-border_less">
              <tbody>
                <tr>
                  <td class="cs-width_4 cs-text_center">
                    <p class="cs-accent_color cs-m0 cs-bold cs-f16 cs-special_item">Cliente</p>
                    <p class="cs-m0">{{ optional($order->user)->name . optional($order->departament)->name }}</p>
                  </td>
                  <td class="cs-width_8">
                    <div class="cs-table cs-style1">
                      <table>
                        <tbody>
                          <tr>
                            @if($order->info_customer)
                            <td class="cs-primary_color cs-semi_bold">@lang('Info customer')</td>
                            @endif
                            @if($order->comment)
                            <td class="cs-primary_color cs-semi_bold">@lang('Comment')</td>
                            @endif
                            <td class="cs-width_3 cs-primary_color cs-semi_bold">@lang('Order Date')</td>
                          </tr>
                          <tr>
                            @if($order->info_customer)
                            <td> {{ $order->info_customer }}</td>
                            @endif
                            @if($order->comment)
                            <td>{{ $order->comment }}</td>
                            @endif
                            <td>{{ $order->date_entered_or_created }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        @if($details)
        <div class="cs-heading cs-style1 cs-f14 cs-primary_color cs-mb25 cs-semi_bold" style="margin-top: 20px;">@lang('Details')
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
                                              {!!  $row['sizes'][$header['id']]['only_display'] !!}
                                          @endif
                                      </td>
                                  @endforeach
                                  
                                  @if($row['no_size'])
                                  <td class="text-center">
                                      {!!  $row['no_size']['only_display'] !!}
                                  </td>
                                  @endif
                                  <td class="text-center font-weight-bold">
                                      {{ $row['row_quantity'] }}
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
                                          {{ $tableData['totals']['size_totals'][$header['id']]['input_quantity'] }} 
                                          {{-- ×
                                          {{ number_format($tableData['totals']['size_totals'][$header['id']]['amount'] / $tableData['totals']['size_totals'][$header['id']]['quantity'], 2) }}
                                          <br>
                                          = {{ number_format($tableData['totals']['size_totals'][$header['id']]['amount'], 2) }} --}}
                                      @endif
                                  </td>
                              @endforeach

                              @if($row['no_size'])                    
                              <td class="text-center font-weight-bold">
                                  @if($tableData['totals']['no_size_total']['input_quantity'] > 0)
                                      {{ $tableData['totals']['no_size_total']['input_quantity'] }} 
                                  @endif
                              </td>
                              @endif
                              <td class="text-center font-weight-bold text-danger">
                                  {{ $tableData['totals']['row_quantity'] }}
                                  &nbsp;
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
            </div>
          @endforeach
        </div>
        @endif


        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb20 cs-semi_bold">@lang('Bom of Materials')</div>

        @if($station->consumption)
          <div class="alert alert-success cs-hide_print" role="alert">
            Materia prima consumida
          </div>  
        @else
          <div class="alert alert-danger cs-hide_print" role="alert">
            Materia prima no consumida. Es necesario realizar el consumo para ajustar cantidades.
          </div>        
        @endif

        <div class="cs-table cs-style2 cs-mb50 cs-table cs-style1  cs-mb30">
          <div class="cs-round_border tm-border-radious-12">
                <div class="cs-table_responsive">
                    <table>
                        <thead>
                            <tr class="cs-focus_bg tm-bg-gray">
                                <th class="cs-width_2 cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">@lang('Quantity')</th>
                                <th class="cs-width_2 cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">@lang('Code')</th>
                                <th class="cs-width_8  cs-semi_bold cs-primary_color" style="color: #2ad19d !important;">Materia Prima</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($allMaterials as $key => $material)
                          <tr class="{{ $loop->odd ? '' : 'cs-accent_10_bg' }}" style="{{ $material['quantity'] == 0 ? 'text-decoration: line-through;' : '' }}">
                              <td>
                                @if(!empty($manualMaterials[$material['material_id']]))
                                <del>
                                  {{ $material['quantity'] .'  '.$material['unit_measurement'] }}
                                </del>
                                @else
                                  {{ $material['quantity'] .'  '.$material['unit_measurement'] }}
                                @endif


@if($station->consumption && empty($manualMaterials[$material['material_id']]) && !$station->allItemsAreBalanced())
  <p class="card-text no-print d-inline cs-hide_print">

      <div x-data="{ show: false }" class="d-inline">
          
          <h5>
              <button class="cs-hide_print badge badge-info" @click="show = !show">
                  Agregar cantidad 
              </button>
          </h5>

          <div x-show="show" class="mt-2">
              <input 
                  type="text" 
                  class="form-control m-1 cs-hide_print" 
                  placeholder="Agregar" 
                  wire:model="add_qty.{{ $key }}"
              >

              <input 
                  type="text" 
                  class="form-control m-1 cs-hide_print" 
                  placeholder="Comentario" 
                  wire:model="add_comment.{{ $key }}"
              >
          </div>

          @if(!empty($add_qty[$key]))
          <div x-show="show">
              <a 
                  role="button" 
                  wire:click="saveData({{ $key }})" 
                  class="cs-hide_print btn btn-sm btn-primary float-right mt-2 text-white"
              >
                  @lang('Save')
              </a>
          </div>
          @endif

      </div>
      @error("add_qty.$key") 
          <span class="error" style="color: red;">
              <p>{{ $message }}</p>
          </span> 
      @enderror
  </p>
@endif

                              </td>
                              <td>{{ $material['part_number'] }}</td>
                              <td class="cs-primary_color">
                                @if($material['cloth_width'])
                                  <strong style="color:red;">Ancho de tela: ______________________</strong>
                                  &nbsp;&nbsp;&nbsp;
                                @endif
                                {{  $material['material_name'] }} 
                              </td>
                          </tr>
@if(!empty($manualMaterials[$material['material_id']]))
    @foreach($manualMaterials[$material['material_id']] as $manual)
        <tr style="background-color: #ffeeba;">
            <td >
                + {{ $manual['quantity'].' '.$material['unit_measurement'] }} (manual) = 
                <strong class="text-danger">
                  {{ $manual['quantity'] + $material['quantity'].' '.$material['unit_measurement'] }}
                </strong>
            </td>
            <td colspan="2">
              <em>
                {{ $manual['comment'] }}
              </em>
            </td>
        </tr>
    @endforeach
@endif

                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="cs-note">
          <div class="cs-note_left">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M416 221.25V416a48 48 0 01-48 48H144a48 48 0 01-48-48V96a48 48 0 0148-48h98.75a32 32 0 0122.62 9.37l141.26 141.26a32 32 0 019.37 22.62z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M256 56v120a32 32 0 0032 32h120M176 288h160M176 368h160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
          </div>
          <div class="cs-note_right">
            <p class="cs-mb0"><b class="cs-primary_color cs-bold">Nota:</b></p>
            <p class="cs-m0">Este documento no representa lo consumido ni pendiende por consumir, sólo muestra las cantidades de los productos del Lote y la Explosión de Materiales actuales a la fecha de 'Archivo generado'.</p>
          </div>
        </div><!-- .cs-note -->

      </div>
    </div>


</div>