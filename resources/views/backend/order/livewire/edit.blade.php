@push('after-styles')
  <style type="text/css">
  </style>
@endpush

<x-backend.card>

  <x-slot name="header">
    <h4>{{ $model->type_order_clear }} #{!! $model->folio_or_id !!}</h4>
  </x-slot>

  <x-slot name="headerActions">

    @if($model->isOrder() && !$model->isFromStore())

      @if($model->previousOrder())
        <x-utils.link class="card-header-action" :href="route('admin.order.edit', $model->previousOrder())" icon="cil-chevron-double-left" :text="__('Previous')" />
      @endif
      @if($model->nextOrder())
        <x-utils.link class="card-header-action" :href="route('admin.order.edit', $model->nextOrder())" icon="cil-chevron-double-right" :text="__('Next')" />
      @endif

    @endif

    <x-utils.link class="card-header-action" :href="$model->from_store ? route('admin.store.all.index') : route('admin.order.index')" icon="fa fa-chevron-left" :text="__('Back')" />
  </x-slot>
  <x-slot name="body">


    @if(!$model->approved)
      <div class="alert alert-danger" role="alert">
        @lang('Not approved') <a wire:click="approve" href="#">@lang('Approve')</a> 
      </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="row ">
      <div class="col-12 col-sm-12 {{ $orderExists || $requestExists ? 'col-md-12' : 'col-md-12' }}">
        <div class="card card-product_not_hover card-flyer-without-hover">
          @if($slug)
            <div class="card-header">
              @lang('Tracking number'): <strong class="text-primary">{{ $slug }}</strong>
              <a href="{{ route('frontend.track.show', $slug) }}" target=”_blank”>
                <span class="badge badge-primary"> 
                  @lang('Go to track')
                  <i class="cil-external-link"></i>
                </span>
              </a>
            </div>
          @endif
          <div class="card-body">
            <h5 class="card-title">#{!! $model->folio_or_id !!}</h5>
            <p class="card-text">
              <div class="form-row ">
                
                @if($slug)
                  <div class="col-md-3 mb-3">
                    <div class="visible-print text-left" wire:ignore.self>
                      {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(route('frontend.track.show', $slug)); !!}
                      <p class="mt-2">@lang('Scan me for go track')</p>
                    </div>
                  </div>
                @endif

                <div class="col-md-9 mb-3">
                  <div class="row">
                    <div class="col-6 col-lg-6">
                      <strong class="text-info">{!! $model->user_name !!}</strong>
                    </div>
                    @if(optional($model->user)->customer)
                      @if(optional($model->user)->customer['phone'])
                      <div class="col-6 col-lg-6">
                        <strong>@lang('Phone'): </strong>{!! optional($model->user)->customer['phone'] ?? '' !!}
                      </div>
                      @endif
                    @endif
                    <br>
                    @if(optional($model->user)->customer)
                      @if(optional($model->user)->customer['address'])
                      <div class="col-6 col-lg-6">
                        <strong>@lang('Address'): </strong>{!! optional($model->user)->customer['address'] ?? '' !!}
                      </div>
                      @endif
                    @endif
                    @if(optional($model->user)->customer)
                      @if(optional($model->user)->customer['rfc'])
                      <div class="col-6 col-lg-6">
                        <strong>RFC: </strong>{!! optional($model->user)->customer['rfc'] ?? '' !!}
                      </div>
                      @endif
                    @endif
                    @if($orderExists)
                      <div class="col-6 col-lg-6">


                      </div>
                    @endif
                  </div>

                  <div class="row mt-3">
                    <div class="col-6 col-lg-6">
                      {{ $model->date_entered->format('d-m-Y') }}

                    </div>
                    <div class="col-6 col-lg-6">
                      {{ $model->created_at }}
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->info_customer }} --}}
                      <x-input.input-alpine nameData="isInfo_customer" :inputText="$isInfo_customer" :originalInput="$isInfo_customer" wireSubmit="saveinfocustomer" modelName="info_customer" maxlength="300" className="" />
                      @error('info_customer') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->comment }} --}}
                      <x-input.input-alpine nameData="isComment" :inputText="$isComment" :originalInput="$isComment" wireSubmit="savecomment" modelName="comment" maxlength="300" className="" />
                      @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->comment }} --}}
                      <x-input.input-alpine nameData="isObservation" :inputText="$isObservation" :originalInput="$isObservation" wireSubmit="saveobservation" modelName="observation" maxlength="300" className="" />
                      @error('observation') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>

                  @if($model->filterByDiscount() && $model->total_payments < 1)
                    <div class="row mt-3">
                      <div class="col-12 col-lg-12">
                        {{-- {{ $model->discount }} --}}
                        <x-input.input-alpine nameData="isDiscount" :inputText="$isDiscount" :originalInput="$isDiscount" wireSubmit="savediscount" modelName="discount" maxlength="300" className="" />
                        @error('discount') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                      </div>
                    </div>
                  @endif

                  @if($model->branch_id === 0)
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      @if($model->isQuotation())
                        <x-input.input-alpine nameData="isRequest" :inputText="$isRequest" :originalInput="$isRequest" wireSubmit="saverequest" modelName="request" maxlength="300" className="" />
                        @error('request') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                      @else
                        @if($model->request)
                          <strong>@lang('Request number'):</strong> {{ $model->request }}
                        @endif
                      @endif
                    </div>
                  </div>
                  @endif
                </div>
              </div>

            </p>

            @if($orderExists)

            <div class="form-row">
              <div class="col-md-4 mb-3" >
                @lang('Production status'):
                <em class="text-primary">
                  <strong>
                    {!! $model->last_status_order->status->name ?? '<span class="badge badge-secondary">'.__('undefined status').'</span>' !!}
                  </strong>
                </em>
                <div wire:loading wire:target="updateStatus" class="loading"></div>
              </div>
              <div class="col-md-4 mb-3">
                {{-- <a href="{{ route('admin.order.advanced', $order_id) }}" style="color:#1ab394;">
                  <p> @lang('Advanced options') </p>
                </a> --}}
              </div>
              <div class="col-md-4 mb-3 text-left">
                {{-- @if($model->exist_user_departament)
                  <a href="{{ route('admin.order.sub', $order_id) }}" style="color:purple;">
                    <p> @lang('I want to assign suborders') <i class="cil-library"></i></p> 
                  </a>
                @endif --}}

                @php
                  $colors_counter = 0;
                  $colors = array(0=>"primary", 1=>"info", 2=>"secondary", 3=>"light");
                @endphp

                <div class="list-group">
                  @foreach($model->suborders as $suborder)
                    <a href="{{ route($from_store ? 'admin.order.edit' : 'admin.store.product.index', $suborder->id) }}" class="list-group-item list-group-item-action flex-column align-items-start 
                      @if($colors_counter <= 3)
                        list-group-item-{{ $colors[$colors_counter] }}
                      @endif
                    ">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 mr-1 text-left"><strong> #{{ $suborder->id}} </strong> {{ optional($suborder->departament)->name }}</h6>
                        <small class="text-center">{{ $suborder->date_diff_for_humans }}</small>
                      </div>
                    </a>
                      <?php $colors_counter++; ?>
                  @endforeach
                </div>
              </div>
            </div>
            @endif

            <div class="text-center">
              <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  S/Desglose
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  @if(!$model->isQuotation())
                    <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.ticket_order', [$order_id, true]) : route('admin.store.all.ticket_order', [$order_id, true]) }}" target="_blank">Ticket</a>
                  @endif
                  @if(!$model->isOutputProducts())
                    <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.print', [$order_id, true]) : route('admin.store.all.print', [$order_id, true]) }}" target="_blank">Carta</a>
                  @endif
                </div>
              </div>

              <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  P/Factura
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  @if(!$model->isQuotation())
                    <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.ticket_order', $order_id) : route('admin.store.all.ticket_order', $order_id) }}" target="_blank">Ticket</a>
                  @endif
                  <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.print', $order_id) : route('admin.store.all.print', $order_id) }}" target="_blank">Carta</a>
                </div>
              </div>

              @if(!$model->isQuotation())
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    S/Precios
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.ticket_order', [$order_id, true, true]) : route('admin.store.all.ticket_order', [$order_id, true, true]) }}" target="_blank">Ticket</a>
                        <a class="dropdown-item" href="{{ route('admin.order.printgropedwithoutprice', $order_id) }}" target="_blank">Carta - Agrupado</a>

                  </div>
                </div>
              @endif

              @if(!$model->isOutputProducts())
                <a type="button" href="{{ !$from_store ? route('admin.order.print', [$order_id, 0, true]) : route('admin.store.all.print', [$order_id, 0, true]) }}" class="btn btn-secondary" target="_blank">Imprimir productos agrupados</a>
              @endif

              @if(!$from_store && $model->materials_order()->exists())
                <a type="button" href="{{ route('admin.order.ticket_materia', $order_id) }}" class="btn btn-warning text-white" target="_blank">@lang('Feedstock')</a>
              @endif


            </div>
            <div class="text-center mt-2">
              @if(!$model->isQuotation() and !$model->isOutputProducts())
                <a type="button" href="{{ route('admin.order.service_orders', $order_id) }}" class="btn btn-link">@lang('Service Order') 
                  @if($model->service_orders()->count())
                    <span class="badge badge-success">{{ $model->service_orders()->count() }}</span>
                  @endif
                </a>

                <a type="button" href="{{ route('admin.order.children_orders', $order_id) }}" class="btn btn-link">Crear Orden Produccion 
                  @if($model->order_children()->count())
                    <span class="badge badge-success">{{ $model->order_children()->count() }}</span>
                  @endif
                </a>

              @endif

              <a type="button" href="{{ route('admin.store.all.newformat', $order_id) }}" class="btn btn-secondary" target="_blank">Nuevo Formato</a>
            </div>

   {{--          @if(!$model->isQuotation())
              <a href="{{ !$from_store ? route('admin.order.ticket_order', $order_id) : route('admin.store.all.ticket_order', $order_id) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
                <ins>
                  Ticket
                </ins>
              </a>

              @if(!$model->isOutputProducts())
                <a href="{{ !$from_store ? route('admin.order.ticket_order', [$order_id, true]) : route('admin.store.all.ticket_order', [$order_id, true]) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
                  <ins>
                    Ticket sin desglose
                  </ins>
                </a>
              @endif

            @endif

            <a href="{{ !$from_store ? route('admin.order.print', $order_id) : route('admin.store.all.print', $order_id) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
              <ins>
                @lang('Print for invoice')
              </ins>
            </a>

            @if(!$model->isOutputProducts())
            <a href="{{ !$from_store ? route('admin.order.print', [$order_id, true]) : route('admin.store.all.print', [$order_id, true]) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
              <ins>
                Imprimir sin desglose
              </ins>
            </a>

            <a href="{{ !$from_store ? route('admin.order.print', [$order_id, 0, true]) : route('admin.store.all.print', [$order_id, 0, true]) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
              <ins>
                Imprimir productos agrupados
              </ins>
            </a>
            @endif

            @if(!$from_store && $model->materials_order()->exists())
              <a href="{{ route('admin.order.ticket_materia', $order_id) }}" class="card-link text-warning" target="_blank"><i class="cil-print"></i>
                <ins>
                  @lang('Feedstock')
                </ins>
              </a>
            @endif

            @if($model->materials_order()->exists())
              <a href="{{ route('admin.order.short_ticket_materia', $order_id) }}" class="card-link text-warning" target="_blank"><i class="cil-print"></i>
                <ins>
                  @lang('Tackle and more')
                </ins>
              </a>
            @endif --}}

            {{-- @if($model->isOrder() or $model->isRequest())
              <a href="{{ route('admin.order.ticket_monitoring', $order_id) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
                <ins>
                  @lang('Monitoring dashboard ticket')
                </ins>
              </a>
            @endif --}}


{{--             @if(!$model->isQuotation() and !$model->isOutputProducts())
              <a href="{{ route('admin.order.service_orders', $order_id) }}" class="card-link">@lang('Create service order')</a>
            @endif
 --}}
          </div>

          @if( ( ($model->user_id || $model->departament_id) || $model->isFromStore() ) && (!$model->isQuotation()))
            <div class="card-footer text-center">
              <div class="row">
                @if(!$model->isOutputProducts())
                <div class="col-6 col-lg-6">
                  <p><strong>Total: </strong> ${{ number_format($model->total_by_all_with_discount, 2) }}</p>

                  @if($model->discount)
                    <p><strong>@lang('Discount') {{ '(%'.$model->discount.')' }}:</strong> ${{ number_format($model->calculate_discount_all, 2) }}</p>
                  @endif

                  <p><strong>@lang('Payment'):</strong> {!! $model->payment_label !!} ${{  number_format((float)$model->total_payments, 2) }}</p>
                  @if($model->total_payments_remaining > 0)
                    <p><strong>@lang('Remaining'):</strong> ${{ number_format((float)$model->total_payments_remaining, 2)  }}</p>
                    <h5 class="mt-2"><a href="#!" data-toggle="modal" wire:click="$emitTo('backend.order.create-payment', 'createmodal', {{ $order_id }})" data-target="#createPayment" style="color: #ee2e31;">@lang('Create payment')</a></h5>
                  @endif
                  <br>
                  <a href="{{ !$from_store ? route('admin.order.records_payment', $order_id) : route('admin.store.all.records_payment', $order_id) }}" class="card-link">@lang('View payment records')</a>
                </div>
                @endif
                <div class="col-6 col-lg-6">
                  <strong>@lang('Delivery'):</strong> {{ $last_order_delivery_formatted ?? __('Pending') }}
                  <select class="form-control text-center mt-2" style="border: 1px solid #fe8a71" wire:model.debounce.800ms="order_status_delivery">
                    <option value="" hidden>@lang('Select order delivery status')</option>
                    @foreach($OrderStatusDelivery as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                  </select>
                  <br>
                  @if(!$from_store)
                    <a href="{{ route('admin.order.records_delivery', $order_id) }}" class="card-link">@lang('View delivery records')</a>
                  @endif
                </div>
              </div>
            </div>
          @endif

          <div class="card-footer text-muted text-center">
            @lang('Created'): {{ $model->date_diff_for_humans }} - {{ __('Captured').': '. optional($model->audi)->name }}
          </div>
          <div class="card-footer text-muted text-center">

            @if($model->seller_id)
              @lang('Seller'): {{ optional($model->seller)->name }}
            @else
              @if ($model->audi_id == auth()->id())
                  <button 
                      type="button" 
                      class="btn btn-primary mt-2" 
                      wire:click="$set('seller_id', {{ auth()->id() }})">
                      Asignarme como vendedor
                  </button>
              @endif

            @endif

            <select class="form-control text-center mt-2" style="border: 2px solid #003092; border-style: dashed;" wire:model.debounce.800ms="seller_id">
              <option value="" hidden> {{ $model->seller_id ? __('Change seller') :  __('Select seller') }} </option>
              @foreach($usersToSell as $key => $user)
                <option value="{{ $user['id'] }}">{{ ucwords(strtolower($user['name'])) }}</option>
              @endforeach
            </select>


          </div>
          @if($model->quotation && $model->isOrder())
            <div class="card-footer text-muted text-center h5">
              {!! __('Quotation'). ' <strong class="text-danger">: #'.$model->quotation.'</strong>' !!}
            </div>
          @endif

        </div>


        <div class="card card-edit card-product_not_hover card-flyer-without-hover">
          <div class="card-body">
            @if($orderExists)

            <div class="row">
              @if($model->materials_order()->doesntExist())
              <div class="col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="custom-control custom-switch custom-control-inline">
                      <input type="checkbox" wire:model="previousMaterialByProduct" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                      <label class="custom-control-label" for="customRadioInline1">
                        @lang('See raw material by product, prior to consumption')
                     </label>
                    </div>
                  </div>
                </div>
              </div>
              @else
              <div class="col-sm-6">
                <div class="card border-warning">
                  <div class="card-body">
                    <div class="custom-control custom-switch custom-control-inline">
                      <input type="checkbox" wire:model="maerialAll" id="customRadioInline2" name="customRadioInline2" class="custom-control-input">
                      <label class="custom-control-label" for="customRadioInline2">
                        Ver concentrado de materia prima ya consumido
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              @endif
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <caption>
                  <a href="#!" class="mt-2 ml-2" data-toggle="modal" wire:click="$emitTo('backend.order.add-service', 'createmodal', {{ $order_id }}, 1, {{ $from_store }})" data-target="#addService" style="color: #ee2e31;">@lang('Add service')</a>
                </caption>
                <thead style="background-color: #321fdb; border-color: #321fdb; color: white;">
                  <tr class="text-center">
                    <th colspan="4">@lang('Order')</th>
                  </tr>
                  <tr class="thead-dark">
                    <th >@lang('Product')</th>
                    <th>@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_order->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                  <tr>
                    <td>
                      <a href="{{ route('admin.product.consumption_filter', $product->product_id) }}" target=”_blank”> <span class="badge badge-warning"> <i class="cil-color-fill"></i> <em class="text-white">@lang('Consumption')</em> </span></a>
                      {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                      {{ $product->product->code_subproduct_clear }}
                      {!! $product->product->full_name_link !!}
                    </td>
                    <td class="text-center">
                      ${{ $product->price }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
                    </td>
                    <td class="text-center">{{ $product->quantity }}</td>
                    <td class="text-center">
                      ${{ number_format((float)$product->total_by_product, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->total_by_product) }} </div>
                    </td>
                  </tr>

                  <tr>
                    <th class="text-right">
                      <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                    </th>
                    <th class="text-left" colspan="3">
                      <livewire:backend.components.edit-field :model="'\App\Models\ProductOrder'" :entity="$product" :field="'comment'" :key="'comments'.$product->id"/>
                    </th>
                  </tr>

                    {{-- @json($product->gettAllConsumption()) --}}
                    @if($previousMaterialByProduct)

                      @if($product->gettAllConsumption() != 'empty')
                          <tr class="table-warning text-right font-italic font-weight-bold">
                            <td colspan="2">
                              Materia prima
                            </td>
                            <td>
                              Consumo Unitario
                            </td>
                            <td>
                              Total
                            </td>
                          </tr>

                        @foreach($product->gettAllConsumption() as $key => $consumption)
                          <tr class="table-warning text-right font-italic">
                            <td colspan="2">
                              {{ $consumption['material'] }}
                            </td>
                            <td>
                                {{ rtrim(rtrim(sprintf('%.8F', $consumption['unit']), '0'), ".") }}
                            </td>
                            <td>
                                {{ rtrim(rtrim(sprintf('%.8F', $consumption['quantity']), '0'), ".") }}
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr class="table-danger text-center font-italic">
                            <td colspan="4">
                              <p>Sin materia prima definida, aún.</p>
                            </td>
                        </tr>
                      @endif
                    @endif

                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{!! $model->total_products_and_services_label !!}</td>
                    <td class="text-center">
                      ${{ number_format((float)$model->total_order, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($model->total_order) }} </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            @endif

            @if($saleExists)
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <caption>
                  <a href="#!" class="mt-2 ml-2" data-toggle="modal" wire:click="$emitTo('backend.order.add-service', 'createmodal', {{ $order_id }}, '2', {{ $from_store }})" data-target="#addService" style="color: #ee2e31;">@lang('Add service')</a>
                </caption>
                <thead style="background-color: #248f48; border-color: #218543; color: white;">
                  <tr class="text-center">
                    <th colspan="4" >@lang('Sale')</th>
                  </tr>
                  <tr class="thead-dark">
                    <th>@lang('Product')</th>
                    <th>@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_sale->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                  <tr >
                    <td>
                      {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                      {{ $product->product->code_subproduct_clear }}
                      {!! $product->product->full_name !!}
                    </td>
                    <td class="text-center">
                      ${{ $product->price }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
                    </td>
                    <td class="text-center">{{ $product->quantity }}</td>
                    <td class="text-center">
                      ${{ number_format((float)$product->total_by_product, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->total_by_product) }} </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="text-right">
                      <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                    </th>
                    <th class="text-left" colspan="3">
                      <livewire:backend.components.edit-field :model="'\App\Models\ProductOrder'" :entity="$product" :field="'comment'" :key="'comments'.$product->id"/>
                    </th>
                  </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{!! $model->total_products_and_services_label !!}</td>
                    <td class="text-center">
                      ${{ number_format((float)$model->total_sale, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($model->total_sale) }} </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
            @endif

            @if($requestExists)
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">

                @if($model->orders_delivery->count() <= 1)
                <caption>
                  <a href="#!" class="mt-2 ml-2" data-toggle="modal" wire:click="$emitTo('backend.order.add-service', 'createmodal', {{ $order_id }}, '5', {{ $from_store }})" data-target="#addService" style="color: #ee2e31;">@lang('Add service')</a>


                    <a href="#!" data-toggle="modal" wire:click="searchproduct()" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Add product')</a>

                </caption>
                @endif
                <thead style="background-color: coral; border-color: #218543; color: white;">
                  <tr class="text-center">
                    <th colspan="4" >@lang('Request')</th>
                  </tr>
                  <tr class="thead-dark">
                    <th>@lang('Product')</th>
                    <th>@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_request->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                  <tr>
                    <td>
                      {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                      {{ $product->product->code_subproduct_clear }}
                      {!! $product->product->full_name !!}
                    </td>
                    <td class="text-center">
                      ${{ $product->price }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
                    </td>
                    <td class="text-center">{{ $product->quantity }}</td>
                    <td class="text-center">
                      ${{ number_format((float)$product->total_by_product, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->total_by_product) }} </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="text-right">
                      {{-- Ajustar --}}
                      <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                    </th>
                    <th class="text-left" colspan="3">
                      <livewire:backend.components.edit-field :model="'\App\Models\ProductOrder'" :entity="$product" :field="'comment'" :key="'comments'.$product->id"/>
                    </th>
                  </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{!! $model->total_products_and_services_label !!}</td>
                    <td class="text-center">
                      ${{ number_format((float)$model->total_request, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($model->total_request) }} </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
            @endif

            @if($productsOutputExists)

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">

                <thead style="background-color: #86FFCF; border-color: #FAFA33; color: dark;">
                  <tr class="text-center">
                    <th colspan="2" >@lang('Output Products')</th>
                  </tr>
                  <tr class="thead-dark">
                    <th>@lang('Product')</th>
                    <th class="text-center">@lang('Quantity')</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_output->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                    <tr>
                      <td>
                        {{ $product->product->code_subproduct_clear }}
                        {!! $product->product->full_name !!}
                      </td>

                      <td class="text-center">
                        {{ $product->quantity }}
                      </td>

                    </tr>
                    <tr>
                      <th class="text-right">
                        <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                      </th>
                      <th class="text-left" colspan="3">
                        <livewire:backend.components.edit-field :model="'\App\Models\ProductOrder'" :entity="$product" :field="'comment'" :key="'comments'.$product->id"/>
                      </th>
                    </tr>
                  @endforeach

                </tbody>
              </table>
            </div>

            @endif

            @if($quotationExists)

            @if($model->product_quotation->count() >= 1)
              <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
                <em class=" mt-2"> @lang('Change prices without taxes')</em>
                  <div class="col-md-2 mt-2">
                    <div class="form-check">
                      <label class="c-switch c-switch-label c-switch-primary">
                        <input type="checkbox" wire:model="showPriceWithoutTax" class="c-switch-input">
                        <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
                      </label>
                    </div>
                  </div>
              </div>
            @endif

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <caption>

                  <a href="#!" class="mt-2 ml-2" data-toggle="modal" wire:click="$emitTo('backend.order.add-service', 'createmodal', {{ $order_id }}, '6', {{ $from_store }})" data-target="#addService" style="color: #ee2e31;">@lang('Add service')</a>

                  <a href="#!" data-toggle="modal" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Search product')</a>

                </caption>
                <thead style="background-color: #86FFCF; border-color: #FAFA33; color: dark;">
                  <tr class="text-center">
                  </tr>
                  <tr class="thead-dark">
                    <th>@lang('Product')</th>
                    <th>@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_quotation->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                  <tr>
                    <td>
                      {{ $product->product->code_subproduct_clear }}
                      {!! $product->product->full_name_link !!}
                    </td>
                    <td class="text-center">
                      
                      @if($showPriceWithoutTax == false)
                        <livewire:backend.cartdb.price-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$product->type" :setModel="'product_order'"/>
                        <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
                      @else
                        <div class="small text-muted"> ${{ $product->price }} </div>
                        <livewire:backend.cartdb.price-without-taxes-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$product->type" :setModel="'product_order'"/>
                      @endif

                    </td>
                    <td class="text-center" wire:ignore.self>
                      <livewire:backend.cartdb.quantity-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$product->type" :setModel="'product_order'"/>
                    </td>
                    <td class="text-center">
                      ${{ number_format((float)$product->total_by_product, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->total_by_product) }} </div>
                    </td>
                    <td>
                      <a wire:click="removeProduct({{ $product->id }})" class="link link-dark-primary link-normal" style="cursor:pointer;"><i class="fas fa-times text-c-blue m-l-10"></i></a> 
                    </td>
                  </tr>
                  <tr>
                    <th class="text-right">
                      <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                    </th>
                    <th class="text-left" colspan="3">
                      <livewire:backend.components.edit-field :model="'\App\Models\ProductOrder'" :entity="$product" :field="'comment'" :key="'comments'.$product->id"/>
                    </th>
                  </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">
                      {!! $model->total_products_and_services_label !!}
                    </td>
                    <td class="text-center">
                      ${{ number_format((float)$model->total_quotation, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($model->total_quotation) }} </div>
                    </td>
                  </tr>

                  <tr>
                    <td class="border-0">
                    </td>
                    <td colspan="2" class="text-center border-0">
                      <button type="button" class="btn" style="background-color: #86FFCF;" wire:click="renderButton">@lang('Save')</button>
                    </td>
                    <td colspan="2" class="border-0">
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
            <div class="card border-0">
              <div class="card-body text-center">
                <a href="#" class="btn btn-primary" wire:click="processQuotation(true)" onclick="confirm('¿Seguro que desea procesar?') || event.stopImmediatePropagation()">Procesar a {{ $from_store ? __('Request') : __('Order') }}</a>
              </div>
            </div>
            @endif

            @if($maerialAll)
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                  <tr >
                    <th>@lang('Feedstock')</th>
                    <th>@lang('Unit price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($model->materials_order as $material)
                    <tr class="table-warning">
                      <td>
                        {!! $material->material->full_name !!}
                      </td>
                      <td class="text-center">${{ $material->price }}</td>
                      <td class="text-center">{{ rtrim(rtrim(sprintf('%.8F', $material->sum), '0'), ".") }}</td>
                      <td class="text-center">${{ rtrim(rtrim(sprintf('%.8F', $material->sumtotal), '0'), ".") }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif

          </div>
        </div>
      </div>

    </div>

  </x-slot>

  <x-slot name="footer">
    @if(($model->type != '7'))
      <x-utils.delete-button :text="__('Delete').' '.$model->type_order_clear" :href="route('admin.order.destroy', $order_id)" />
    @endif
    <footer class="blockquote-footer float-right">
      Mies Van der Rohe <cite title="Source Title">Less is more</cite>
    </footer>
  </x-slot>

</x-backend.card>

<livewire:backend.cartdb.search-product-order :typeSearch="6" branchIdSearch="1" orderId="{{ $order_id }}"/>

<livewire:backend.order.create-payment />
<livewire:backend.order.add-service />

@push('after-scripts')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> 

  <script>

    var labels =  {!! $model->total_graphic->keys() !!};
    var values =  {!! $model->total_graphic->values() !!};

    new Chart(document.getElementById("doughnut-chart"), {
        type: 'doughnut',
        data: {
          labels: labels,
          values: values,
          datasets: [
            {
              label: "Estaciones",
              backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9"],
              data: values
            }
          ]
        },
        options: {
          title: {
            display: true,
            text: 'Avance'
          }
        }
    });
  </script>

  <script type="text/javascript">
    Livewire.on("paymentStore", () => {
        $("#createPayment").modal("hide");
    });
  </script>

  <script type="text/javascript">
    Livewire.on("serviceStore", () => {
        $("#addService").modal("hide");
    });
  </script>
@endpush