<x-backend.card>
    <x-slot name="header">
        @lang('Show workstation') - {{ $status_name }}
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.order.edit_chart', $order_id)" :text="__('Go to edit order')" />

        <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">


          <div class="row">
            <div class="col-sm-7 mt-2">
              <div class="card" style="background-color: #f0f0f0;">
                <div class="col d-flex"><span class="text-muted" id="orderno">@lang('Order') #{!! $model->folio_or_id !!}</span></div>
                <div class="gap">
                    <div class="col-2 d-flex mx-auto"> </div>
                </div>
                <div class="title mx-auto"> {!! $model->aboutOrderInfo() !!} </div>
                <div style="width: 50px;" class="mx-auto">
                  {!! $model->aboutOrder() !!}
                </div>

                <div class="mx-auto"><strong>Info:</strong> {{ $status->data_logic }} </div>
                <div class="mx-auto"><strong></strong> {{ implode(', ', $status->getDataStatus()) }} </div>
                @if($status->initial_process)
                  <div class="mx-auto">
                    <div class="alert alert-danger mt-2 text-center mr-2 ml-2" role="alert">
                      Las cantidades ingresadas manualmente en <strong> {{ $status_name }} </strong> provienden del <strong> Almacén </strong>
                    </div>
                  </div>
                @endif
                <div class="main"> 
                    <span id="sub-title">
                      <p><b>@lang('Summary')</b></p>
                      @if($status->initial_lot || $status->initial_process || $status->supplier)
                        <div class="row align-items-start">
                          <div class="col-9">
                          </div>
                          <div class="col-3">
                            <div class="text-center" style="border-width: 2px; border-style: dashed; border-color: red; "> 
                              <a href="javascript:void(0);" style="display: block;" wire:click="emitUpdatedQuantity" class="text-dark">
                                <i class="cil-touch-app mr-4"></i>{{ $sumValue != 0 ? $sumValue : 'suma' }}
                              </a>
                            </div>
                          </div>
                        </div>
                      @endif
                    </span>
                    @foreach($model->products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                        <div class="row row-main">
                            <div class="col-3 text-center"> 
                              @if($product->product->isProduct())
                                {{-- <img class="img-fluid" src="{{ asset('/storage/' . optional($product->product)->parent->file_name) }}" onerror="this.onerror=null; this.src='{{ URL::to('/img/ga/not0.png') }}';"> --}}
                              @endif
                              {!! $product->isQuantityMatched() ? '<i class="cil-check" style="color: blue;"></i>' : '' !!}
                            </div>
                            <div class="{{ ($status->initial_process || $status->supplier) ? 'col-5' : 'col-6' }}">
                              <div class="row d-flex">
                                <p><b>{!!  $product->product->isProduct() ? '['.optional($product->product)->parent->code.'] ' : '' !!} {!! $product->product->only_name_link !!}</b></p>
                              </div>
                              <div class="row d-flex">
                                <p class="text-muted">{!! $product->product->only_parameters !!} </p>
                              </div>
                              @if($product->product->isProduct() && $status->supplier)
                                <div class="row d-flex">
                                  @if(!$product->product->parent->vendor_id)
                                    <span class="badge badge-danger">Sin proveedor definido</span>
                                  @else
                                    <span class="badge badge-primary">{{ $product->product->parent->vendor->name }}</span>
                                  @endif
                                </div>
                              @endif
                            </div>
                            {{--  --}}
                            @if($status->initial_process || $status->supplier)

                              <div class="col-1 d-flex justify-content-end text-center {{( $product->product->stock > 0) ? 'text-info' : 'text-danger' }}">
                                  <p><b>{{ $product->product->stock }} {{( $product->product->stock > 0) ? '[E]' : '' }}</b></p>
                              </div>
                            @endif

                            <div class="col-1 d-flex justify-content-end">
                                <p><b> {{ $product->quantity }}</b></p>
                            </div>

                            @if($status->initial_lot && ($product->product && $product->product->parent && $product->product->parent->brand->is_internal))
                              <div class="col-2 d-flex justify-content-end">
                                <input type="text" 
                                    wire:model.defer="quantity.{{ $product->id }}.available"
                                    class="form-control text-center @error('quantity.'.$product->id.'.available') is-invalid @enderror"
                                    style="color: red;"
                                    placeholder="{{ ($product->available_lot > 0) ? $product->available_lot : 0  }}"
                                    wire:keydown.tab="emitUpdatedQuantity"
                                    wire:keydown.escape="emitUpdatedQuantity"
                                >
                              </div>
                            @endif

                            @if($status->supplier)
                              <div class="col-2 d-flex justify-content-end">
                                <input type="number" 
                                    wire:model.defer="quantityFromSupplier.{{ $product->id }}.available"
                                    class="form-control text-center @error('quantityFromSupplier.'.$product->id.'.available') is-invalid @enderror"
                                    style="color: red;"
                                    placeholder="{{ ($product->available_supplier > 0) ? $product->available_supplier : 0  }}"
                                    wire:keydown.tab="emitUpdatedQuantity"
                                    wire:keydown.escape="emitUpdatedQuantity"
                                >
                              </div>
                            @endif

                            @if($status->initial_process)
                              <div class="col-2 d-flex justify-content-end">
                                <input type="number" 
                                    wire:model.defer="quantityFromStock.{{ $product->id }}.available"
                                    class="form-control text-center @error('quantityFromStock.'.$product->id.'.available') is-invalid @enderror"
                                    style="color: red;"
                                    placeholder="{{ ($product->available_process > 0) ? $product->available_process : 0  }}"
                                    wire:keydown.tab="emitUpdatedQuantity"
                                    wire:keydown.escape="emitUpdatedQuantity"
                                >
                              </div>
                            @endif

                        </div>
                    @endforeach
                    <hr>
                    <div class="total">

                      @if($status->initial_lot || $status->initial_process || $status->supplier)
                        <div class="row align-items-start mb-4">
                          <div class="col-9">
                          </div>
                          <div class="col-3">
                            <div class="text-center" style="border-width: 2px; border-style: dashed; border-color: red; "> 
                              <a href="javascript:void(0);" style="display: block;" wire:click="emitUpdatedQuantity" class="text-dark">
                                <i class="cil-touch-app mr-4"></i>{{ $sumValue != 0 ? $sumValue : 'suma' }}
                              </a>
                            </div>
                          </div>
                        </div>
                      @endif

                      <div class="row">
                        <div class="col-3"> </div>
                        <div class="col-6"> <b>Total:</b> </div>
                        <div class="col-1 d-flex justify-content-end"> <b> {{ $model->total_products }} </b> </div>
                        @if($status->initial_lot)
                          <div class="col-2 d-flex justify-content-center"> 
                              <button type="button" wire:click="createInfo('save')" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                                Lote &#128190;
                              </button>
                          </div>
                        @endif
                        @if($status->supplier)
                          <div class="col-2 d-flex justify-content-center"> 
                              <button type="button" wire:click="createInfo('saveFromSupplier')" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                              Pedido  &#128190;
                              </button>
                          </div>
                        @endif
                        @if($status->initial_process)
                          <div class="col-2 d-flex justify-content-center"> 
                              <button type="button" wire:click="createInfo('saveFromInitialProcess')" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                              Usar almacén &#128190;
                              </button>
                          </div>
                        @endif
                      </div> 
                      <a href="{{ route('admin.order.printexportorders', urlencode(json_encode(array(0 => $order_id)))) }}" target="_blank" class="btn btn-primary  mb-4  d-flex mx-auto" style="border-color: rgb(3, 122, 219); color: white; margin: 7vh 0; border-radius: 7px; width: 60%; font-size: 0.8rem; padding: 0.8rem; justify-content: center"> Ver pedido agrupado <i class="fas fa-external-link-alt m-1"></i></a>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-sm-5">
                <br>

                <div class="page-content page-container" id="page-content">
                    <div class="padding">

                        <div class="row container d-flex justify-content-center">

                            <select id="redirectSelect" class="mb-4">
                                <option value="NoLink">Redireccionar a Estación</option>
                                @foreach(\App\Models\Status::orderBy('level')->whereActive(true)->get() as $s)
                                  <option style="color:#0071c5;" value="{{ route('admin.order.station', [$order_id, $s->id]) }}">
                                    <strong>
                                      {{ ucfirst($s->name) }}
                                    </strong>
                                  </option>
                                @endforeach
                            </select>

                            <p>
                              <div class="mb-4 btn-group" role="group" aria-label="Basic example">
                                @if($previous_status)
                                  <a href="{{ route('admin.order.station', [$model->id, $previous_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $previous_status->name ?? null }}">
                                    @if($previous_status->to_add_users)<i class="c-icon  c-icon-4x cil-people"></i>@endif
                                    @lang('Previous status')
                                  </a>
                                @endif

                                @if($next_status)
                                  <a href="{{ route('admin.order.station', [$model->id, $next_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $next_status->name ?? null }}">
                                    @if($next_status->to_add_users)<i class="c-icon  c-icon-4x cil-people"></i>@endif
                                    @lang('Next status') 
                                    @if($next_status->finial_process) &nbsp; <i class="cil-running"></i> @endif
                                  </a>
                                @endif
                              </div>

                            </p>

                            <div class="col-md-12 grid-margin stretch-card">
                              <div class="card">
                                <div class="card-body">
                                  <h3 class="card-title text-danger text-center">
                                    {{ ucfirst($status_name) }}
                                  </h3>
                                  <p class="card-description">@lang('About it')</p>
                                  <div class="template-demo">
                                    <table class="table mb-0 table-sm">
                                      <thead>
                                        <tr>
                                          <th class="pl-0">Referencia</th>
                                          <th class="text-right">Valor</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td class="pl-0">Asignado</td>
                                          <td class="pr-0 text-right"><div class="badge badge-pill badge-dark"><i class="fa fa-flag mr-2"></i> {{ $model->getTotalQuantityByStation($status_id) }}</div></td>
                                        </tr>
                                        <tr>
                                          <td class="pl-0 text-danger"><ins><strong> Activo </strong></ins></td>
                                          <td class="pr-0 text-right text-danger"> <ins><strong>{{ $model->getTotalQuantityByStationClosed($status_id) + $model->getTotalQuantityByStationOpened($status_id) }}</strong></ins> </td>
                                        </tr>
                                        <tr>
                                          <td class="pl-0">Open</td>
                                          <td class="pr-0 text-right"><div class="badge badge-pill badge-primary"><i class="fa fa-user mr-2"></i>{{ $model->getTotalQuantityByStationOpened($status_id) }}</div></td>
                                        </tr>
                                        <tr>
                                          <td class="pl-0">Closed</td>
                                          <td class="pr-0 text-right"><div class="badge badge-pill badge-success">{{ $model->getTotalQuantityByStationClosed($status_id) }}<i class="fa fa-user ml-2"></i></div></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>


                            <p>
                              <div class="mb-4 btn-group m-2" role="group" aria-label="Basic example">
                                  <a href="{{ route('admin.order.report', $model->id) }}" target="_blank" class="btn btn-primary" data-toggle="tooltip" title="{{ __('General Report') }}"><i class="c-icon  c-icon-4x cil-newspaper"></i> @lang('General Report') <i class="fas fa-external-link-alt m-1"></i></a>
                              </div>

                              <div class="mb-4 btn-group m-2" role="group" aria-label="Basic example">
                                    <a href="#section1" class="btn btn-outline-secondary text-dark">@lang('Workstations')</a>
                              </div>
                            </p>

                        </div>
                    </div>
                </div>

            </div>
          </div>

          <div data-spy="scroll" data-target=".navbar" data-offset="50"></div>

          <div class="row" id="section1">

            @foreach($model->stations->where('status_id', $status_id) as $station)
              <div class="col-md-4 col-sm-6">
                  <div class="pricing-table-3 {{ $status->batch ? 'basic' : '' }} {{ $status->process ? 'business' : '' }} {{ $status->supplier ? 'premium' : '' }} {{ !$station->active ? 'disabled' : '' }}">
                      <div class="pricing-table-header">
                          <h4><strong>{{ ucfirst($status->short_name) }}</strong></h4>
                          <p>{{ $station->created_at_for_humans }}</p>
                          <p>@if($station->consumption) <span class="badge badge-success">Ya consumido</span> @endif </p>
                          @if($station->product_station()->exists())
                            <p>@if($station->product_station()->first()->not_consider) <span class="badge badge-danger">No considerado para BOM Global</span> @endif </p>
                          @endif
                      </div>
                      <div class="price"><strong>#{{ $station->id }}</strong> / {{ ucfirst($status->short_name) }} <span class="glyphicon glyphicon-ok"></span></div>
                      <div class="pricing-body">

                          <livewire:backend.components.edit-field :model="'\App\Models\Station'" :entity="$station" :field="'comment'" :key="'stations'.$station->id"/>

                          <br>

                          @if($status->to_add_users)
                            <div class="form-group mb-3">
                                @if($station->service_type_id)
                                  <h2 class="text-center">{{ ucwords(optional($station->service_type)->name) }}</h2>
                                @endif
  
                                <label class="mt-2" for="user-select-{{ $station->id }}">Seleccionar Usuario</label>
                                <select id="user-select-{{ $station->id }}" wire:change="savePersonalId({{ $station->id }}, $event.target.value)" class="form-control" onfocus="disableKeyUpDown({{ $station->id }})">
                                    <option value="">Seleccionar</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $station->personal_id ? 'selected' : '' }}>
                                            {{ ucwords(strtolower($user->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                          @endif

                          <ul class="pricing-table-ul list-group">
                            <table class="table table-sm">
                              <tbody>
                                @foreach($station->product_station->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product_station)
                                <tr style="{{ ($product_station->getQuantityBelongTo() < $product_station->quantity) ? 'background-color: #49e82b;' : '' }}">
                                  <th scope="row" class="{{ !$product_station->active ? 'text-decoration-line-through' : '' }}"> {!! $product_station->product->full_name_link !!} </th>
                                  <td><span class="badge badge-dark badge-pill">{{ $product_station->quantity }}</span></td>
                                  <td class="text-primary">{{ $product_station->metadata['open'] }}</td>
                                  <td class="text-success">{{ $product_station->metadata['closed'] }}</td>
                                </tr>

                                @if($product_station->metadata_product_station)
                                  @foreach($product_station->metadata_product_station as $key => $quantity)
                                    @php($getTheStation = \App\Models\Station::find($key))

                                    <tr>
                                      <td class="text-left" colspan="4"><strong>{{ $quantity }}</strong> Proveniente de: <u>{{ $getTheStation->status->name }}</u></td>
                                    </tr>
                                  @endforeach
                                @endif
                                @endforeach
                                <tr>
                                  <th></th>
                                  <th class="text-center"> {{ $station->total_products_station }} </th>
                                  <td colspan="2"></td>
                                </tr>
                              </tbody>
                            </table>
                        </ul>

                          <p class="text-center mt-3">
                            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{ $station->id }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $station->id }}">
                              Despliegue
                            </a>
                          </p>
                          <div class="collapse show" id="collapseExample{{ $station->id }}">
                            <div class="card card-body mt-3">
                              <div class="list-group">
                                <a wire:click="closeStation({{ $station->id }})" class="list-group-item list-group-item-action" wire:loading.attr="disabled" onclick="confirm('¿Recibir todo?') || event.stopImmediatePropagation()">Recibir Seguimiento</a>
                                @if($status->initial_lot)
                                  @if(!$station->consumption)
                                    <a wire:click="makeConsumption({{ $station->id }})" class="list-group-item list-group-item-action">
                                      Consumir Materia Prima
                                    </a>
                                  @else
                                    <a href="{{ route('admin.station.checklist', $station->id) }}" target="_blank" class="list-group-item list-group-item-action"> Checklist <i class="fas fa-external-link-alt m-1"></i></a>

                                    <a href="{{ route('admin.station.checklist_ticket', $station->id) }}" target="_blank" class="list-group-item list-group-item-action list-group-item-success"> Consumido - Ticket <i class="fas fa-external-link-alt m-1"></i></a>
                                  @endif

                                  @if(!$station->consumption)
                                    <a wire:click="makeToggleNotConsider({{ $station->id }})" class="list-group-item list-group-item-action" wire:loading.attr="disabled" onclick="confirm('¿Cambiar estado?') || event.stopImmediatePropagation()">
                                      @if(!$station->product_station()->first()->not_consider)
                                        No considerar para BOM
                                      @else
                                        Considerar para BOM
                                      @endif
                                    </a>
                                  @endif

                                @endif

                                @if($status->supplier)
                                  <a wire:click="makeToggleNotConsider({{ $station->id }})" class="list-group-item list-group-item-action" wire:loading.attr="disabled" onclick="confirm('¿Cambiar estado?') || event.stopImmediatePropagation()">  Solicitado
                                  </a>
                                @endif
                                @if($status->initial_lot)
                                  <a href="{{ route('admin.order.ticket_materia_station', [$order_id, $station->id]) }}" target="_blank" class="list-group-item list-group-item-action"> Ver BOM  <i class="fas fa-external-link-alt m-1"></i></a>
                                @endif
                                <a href="{{ route('admin.station.ticket', $station->id) }}" target="_blank" class="list-group-item list-group-item-action"> <i class="cil-print"></i> Ticket <i class="fas fa-external-link-alt m-1"></i></a>

                                <a href="{{ route('admin.station.edit', $station->id) }}" target="_blank" class="list-group-item list-group-item-action"> Detalles <i class="fas fa-external-link-alt m-1"></i></a>

                                @if($status->final_process)
                                  <a wire:click="makeOutput({{ $station->id }})" class="list-group-item list-group-item-action">Dar Salida <i class="cil-arrow-thick-right"></i> </a>
                                @endif

                                @if($status->initial_process)
                                  <a wire:click="sendToStock({{ $station->id }})" class="list-group-item list-group-item-action"> Enviar todo a Almacén <i class="cil-arrow-thick-right"></i> </a>
                                @endif

                                @if($status->batch || $status->not_restricted)
                                  <a wire:click="makeDeletion({{ $station->id }})" class="list-group-item list-group-item-action">
                                    Eliminar
                                  </a>
                                @endif

                              </div>
                            </div>
                          </div>

                        </div>
                  </div>
              </div>
            @endforeach


            {{-- Lot, without Initial Lot --}}
            @if($status->batch && !$status->initial_lot)
              @foreach($model->lot_stations as $station)
                <div class="col-md-4 col-sm-6">
                    <div class="pricing-table-3 {{ $status->batch ? 'basic' : '' }} {{ !$station->active ? 'disabled' : '' }}" style="border: dashed 5px green;">
                        <div class="pricing-table-header">
                            <h4><strong>@lang('Tracking')</strong></h4>
                            <p><span class="badge badge-danger">Lote</span> {{ ucfirst(optional($station->status)->name) }}</p>
                        </div>
                        <div class="price"><strong>#{{ $station->id }}</strong> / @lang('Tracking')</div>
                        <div class="pricing-body">
                            <ul class="pricing-table-ul list-group">
                                <table class="table">
                                  <tbody>
                                    {{-- @json($quantity) --}}

                                    @foreach($station->product_station->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product_station)
                                    <tr>
                                      <th scope="row"> <em class="text-white bg-dark">{!! '&nbsp'.$product_station->id.'&nbsp&nbsp' !!}</em> {!! $product_station->product->full_name_link !!}</th>
                                      <td><span class="badge badge-dark badge-pill">{{ $product_station->quantity }}</span></td>
                                      <td class="text-primary"> {{ $product_station->metadata['open'] }} <br>-</td>
                                      <td class="text-success"> {{ $product_station->metadata['closed'] }} <br>-</td>

                                      {{-- <td class="text-primary"> {{ $product_station->getQuantitiesByStatusOpen($status_id) }} </td> --}}
                                      {{-- <td class="text-success"> {{ $product_station->getQuantitiesByStatusClosed($status_id) }} </td> --}}

                                      <td>
                                          <input type="number" 
                                              wire:model.defer="quantity.{{ $station->id }}.{{ $product_station->id }}.available"
                                              class="form-control text-center"
                                              style="color: red;"
                                              wire:keydown.tab="emitUpdatedInStation({{ $station->id }})"
                                              wire:keydown.escape="emitUpdatedInStation({{ $station->id }})"
                                              placeholder="{{ $product_station->getAvailableBatch($status_id, $station->id) }}"
                                          >
                                          @error('quantity.'.$station->id.'.'.$product_station->id.'.available') 
                                              <span class="error" style="color: red;">
                                                  <p>@lang('Check the quantity')</p>
                                              </span> 
                                          @enderror
                                      </td>

                                    </tr>
                                    @endforeach
                                    <tr>
                                      <th></th>
                                      <th class="text-center"> {{ $station->total_products_station }} </th>
                                      <td colspan="2"></td>
                                      <td class="text-center" >
                                        <a href="javascript:void(0);" style="display: block; border-width: 2px; border-style: dashed; border-color: red;"  wire:click="emitUpdatedInStation({{ $station->id }})" class="text-dark text-center">
                                          &nbsp; {!! isset($sumValueStation[$station->id]) && $sumValueStation[$station->id] != 0 ?  '<strong class="text-danger">'. $sumValueStation[$station->id] .'</strong>' : '' !!}
                                        </a>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="4"></td>
                                      <td class="text-center">
                                        <button type="button" wire:click="saveInStation({{ $station->id }})" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                                          &nbsp; &#128190; &nbsp;
                                        </button>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>

                            </ul>

                            <p class="text-center mt-3">
                              <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{ $station->id }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $station->id }}">
                                Despliegue Lote
                              </a>
                            </p>
                            <div class="collapse show" id="collapseExample{{ $station->id }}">
                              <div class="card card-body mt-3">
                                <div class="list-group">
                                  {{-- <a wire:click="closeLot({{ $station->id }})" class="list-group-item list-group-item-action" onclick="confirm('¿Recibir todo?') || event.stopImmediatePropagation()">Recibir Seg. Lot</a> --}}
                                  <a href="{{ route('admin.station.edit', $station->id) }}" target="_blank" class="list-group-item list-group-item-action"> Detalles <i class="fas fa-external-link-alt m-1"></i></a>
                                </div>                              
                              </div>
                            </div>

                          </div>
                    </div>
                </div>
              @endforeach
            @endif


            {{-- Process, without Initial Process --}}
            @if($status->process && !$status->initial_process)
              @foreach($model->process_stations as $station)
                <div class="col-md-4 col-sm-6">
                    <div class="pricing-table-3 {{ $status->process ? 'business' : '' }} {{ !$station->active ? 'disabled' : '' }}" style="border: dashed 5px green;">
                        <div class="pricing-table-header">
                            <h4><strong>@lang('Process') {{ $station->id }}</strong></h4>
                            <p><span class="badge badge-danger">Seguimiento</span> {{ ucfirst(optional($station->status)->name) }}</p>
                        </div>
                        <div class="price"><strong>#{{ $station->id }}</strong> / @lang('Process')</div>
                        <div class="pricing-body">
                            <ul class="pricing-table-ul list-group">
                                <table class="table">
                                  <tbody>
                                    @foreach($station->product_station->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product_station)
                                    <tr>
                                      <th scope="row"> <em class="text-white bg-dark">{!! '&nbsp'.$product_station->id.'&nbsp&nbsp' !!}</em> {!! $product_station->product->full_name_link !!}</th>
                                      <td><span class="badge badge-dark badge-pill">{{ $product_station->quantity }}</span></td>
                                      <td class="text-primary"> {{ $product_station->getQuantitiesByStatusOpen($status_id) }} </td>
                                      <td class="text-success"> {{ $product_station->getQuantitiesByStatusClosed($status_id) }} </td>

                                      {{-- <td>
                                        <input type="number" 
                                            wire:model.debounce.700ms="quantity.{{ $product_station->id }}.available"
                                            class="form-control text-center"
                                            style="color: red;"
                                            placeholder="{{ $status->not_restricted ? $product_station->metadata['closed'] : $product_station->getAvailableInitialProcess($status_id) }}"
                                        >
                                        @error('quantity.'.$product_station->id.'.available') 
                                          <span class="error" style="color: red;">
                                            <p>@lang('Check the quantity')</p>
                                          </span> 
                                        @enderror
                                      </td> --}}

                                      <td>
                                          <input type="number" 
                                              wire:model.defer="quantity.{{ $station->id }}.{{ $product_station->id }}.available"
                                              class="form-control text-center"
                                              style="color: red;"
                                              wire:keydown.tab="emitUpdatedInStation({{ $station->id }})"
                                              wire:keydown.escape="emitUpdatedInStation({{ $station->id }})"
                                              placeholder="{{ $status->not_restricted ? $product_station->metadata['closed'] : $product_station->getAvailableInitialProcess($status_id) }}"
                                          >
                                          @error('quantity.'.$station->id.'.'.$product_station->id.'.available') 
                                              <span class="error" style="color: red;">
                                                  <p>@lang('Check the quantity')</p>
                                              </span> 
                                          @enderror
                                      </td>

                                    </tr>
                                    @endforeach

                                    <tr>
                                      <th></th>
                                      <th class="text-center"> {{ $station->total_products_station }} </th>
                                      <td colspan="2"></td>
                                      <td class="text-center">

                                        <a href="javascript:void(0);" style="display: block; border-width: 2px; border-style: dashed; border-color: red;"  wire:click="emitUpdatedInStation({{ $station->id }})" class="text-dark text-center">
                                          &nbsp; {!! isset($sumValueStation[$station->id]) && $sumValueStation[$station->id] != 0 ?  '<strong class="text-danger">'. $sumValueStation[$station->id] .'</strong>' : '' !!}
                                        </a>

                                      </td>
                                    </tr>

                                    <tr>
                                      <td colspan="4"></td>
                                      <td class="text-center">
                                        <button type="button" wire:click="saveInStation({{ $station->id }})" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                                          &nbsp; &#128190; &nbsp;
                                        </button>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>

                            </ul>

                            <p class="text-center mt-3">
                              <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{ $station->id }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $station->id }}">
                                Despliegue en Proceso
                              </a>
                            </p>
                            <div class="collapse show" id="collapseExample{{ $station->id }}">
                              <div class="card card-body mt-3">
                                <div class="list-group">
                                  {{-- <a wire:click="closeLot({{ $station->id }})" class="list-group-item list-group-item-action" onclick="confirm('¿Recibir todo?') || event.stopImmediatePropagation()">Recibir Seg. Process</a> --}}
                                  <a href="{{ route('admin.station.edit', $station->id) }}" target="_blank" class="list-group-item list-group-item-action"> Detalles <i class="fas fa-external-link-alt m-1"></i></a>
                                </div>                              
                              </div>
                            </div>

                          </div>
                    </div>
                </div>
              @endforeach
            @endif
          </div>

          <div class="row">
            <div class="col text-center mt-4">
              <div class="mb-4 btn-group" role="group" aria-label="Basic example">
                @if($previous_status)
                  <a href="{{ route('admin.order.station', [$model->id, $previous_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $previous_status->name ?? null }}">
                    @if($previous_status->to_add_users)<i class="c-icon  c-icon-4x cil-people"></i>@endif
                    @lang('Previous status')
                  </a>
                @endif

                @if($next_status)
                  <a href="{{ route('admin.order.station', [$model->id, $next_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $next_status->name ?? null }}">
                    @if($next_status->to_add_users)<i class="c-icon  c-icon-4x cil-people"></i>@endif
                    @lang('Next status') 
                    @if($next_status->finial_process) &nbsp; <i class="cil-running"></i> @endif
                  </a>
                @endif
              </div>
            </div>
          </div>



    </x-slot>
</x-backend.card>


@push('after-scripts')
<script>
    function disableKeyUpDown(stationId) {
        document.getElementById('user-select-' + stationId).addEventListener('keydown', function(event) {
            if (event.key === 'ArrowUp' || event.key === 'ArrowDown' || event.key === 'PageUp' || event.key === 'PageDown') {
                event.preventDefault();
            }
        });
    }
</script>

<script type="text/javascript">
    function redirect(goto) {
      var conf = confirm("¿Redireccionar?");
      if (conf && goto != '') {
        window.location = goto;
      }
    }

  var selectEl = document.getElementById('redirectSelect');

  selectEl.onchange = function() {
    if (this.value.startsWith('http')) {
      var goto = this.value;
      redirect(goto);
    }
  };

</script>
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
</script>
@endpush