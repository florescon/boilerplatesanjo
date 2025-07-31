@push('after-styles')
<style>
    .text-right { text-align: right; }
    .font-weight-bold { font-weight: bold; }
    .table-active { background-color: rgba(0,0,0,.05); }
    .product-group { margin-bottom: 2rem; }
    .product-group h4 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
</style>
@endpush

<x-backend.card>

  <x-slot name="header">
    <h4>{{ $model->type_order_clear }} #{!! $model->folio_or_id !!}</h4>
  </x-slot>

  <x-slot name="headerActions">

    @if($model->isOrder() && !$model->isFromStore() && !$model->stations()->exists())

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
      <div class="col-12 col-sm-12 {{ $orderExists || $requestExists ? 'col-md-8' : 'col-md-12' }}">
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
                      <x-input.input-alpine nameData="isDate" :inputText="$isDate" :originalInput="$isDate" wireSubmit="savedate" modelName="date_entered" inputType="date" className="" :extraName="__('Date')" />

                    </div>
                    <div class="col-6 col-lg-6">
                      {{ $model->created_at }}
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->info_customer }} --}}
                      <x-input.input-alpine nameData="isInfo_customer" :inputText="$isInfo_customer" :originalInput="$isInfo_customer" wireSubmit="saveinfocustomer" modelName="info_customer" maxlength="300" className="" :extraName="__('Info customer')" />
                      @error('info_customer') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->comment }} --}}
                      <x-input.input-alpine nameData="isComment" :inputText="$isComment" :originalInput="$isComment" wireSubmit="savecomment" modelName="comment" maxlength="300" className="" :extraName="__('Comment')" />
                      @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->comment }} --}}
                      <x-input.input-alpine nameData="isObservation" :inputText="$isObservation" :originalInput="$isObservation" wireSubmit="saveobservation" modelName="observation" maxlength="300" className="" :extraName="__('Observations')"  />
                      @error('observation') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>

                  @if($model->filterByDiscount() && $model->total_payments < 1)
                    <div class="row mt-3">
                      <div class="col-12 col-lg-12">
                        {{-- {{ $model->discount }} --}}
                        <x-input.input-alpine nameData="isDiscount" :inputText="$isDiscount" :originalInput="$isDiscount" wireSubmit="savediscount" modelName="discount" maxlength="300" className="" :extraName="__('Discount')" />
                        @error('discount') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                      </div>
                    </div>
                  @endif

                  @if($model->branch_id === 0)
                  <div class="row mt-3">
                    <div class="col-6 col-lg-6">
                      <x-input.input-alpine nameData="isRequest" :inputText="$isRequest" :originalInput="$isRequest" wireSubmit="saverequest" modelName="request" :extraName="__('Request n.º')" maxlength="300" className="" />
                      @error('request') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                    <div class="col-6 col-lg-6">
                      <x-input.input-alpine nameData="isPurchase" :inputText="$isPurchase" :originalInput="$isPurchase" wireSubmit="savepurchase" modelName="purchase" :extraName="__('Purchase order')" maxlength="300" className="" />
                      @error('purchase') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
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
                <x-input.input-alpine nameData="isComplementary" :inputText="$isComplementary" :originalInput="$isComplementary" wireSubmit="savecomplementary" modelName="complementary" maxlength="300" className="" :extraName="__('Complementary observations')"  />
                @error('complementary') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

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

                <div class="btn-group" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    S/Precios
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.ticket_order', [$order_id, true, true]) : route('admin.store.all.ticket_order', [$order_id, true, true]) }}" target="_blank">Ticket</a>
                      @if(!$model->isQuotation())

                        <a class="dropdown-item" href="{{ !$from_store ? route('admin.order.print', [$order_id, true, 0, true]) : route('admin.store.all.print', [$order_id, true]) }}" target="_blank">Carta</a>

                        <a class="dropdown-item" href="{{ route('admin.order.printgropedwithoutprice', $order_id) }}" target="_blank">Carta - Agrupado</a>
                      @endif

                  </div>
                </div>
        
              @if(!$model->isOutputProducts())
                <a type="button" href="{{ !$from_store ? route('admin.order.print', [$order_id, 0, true]) : route('admin.store.all.print', [$order_id, 0, true]) }}" class="btn btn-secondary" target="_blank">Imprimir productos agrupados</a>
              @endif

            </div>
            <div class="text-center mt-2">
              @if(!$from_store && $model->materials_order()->exists())
                <a type="button" href="{{ route('admin.order.ticket_materia', $order_id) }}" class="btn btn-warning text-white" target="_blank">@lang('Feedstock')</a>
              @endif

              @if(!$model->isQuotation() and !$model->isOutputProducts())
                <a type="button" target="_blank" href="{{ route('admin.order.service_orders', $order_id) }}" class="btn btn-link">@lang('Service Order') 
                  @if($model->service_orders()->count())
                    <span class="badge badge-success">{{ $model->service_orders()->count() }}</span>
                  @endif
                </a>
              @endif
            </div>

            <div class="text-center mt-2">
        
                <a type="button" href="{{ route('admin.order.newformat', $order_id) }}" class="btn btn-primary" target="_blank">Nuevo Formato</a>
                <a href="{{ route('admin.bom.ticket_bom', urlencode(json_encode(array($order_id)))) }}" class="btn btn-secondary ml-2" target="_blank"><i class="cil-print"></i> Ticket BOM </a>

                @if(!$model->productionBatches()->exists() && !$model->stations()->exists())
                  <a href="{{ route('admin.order.advanced', $order_id) }}" style="color:#1ab394;" class="btn btn-white ml-2 pulsingButton" >
                  <strong>@lang('Edit request')</strong>
                @endif
                </a>
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
          @if($model->quotation && $model->isOrder())
            <div class="card-footer text-muted text-center h5">
              {!! __('Quotation'). ' <strong class="text-danger">: #'.$model->quotation.'</strong>' !!}
            </div>
          @endif
          @if($model->isOrder())
            <div class="card-footer text-muted text-center h5">
                <x-input.input-alpine nameData="isNotes" :inputText="$isNotes" :originalInput="$isNotes" wireSubmit="savenotes" modelName="notes" maxlength="300" className="" :extraName="__('Notes')"  />
                @error('notes') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
            </div>
          @endif
        </div>


        <div class="card card-edit card-product_not_hover card-flyer-without-hover">
          <div class="card-body">
{{--  --}}

@if(!$model->isQuotation())

<div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
  <!-- Left side - File generated info -->
  <div class="legend">
    <p class="mb-0 text-primary font-weight-bold" style="font-size: 16px;">
      <span class="text-primary">@lang('Last request'):</span> 
      {{ now()->isoFormat('D, MMM, YY - h:mm a') }}
    </p>
  </div>
  
  <!-- Right side - Action buttons -->
  <div class="btn-group" role="group">
    <!-- Prices Toggle Button -->
    <button wire:click="$toggle('prices')" 
            class="btn btn-sm {{ $prices ? 'btn-primary' : 'btn-outline-primary' }}">
      @if(!$prices)
        @lang('Prices')
      @else
        @lang('Without prices')
      @endif
    </button>

    <!-- General Toggle Button -->
    <button wire:click="$toggle('general')" 
            class="btn btn-sm {{ $general ? 'btn-primary' : 'btn-outline-primary' }}">
      @if(!$general)
        @lang('General')
      @else
        @lang('Sin General')
      @endif
    </button>

    <!-- Details Toggle Button -->
    <button wire:click="$toggle('details')" 
            class="btn btn-sm {{ $details ? 'btn-primary' : 'btn-outline-primary' }}">
      @if(!$details)
        @lang('Detalles')
      @else
        @lang('Sin Detalles')
      @endif
    </button>
  </div>
</div>


<table class="table table-hover mb-0 text-center">
  <thead class="bg-dark">
      <td style="width: 50%;">
        {!! $order->total_products_and_services_label !!}
      </td>
    </thead>
</table>

@if($general)
<div class="container-fluid" style="margin-top: 20px;">
  <h4 class="text-primary font-weight-semi-bold mb-4" style="font-size: 18px;">@lang('General')</h4>
  
  <div class="card border rounded">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="bg-light">
          <tr>
            <th class="text-center font-weight-semi-bold text-primary" style="width: 5%;">@lang('Quantity')</th>
            <th class="font-weight-semi-bold text-primary" style="width: 10%;">@lang('Code')</th>
            <th class="font-weight-semi-bold text-primary" style="width: 60%;">@lang('Description')</th>
            @if($prices)
            <th class="font-weight-semi-bold text-primary" style="width: 10%;">@lang('Price')</th>
            <th class="text-right font-weight-semi-bold text-primary" style="width: 15%;">@lang('Total')</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach($orderGroup as $product)
            @if($product->product_name != null && $product->sum != null)
            <tr>
              <td class="text-center text-accent">{{ $product->sum }}</td>
              <td>{{ $product->product_code ?? '--' }}</td>
              <td><strong class="text-primary">{{ $product->brand_name }}</strong> {{ $product->product_name }} - {{ $product->color_name }}</td>
              @if($prices)
              <td class="text-center text-primary">
                @if($product->omg)
                  ${{ priceWithoutIvaIncluded($product->min_price) }}
                  -
                @endif
                ${{ priceWithoutIvaIncluded($product->max_price) }}
              </td>
              <td class="text-right text-primary">${{ priceWithoutIvaIncluded($product->sum_total) }}</td>
              @endif
            </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  
  <div class="mt-4">
    <div class="table-responsive">
      <table class="table">
        <tbody>
          <tr>
            @if($prices)
            <td class="text-right" style="width: 40%;">
              <p class="text-primary font-weight-bold mb-1" style="font-size: 19px;">@lang('Subtotal'):</p>
              @if($order->discount)
                <p class="text-primary font-weight-bold mb-1" style="font-size: 19px;">@lang('Discount'):</p>
              @endif
              <p class="mb-1 font-weight-semi-bold text-primary" style="font-size: 19px;">IVA:</p>
              <p class="text-primary font-weight-bold mb-0" style="font-size: 19px;">@lang('Total'):</p>
            </td>
            <td class="text-right" style="width: 10%;">
              <p class="text-primary font-weight-bold mb-1 text-right" style="font-size: 19px;">${{ count($order->product_suborder) ? '--' : number_format($order->subtotal_by_all, 2) }}</p>
              @if($order->discount)
                <p class="mb-1 font-weight-semi-bold text-primary text-right" style="font-size: 19px;">
                  @if(!$breakdown)
                    ${{ number_format($order->calculate_discount_all, 2)}}
                  @else
                    %{{ $order->discount }}
                  @endif
                </p>
              @endif
              <p class="mb-1 font-weight-semi-bold text-primary text-right" style="font-size: 19px;">${{ count($order->product_suborder) ? '--' : calculateIva($order->subtotal_less_discount) }}</p>
              <p class="text-primary font-weight-bold mb-0 text-right" style="font-size: 19px;">${{ number_format(count($order->product_suborder) ? $total : $order->total_by_all_with_discount, 2) }}</p>
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
<div class="container-fluid mt-3">
  <h4 class="text-primary font-weight-semi-bold mb-4" style="font-size: 18px;">@lang('Details')</h4>
  
  @foreach($tablesData as $parentId => $tableData)
      <div class="product-group">
        <h5 class=""> 
          <strong class="text-primary"><a target="_blank" href="{{ route('admin.product.edit', $parentId) }}"> {{ $tableData['parent_code'] }}</a></strong> 
          {{ $tableData['parent_name'] }}
        </h5>
        
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
              @foreach($tableData['rows'] as $row)
                <tr>
                  @if($row['no_size'])
                    <td style="width: 35%">{{ $row['general_code'] }}</td>
                  @endif
                  <td style="width: 10%">{{ $row['color_product'] ?: 'N/A' }}</td>
                  
                  @foreach($tableData['headers'] as $header)
                    <td class="text-center">
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
                    <div>{{ $row['row_quantity'] }}</div>
                    @if($prices)
                    <div class="text-primary font-italic small">
                      {{ $row['row_total_display'] }}
                    </div>
                    @endif
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
                  @if($prices)
                    <div class="font-italic small">
                      {{ $tableData['totals']['grand_total'] }}
                    </div>
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

@endif
             @if($model->isQuotation())
              <h3 class="text-center">
                <span class="badge badge-primary" wire:click="updatePrices" style="cursor:pointer;">@lang('Update prices')</span>
              </h3>
            @endif

            @if($orderExists)

            <div class="row justify-content-md-center">
              @if($model->materials_order()->doesntExist())
{{--               <div class="col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="custom-control custom-switch custom-control-inline">
                      <input type="checkbox" wire:model="previousMaterialByProduct" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                      <label class="custom-control-label" for="customRadioInline1">
                        @lang('See raw material of each product')
                     </label>
                    </div>
                  </div>
                </div>
              </div>
 --}}              @else
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
              </table>
            </div>

            @endif


            @if($model->isQuotation() && $model->product_quotation->count() < 1)
              <a href="#!" class="mt-2 ml-2" data-toggle="modal" wire:click="$emitTo('backend.order.add-service', 'createmodal', {{ $order_id }}, '6', {{ $from_store }})" data-target="#addService" style="color: #ee2e31;">@lang('Add service')</a>

              <a href="#!" data-toggle="modal" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Search product')</a>
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
                    <th colspan="5" >@lang('Quotation')</th>
                  </tr>
                  <tr class="thead-dark">
                    <th class="text-center">@lang('Quantity')</th>
                    <th>@lang('Product')</th>
                    <th class="text-center">@lang('Price')</th>
                    <th class="text-center">Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_quotation->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                  <tr>
                    <td class="text-center" wire:ignore.self>
                      <livewire:backend.cartdb.quantity-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$product->type" :setModel="'product_order'"/>
                    </td>
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
                    <td class="text-center">
                      {!! $model->total_products_and_services_label !!}
                    </td>
                    <td class="text-center">
                    </td>
                    <td class="text-right">Total:</td>
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

      @if($orderExists || $requestExists)
        <div class="col-12 col-md-4">
          <div class="row d-flex justify-content-center mt-70 mb-70 sticky-top">

            @if(!$model->from_store 
              && ($model->id > 977)
              )
            <div class="col-md-12" wire:ignore>
              <div class="main-card mb-3 card card-edit">

                <p class="card-text text-center pt-4">{!! $model->total_products_and_services_line_label !!} </p>

                <div>
                  @if($model->stations()->exists())

                    <canvas id="doughnut-chart" width="800" height="550"></canvas>

                    <div class="text-center p-4">
                      @foreach($model->total_graphic_new['collectionExtra'] as $key => $value)
                        <li class="list-group-item list-group-item-secondary"> {!! ucfirst($key) .': <strong class="text-danger">'.$value.'</strong>' !!}</li>
                      @endforeach
                    </div>

                  @else

                    <canvas id="doughnut-chart-work" width="500" height="250"></canvas>

                    <div class="text-center p-4">
                      @foreach($model->total_graphic_work['collectionExtra'] as $key => $value)
                        <li class="list-group-item list-group-item-secondary"> {!! ucfirst($key) .': <strong class="text-danger">'.$value.'</strong>' !!}</li>
                      @endforeach
                    </div>


                  @endif
                </div>

                
                  {{-- {{ $model->total_graphic }} --}}

              </div>

            </div>

                <div class="card-body border-dashed conic " style="margin-top: -10px;">
                  <div class="list-group">
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action list-group-item-secondary" aria-current="true">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Captura</h6>
                        <small class="text-danger">
                          @if($model->stations()->exists())
                            {{ $model->total_graphic_new['collection']->get('captura') }}
                          @else
                            {{ $model->total_graphic_work['collection']->get('captura') }}
                          @endif
                        </small>
                      </div>
                    </a>
                    @foreach($batches as $status)
                      <a href="{{ $model->stations()->exists() ? route('admin.order.station', [$order_id, $status->id]) : route('admin.order.work', [$order_id, $status->id]) }}" class="list-group-item list-group-item-action" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                          <h6 class="mb-1">{{ ucfirst($status->name) }}</h6>
                          <small class="text-danger">
                          @if($model->stations()->exists())

                            {{ $model->total_by_station->get($status->short_name) ?? '--' }}
                          
                          @else

                            {{ $model->total_by_station_work->get($status->short_name) ?? '--' }}

                          @endif
                          </small>
                        </div>
                      </a>
                    @endforeach
                  </div>

                  <div class="list-group">
                    <a href="{{  $model->stations()->exists() ? route('admin.order.station', [$order_id, $supplier->id]) : route('admin.order.work', [$order_id, $supplier->id]) }}" class="list-group-item list-group-item-action" aria-current="true">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $supplier->name }}</h6>
                        <small class="text-danger">
                        @if($model->stations()->exists())

                          {{ $model->total_graphic_new['collection']->get($supplier->short_name) ?? '--' }}

                        @else

                          {{ $model->total_graphic_work['collection']->get($supplier->short_name) ?? '--' }}

                        @endif
                        </small>
                      </div>
                    </a>
                  </div>


                  <div class="list-group">
                    @foreach($process as $status)
                        <a href="{{ $model->stations()->exists() ? route('admin.order.station', [$order_id, $status->id]) : route('admin.order.work', [$order_id, $status->id]) }}" class="list-group-item list-group-item-action" aria-current="true">
                          <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $status->name }}</h6>
                            <small class="text-danger">
                            @if($model->stations()->exists())  
                              {{ $model->total_graphic_new['collection']->get($status->short_name) ?? '--' }}
                            @else
                              @if(!$status->not_restricted)
                                {{ $model->total_graphic_work['collection']->get($status->short_name) ?? '--' }}
                              @else
                                N/A
                              @endif
                            @endif
                            </small>
                          </div>
                        </a>
                    @endforeach
                  </div>
                </div>
            @endif

          </div>
        </div>
      @endif

    </div>

  </x-slot>

  <x-slot name="footer">
    @if(($model->type != '7'))
      @if(!$model->productionBatches()->exists() && !$model->stations()->exists())
        {{-- <x-utils.delete-button :text="__('Delete').' '.$model->type_order_clear" :href="route('admin.order.destroy', $order_id)" /> --}}

        <button wire:click="makeDeleteOrder" class="btn btn-danger btn-sm text-white" wire:loading.attr="disabled">
          <i class="fas fa-trash"></i>
          Eliminar <span wire:loading wire:target="makeDeleteOrder">...</span>
        </button>
      @endif
    @endif
    <footer class="blockquote-footer float-right">
      Mies Van der Rohe <cite title="Source Title">Less is more</cite>
    </footer>
  </x-slot>

</x-backend.card>

<livewire:backend.cartdb.search-product-order :typeSearch="6" branchIdSearch="0" orderId="{{ $order_id }}"/>

<livewire:backend.order.create-payment />
<livewire:backend.order.add-service />

@push('after-scripts')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> 

  <script>
  var labels =  {!! json_encode($model->total_graphic_new['collection']->keys()) !!};
  var values =  {!! json_encode($model->total_graphic_new['collection']->values()) !!};
  var colors = {!! json_encode($model->total_graphic_new['colors']) !!};

  // Map labels to their corresponding colors
  var backgroundColors = labels.map(label => colors[label] || '#000000'); // Default to black if no color is found

  new Chart(document.getElementById("doughnut-chart"), {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [
          {
            label: "Estaciones",
            backgroundColor: backgroundColors,
            data: values
          }
        ]
      },
      options: {
        title: {
          display: true,
        }
      }
  });
  </script>


  <script>
  var labels =  {!! json_encode($model->total_graphic_work['collection']->keys()) !!};
  var values =  {!! json_encode($model->total_graphic_work['collection']->values()) !!};
  var colors = {!! json_encode($model->total_graphic_work['colors']) !!};

  // Map labels to their corresponding colors
  var backgroundColors = labels.map(label => colors[label] || '#000000'); // Default to black if no color is found

  new Chart(document.getElementById("doughnut-chart-work"), {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [
          {
            label: "Estaciones",
            backgroundColor: backgroundColors,
            data: values
          }
        ]
      },
      options: {
        title: {
          display: true,
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