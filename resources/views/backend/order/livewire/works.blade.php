<div>
        @lang('Workstation') - <h3 class="d-inline">{{ ucfirst($status->name ?? ' ') }}.</h3>

        

		<div class="container-fluid">

		  <div class="row">
		    <!-- Contenido principal con margen derecho para el sidebar -->
		    <main class="col-md-9" style="margin-right: 320px;">
		      <!-- Tu contenido largo aquí -->
		    </main>
		    
		    <!-- Sidebar flotante -->
		    <aside class="position-fixed bg-light border-left" style="right: 0%; top: 19%; width: 18%; height: calc({{ $floatButton ? '73vh' : '10vh' }}); overflow-y: auto; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">


		      <div class="p-3">

	            {{-- <button wire:click="$toggle('floatButton')" class="btn btn-outline-primary btn-sm btn-lg btn-block mb-3">
	              @if(!$floatButton)
	              	<i class="cil-fullscreen"></i>
	              @else
	                <i class="cil-fullscreen-exit"></i>
	              @endif
	            </button> --}}

                <div class="alert text-center" role="alert">
                    
                    <a href="#" class="cd-nav-link cd-signalize-border text-center">{{ ucfirst($status->name ?? ' ') }}.</a>

                </div>

                @if($order->validateAllExists() == false)
                    <p class="text-center text-danger"> Pedido no concluido </p>
                @else
                    <p class="text-center text-primary"> Pedido concluido 🙏</p>
                @endif

                <select id="redirectSelect" class="form-control mb-4 shadow-sm">
                    <option value="NoLink">Seleccionar Estación</option>
                    @foreach(\App\Models\Status::orderBy('level')->whereActive(true)->get() as $s)
                      <option style="color:#0071c5;" value="{{ route('admin.order.work', [$order->id, $s->id]) }}">
                        <strong>
                          {{ ucfirst($s->name) }}
                        </strong>
                      </option>
                    @endforeach
                </select>

			  	<div class="row">
				    <div class="col-sm text-right">
			            @if($previous_status)
			              <a href="{{ route('admin.order.work', [$order->id, $previous_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $previous_status->name ?? null }}">
			               <i class="cil-chevron-left"></i>
			              </a>
			            @endif
				    </div>
				    <div class="col-sm text-left">
				        @if($next_status)
				          <a href="{{ route('admin.order.work', [$order->id, $next_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $next_status->name ?? null }}">
				            <i class="cil-chevron-right"></i>
				            @if($next_status->finial_process) &nbsp; <i class="cil-running"></i> @endif
				          </a>
				        @endif
				    </div>
				</div>                	

		        <p class="mt-4 h3"><a href="{{ route('admin.order.edit',  $order->id) }}">@lang('Order') #{!! $order->folio_or_id !!} </a></p>
		          <!-- Más items... -->

                <table class="table mb-0 table-sm table-hover">
                  <tbody>
                    <tr>
                      <td class="pl-0">@lang('Customer')</td>
                      <td class="pr-0 text-right text-primary"><strong class="h3">{!! $order->user_name !!}</strong></td>
                    </tr>
                    @if($order->info_customer)
                      <tr>
                        <td class="pl-0">@lang('Info customer')</td>
                        <td class="pr-0 text-right">{{ $order->info_customer }}</td>
                      </tr>
                    @endif
                    @if($order->request)
                      <tr>
                        <td class="pl-0">@lang('Request n.º')</td>
                        <td class="pr-0 text-right">{{ $order->request }}</td>
                      </tr>
                    @endif
                    @if($order->purchase)
                      <tr>
                        <td class="pl-0">@lang('Purchase O.')</td>
                        <td class="pr-0 text-right">{{ $order->purchase }}</td>
                      </tr>
                    @endif
                    </tr>
                    @if($order->comment)
                      <tr>
                        <td class="pr-0 text-right h4" colspan="2">{{ $order->comment }}</td>
                      </tr>
                    @endif
                    @if($order->observation)
                      <tr>
                        <td class="pl-0">@lang('Observations')</td>
                        <td class="pr-0 text-right">{{ $order->observation }}</td>
                      </tr>
                    @endif
                    @if($order->complementary)
                      <tr>
                        <td class="pl-0">@lang('Complementary observations')</td>
                        <td class="pr-0 text-right">{{ $order->complementary }}</td>
                      </tr>
                    @endif
                  </tbody>
                </table>

                <table class="table mb-0 table-sm mt-4">
                  <thead>
                    <tr class="bg-dark">
                      <th class="pl-0 pl-2">@lang('Reference')</th>
                      <th class="text-right">@lang('Value')</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="pl-0 pl-2">Captura</td>
                      <td class="pr-0 text-right pr-3">{{ $calculateStatusQuantities['Captura'] }}</td>
                    </tr>
                    <tr>
                      <td class="pl-0 pl-2"><ins><strong> En Proceso </strong></ins></td>
                      <td class="pr-0 text-right text-danger pr-3"> {{ $calculateStatusQuantities['Proceso'] }}<ins><strong>
                          

                      </strong></ins> </td>
                    </tr>
                    <tr>
                      <td class="pl-0 pl-2">Terminado</td>
                      <td class="pr-0 text-right pr-3">{{ $calculateStatusQuantities['Terminado'] }}</td>
                    </tr>
                    <tr class="table-dark">
                      <td class="pl-0 pl-2">Servicios</td>
                      <td class="pr-0 text-right pr-3">{{ $calculateStatusQuantities['Services'] }}</td>
                    </tr>
                  </tbody>
                </table>

		      </div>
		    </aside>
		  </div>
		</div>

      	<div class="row" >
	        <div class="col-sm-10 " style="margin-left: -30px;">

				<div class="container-fluid ">

@foreach($order->getSizeTablesData($status->getStatusCollection()) as $parentId => $tableData)
	<br>
    <div class="product-group shadow bg-white 
		{{ $status->callout_class }}
    " 
    data-parent-id="{{ $parentId }}"
    >
        <h5 class=""> 
            <a href="{{ route('admin.product.edit',  $parentId) }}" target="_blank"><strong class="text-primary">{{ $tableData['parent_code'] }}</strong> 
                {{ $tableData['parent_name'] }}
            </a>
        </h5>

        @if(is_int($parentId))
        <div class="row">
            <div class="text-left col">
                @if(count($tableData['rows']) < 2 && $getStatusCollection['is_principal'] && $getStatusCollection['is_batch'])
                        <button 
                            wire:click="messageAlert('saveAll', '{{ $parentId }}')" 
                            wire:loading.attr="disabled"
                            class="btn btn-sm btn-danger mb-2"
                        >
                            <span wire:loading.remove wire:target="messageAlert('saveAll', '{{ $parentId }}')">
                                <i class="fas fa-save"></i> Crear ticket 
                                {{-- {{ $tableData['parent_code'] }} --}}
                            </span>
                            <span wire:loading wire:target="messageAlert('saveAll', '{{ $parentId }}')">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                Guardando...
                            </span>
                        </button>
                @endif
            </div>
        </div>

        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        @if($tableData['rows'][0]['no_size'])
                        <th class="align-middle">Código</th>
                        @endif
                        <th class="align-middle" style="width: 250px;">Color</th>
                        @foreach($tableData['headers'] as $header)
                            <th class="text-center align-middle">{{ $header['name'] }}</th>
                        @endforeach
                        @if($tableData['rows'][0]['no_size'])
                            <th class="align-middle"></th>
                        @endif
                        <th class="text-center align-middle">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableData['rows'] as $rowIndex => $row)
                        <tr>
                            @if($row['no_size'])
                                <td style="width: 35%">{{ $row['general_code'] }}</td>
                            @endif
                            <td style="width: 10%">
                                {{ $row['color_product'] ?: 'N/A' }}
						        <!-- Agrega este campo oculto para capturar el color_id -->
						        @if(is_int($parentId))
							        <div wire:ignore>
							        	<input
							        		type="hidden" 
										    wire:model.lazy="colorIds.{{ $parentId }}.{{ $rowIndex }}"
										    value="{{ $row['color_id'] }}"
										>
								    </div>
								@endif
                            </td>

                            {{-- @json($row) --}}
                            
            @foreach($tableData['headers'] as $header)
                <td class="text-center">
                    @if(isset($row['sizes'][$header['id']]))
                        {!! $row['sizes'][$header['id']]['quantity'] !!}
                        <br>
                        <div class="d-inline-block position-relative">
                            <input 
                                type="number"
                                placeholder="{{ $row['sizes'][$header['id']]['active'] }}"
                                class="{{ $row['sizes'][$header['id']]['active'] == 0 ? 'placeholder-zero' : '' }}  form-control text-center form-control-sm @error('quantities.'.$parentId.'.'.$rowIndex.'.'.$header['id']) is-invalid @enderror" 
                                style="width: 45px; color: blue;"
                                wire:model.lazy="quantities.{{ $parentId }}.{{ $rowIndex }}.{{ $header['id'] }}"
                                wire:change="updateGroupTotal('{{ $parentId }}')"
                                oninput="calculateGroupTotal('{{ $parentId }}')"
                                data-parent-id="{{ $parentId }}"
                                min="1"
                                max="{{ $row['sizes'][$header['id']]['quantity'] ?? 0 }}"
                                step="1"
                                data-size="{{ $header['id'] }}"
                                data-color="{{ $row['color_product'] }}"
                            >
                            {{-- @error('quantities.'.$parentId.'.'.$rowIndex.'.'.$header['id'])
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror --}}
                        </div>
                    @endif
                </td>
            @endforeach
                            
                            @if($row['no_size'])
                            <td class="text-center">
                                {!! $row['no_size']['quantity'] !!}
                            </td>
                            @endif
                            <td class="text-center font-weight-bold">
                                <div>{{ $row['row_quantity'] }}</div>
                            </td>
                        </tr>
                    @endforeach
                    
                    <!-- Totals row -->
                    <tr class="bg-light">
                        @if($tableData['rows'][0]['no_size'])
                            <td class="font-weight-bold"></td>
                        @endif
                        <td class="font-weight-bold"></td>
                        
                        @foreach($tableData['headers'] as $header)
                            <td class="text-center font-weight-bold text-dark">
                                @if(isset($tableData['totals']['size_totals'][$header['id']]))
                                    {{ $tableData['totals']['size_totals'][$header['id']]['quantity'] }}
                                @endif
                            </td>
                        @endforeach

                        @if($row['no_size'])                    
                        <td class="text-center font-weight-bold text-dark">
                            @if($tableData['totals']['no_size_total']['quantity'] > 0)
                                {{ $tableData['totals']['no_size_total']['quantity'] }}
                            @endif
                        </td>
                        @endif
                        <td class="text-center font-weight-bold text-danger">
                            <div>{{ $tableData['totals']['row_quantity'] }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            {{-- @if(($changesByParent[$parentId] ?? false)) --}}
                <div class="text-right col" wire:ignore>
                    <button 
                        wire:click="messageAlert('save', '{{ $parentId }}')" 
                        wire:loading.attr="disabled"
                        class="btn btn-sm btn-primary btn-hover "
                    >
                        <span wire:loading.remove wire:target="messageAlert('save', '{{ $parentId }}')">
                            <i class="fas fa-save"></i> Guardar captura 
                            <h5 class="d-inline">
                                <span class="badge badge-light text-dark" id="total-{{ $parentId }}">0</span>
                            </h5>
                        </span>
                        <span wire:loading wire:target="messageAlert('save', '{{ $parentId }}')">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                            Guardando...
                        </span>
                    </button>
                </div>
            {{-- @endif --}}

        </div>


@if(is_int($parentId) && $order->getBatchForStatus($status->id, $parentId))
    <ul class="list-group list-group-flush mt-2">
        @foreach($order->getBatchForStatus($status->id, $parentId) as $pp)
            <a href="{{ route('admin.order.production_batch', [$this->order->id, $pp->id]) }}" class="list-group-item d-flex justify-content-between align-items-center {{ $pp->allItemsAreInactiveAndBalanced() ? 'list-group-item-success' : 'list-group-item-danger' }} list-group-item-action">
                <div class="row w-100">
                    <div class="col-4">  <!-- 33% width -->
                        Folio #{{ $pp->folio ?? $pp->id }}
                        <span class="badge badge-secondary badge-pill">{{ optional($pp->personal)->name }}</span>
                    </div>
                    <div class="col-5 text-right">  <!-- 33% width -->
                         <span class="badge badge-secondary badge-pill"><h5> Activo: <strong class="text-danger"> {{ $order->getTotalBatchActiveProduction($status->id, $parentId, $pp->id) }} </strong></h5></span>
                    </div>
                    <div class="col-3 text-right">  <!-- 33% width -->
                        <h5>
                            @if($pp->allItemsAreBalanced())
                                <span class="badge badge-success ml-4">Total recibido</span>
                            @endif
                            <span class="badge badge-primary badge-pill">{{ $order->getTotalBatchProduction($status->id, $parentId, $pp->id) }}</span>
                        </h5>
                    </div>
                </div>
            </a>
        @endforeach
    </ul>
@endif

    </div>
@endforeach
				</div>
			</div>
		</div>
</div>