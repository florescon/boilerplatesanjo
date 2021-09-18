@push('after-styles')
<style type="text/css">
</style>
@endpush

<x-backend.card>

  <x-slot name="header">
    @lang('Show order') #{{ $order_id }}
  </x-slot>

  <x-slot name="headerActions">
    <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
  </x-slot>
  <x-slot name="body">
    <div class="row ">

      <div class="col-12 col-sm-12 {{ $orderExists ? 'col-md-8' : 'col-md-12' }} " style="margin-top: 40px;">
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
            <h5 class="card-title">#{{ $model->id }}</h5>
            <p class="card-text">
              <div class="form-row ">
                
                @if($slug)
                  <div class="col-md-3 mb-3">
                    <div class="visible-print text-left" wire:ignore>
                      {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(route('frontend.track.show', $slug)); !!}
                      <p class="mt-2">@lang('Scan me for go track')</p>
                    </div>
                  </div>
                @endif

                <div class="col-md-9 mb-3">
                  <div class="row">
                    <div class="col-6 col-lg-6">
                      {!! $model->user_name !!}
                    </div>
                    @if($orderExists)
                    <div class="col-6 col-lg-6" style="font-family:Arial, FontAwesome">
                      <a href="{{ route('admin.order.whereIs',$model->id) }}" style="color:#a20909ff;">
                        <em>
                          @lang('Where is products?')
                        </em> 
                        &#xf288;
                      </a>                                   
                    </div>
                    @endif
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-6 col-lg-6">
                      <x-input.input-alpine nameData="isDate" :inputText="$isDate" :originalInput="$isDate" wireSubmit="savedate" modelName="date_entered" maxlength="200" inputType="date" className=""/>

                    </div>
                    <div class="col-6 col-lg-6">
                      {{ $model->created_at }}
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-12 col-lg-12">
                      {{-- {{ $model->comment }} --}}
                      <x-input.input-alpine nameData="isComment" :inputText="$isComment" :originalInput="$isComment" wireSubmit="savecomment" modelName="comment" maxlength="200" className="" />

                      @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                  </div>
                </div>
              </div>

            </p>

            @if($orderExists)

            <div class="form-row">
              <div class="col-md-4 mb-3">
                {!! $model->last_status_order->status->name ?? '<span class="badge badge-secondary">'.__('undefined status').'</span>' !!}
                <div wire:loading wire:target="updateStatus" class="loading"></div>
              </div>
              <div class="col-md-4 mb-3">
                {{-- <a href="{{ route('admin.order.advanced', $model->id) }}" style="color:#1ab394;">
                  <p> Opciones avanzadas </p>
                </a> --}}
              </div>
              <div class="col-md-4 mb-3 text-left">
                <a href="{{ route('admin.order.sub', $model->id) }}" style="color:purple;">
                  <p> @lang('I want to assign suborders') <i class="cil-library"></i></p> 
                </a>
  
                @php
                  $colors_counter = 0;
                  $colors = array(0=>"primary", 1=>"info", 2=>"secondary", 3=>"light");
                @endphp

                <div class="list-group">
                  @foreach($model->suborders as $suborder)
                    <a href="{{ route('admin.order.edit', $suborder->id) }}" class="list-group-item list-group-item-action flex-column align-items-start 
                      @if($colors_counter <= 3)
                        list-group-item-{{ $colors[$colors_counter] }}
                      @endif
                    ">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1"><strong> #{{ $suborder->id}} </strong> {{ optional($suborder->departament)->name }}</h6>
                        <small>{{ $suborder->date_diff_for_humans }}</small>
                      </div>
                    </a>
                      <?php $colors_counter++; ?>
                  @endforeach
                </div>
              </div>
            </div>
            @endif

            <a href="{{ route('admin.order.ticket_order', $model->id) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
              <ins>
                General
              </ins>
            </a>
            @if($model->materials_order()->exists())
              <a href="{{ route('admin.order.ticket_materia', $model->id) }}" class="card-link text-warning" target="_blank"><i class="cil-print"></i>
                <ins>
                  @lang('Feedstock')
                </ins>
              </a>
            @endif
          </div>
          <div class="card-footer text-muted text-center">
            {{ $model->date_diff_for_humans }}
          </div>
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
                        Ver materia prima por producto, previo al consumo
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
                <thead class="thead-dark">
                  <tr >
                    <th >@lang('Product')</th>
                    <th>@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_order as $product)
                  <tr>
                    <td>
                      <a href="{{ route('admin.product.consumption_filter', $product->product_id) }}" target=”_blank”> <span class="badge badge-warning"> <i class="cil-color-fill"></i></span></a>
                      {!! $product->product->full_name !!}
                    </td>
                    <td class="text-center">${{ $product->price }}</td>
                    <td class="text-center">{{ $product->quantity }}</td>
                    <td class="text-center">${{ $product->total_by_product }}</td>
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

                              {{ $key }}
                              {{ $consumption['material'] }}
                            </td>
                            <td>
                                {{ $consumption['unit'] }}
                            </td>
                            <td>
                                {{ $consumption['quantity'] }}
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr class="table-danger text-center font-italic">
                            <td colspan="4">
                              <p>Sin materia prima definida, aun</p>
                            </td>
                          </tr>
                      @endif
                      @endif

                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{{ $model->total_products }}</td>
                    <td class="text-center">${{ $model->total_order }}</td>
                  </tr>

                </tbody>
              </table>
            </div>

            @endif

            @if($saleExists)
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th>@lang('Product')</th>
                    <th>@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($model->product_sale as $product)
                  <tr class="table-info">
                    <td>
                      {!! $product->product->full_name !!}
                    </td>
                    <td class="text-center">${{ $product->price }}</td>
                    <td class="text-center">{{ $product->quantity }}</td>
                    <td class="text-center">${{ $product->total_by_product }}</td>
                  </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{{ $model->total_products }}</td>
                    <td class="text-center">${{ $model->total_order }}</td>
                  </tr>

                </tbody>
              </table>
            </div>
            @endif

            @if($maerialAll)
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                  <tr >
                    <th>Materia prima</th>
                    <th>@lang('Price')</th>
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
                      <td class="text-center">{{ $material->sum }}</td>
                      <td class="text-center">${{ $material->total_by_material }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif

          </div>
        </div>
      </div>


      @if($orderExists)
      <div class="col-12 col-md-4">
        <div class="row d-flex justify-content-center mt-70 mb-70">
          <div class="col-md-12">
            <div class="main-card mb-3 card card-edit">
              <div class="card-body">
                <h5 class="card-title">@lang('Status order')
                  <span class='badge badge-primary'>{{ $model->last_status_order->status->name ?? '' }}</span>
                </h5>
                <div wire:loading wire:target="updateStatus" class="loading">@lang('Wait 3 seconds')</div>
                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                  @foreach($statuses as $status)
                  <div class="vertical-timeline-item vertical-timeline-element">
                    <div> <span wire:click="updateStatus({{ $status->id }})" class="vertical-timeline-element-icon bounce-in"> <i  wire:loading.class.remove="badge-dot-xl badge-dot-xl2" class="badge badge-dot 
                      {{ $status->id == $lates_statusId ? 'badge-dot-xl2' : 'badge-dot-xl' }}
                      badge-primary"> </i> </span>
                      <div class="vertical-timeline-element-content bounce-in" style="{{ $status->id == $lates_statusId ? 'font-size: medium;' : '' }}">
                        <p class="timeline-title  {{ $status->id == $lates_statusId ? 'text-primary' : 'text-success' }}">{{ $status->name }}</p>
                        <p>{{ $status->description }}</p> 
                        @if($status->to_add_users)
                        <a href="{{ route('admin.order.assignments', [$model->id, $status->id]) }}">
                          <span class="vertical-timeline-element-date badge text-primary">
                            <i class="c-icon c-icon-4x cil-people"></i><i class="cil-plus"></i>
                          </span>
                        </a>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>

              <div class="card-body text-center">
                <a href="{{ route('admin.order.records', $model->id) }}" class="card-link">@lang('View status records')</a>
              </div>

            </div>
          </div>
        </div>
      </div>
      @endif

    </div>
  </x-slot>

  <x-slot name="footer">
    <x-utils.delete-button :text="__('Delete order')" :href="route('admin.order.destroy', $model->id)" />
    <footer class="blockquote-footer float-right">
      Mies Van der Rohe <cite title="Source Title">Less is more</cite>
    </footer>
  </x-slot>

</x-backend.card>