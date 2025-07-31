<x-backend.card>

    <x-slot name="header">
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.order.edit', $order->id)" icon="fa fa-chevron-left" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">

                    <div class="ibox-content m-b-sm border-bottom">
                        <div class="p-xs">
                            <div class="pull-left m-r-md"></div>
                            <h2 class="mt-2">&nbsp;Editar Pedido</h2>
                            
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <h4>
                                        &nbsp;{!! $order->type_order !!}
                                        Folio #{{ $order->id }}, <br> 
                                        &nbsp;@lang('Order track'): {{ $order->slug }}
                                        <a href="{{ route('frontend.track.show', $order->slug) }}" target="_blank">
                                            <span class="badge badge-primary"> 
                                                <i class="cil-external-link"></i>
                                            </span>
                                        </a>
                                    </h4>
                                    <span>&nbsp; @lang('Date'): <strong>{{ $order->date_entered ? $order->date_entered->format('d-m-Y') : '--' }}</strong></span><br>
                                </div>
                                
                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    @if($order->comment)<span>&nbsp; @lang('Info customer'): <strong>{{ $order->comment }}</strong></span><br>@endif
                                    @if($order->info_customer)<span>&nbsp; @lang('Comment'): <strong>{{ $order->info_customer }}</strong></span><br>@endif
                                    @if($order->observation)<span>&nbsp; @lang('Observations'): <strong>{{ $order->observation }}</strong></span><br>@endif
                                    @if($order->request)<span>&nbsp; @lang('Request n.º'): <strong>{{ $order->request }}</strong></span><br>@endif
                                    @if($order->purchase)<span>&nbsp; @lang('Purchase order'): <strong>{{ $order->purchase }}</strong></span><br>@endif
                                    @if($order->complementary)<span>&nbsp; @lang('Complementary observations'): <strong>{{ $order->complementary }}</strong></span>@endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content forum-container">

              <table class="table table-striped table-bordered table-hover">

                @if($order->orders_delivery->count() <= 1)
                <caption>
                    @if(!$order->productionBatches()->exists() && !$order->stations()->exists())

                        <a href="#!" data-toggle="modal" wire:click="searchproduct()" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Add product')</a>

                    @endif

                </caption>
                @endif
                <thead style="background-color: coral; border-color: #218543; color: white;">
                  <tr class="text-center">
                    <th colspan="5" >@lang('Request')</th>
                  </tr>
                  <tr class="thead-dark">
                    <th>@lang('Product')</th>
                    <th>@lang('Price') @lang('without IVA')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($order->products_without_quotation->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                  <tr>
                    <td>
                      {!! '<strong>'. $product->product->name_brand .'</strong>'  !!}
                      {{ $product->product->code_subproduct_clear }}
                      {!! $product->product->full_name !!}
                    </td>
                    <td class="text-center">
                        @if(!$order->productionBatches()->exists() && !$order->stations()->exists())
                          <livewire:backend.components.edit-decimal :model="'\App\Models\ProductOrder'" :entity="$product" :field="'price_without_tax'" :key="'price'.$product->id"/>
                        @else
                          ${{ $product->price }}
                          <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
                        @endif
                    </td>
                    <td class="text-center">

                        @if(!$order->productionBatches()->exists() && !$order->stations()->exists())
                          <livewire:backend.components.edit-integer :model="'\App\Models\ProductOrder'" :entity="$product" :field="'quantity'" :key="'quantity'.$product->id"/>
                        @else
                            {{ $product->quantity }}
                        @endif

                    </td>
                    <td class="text-center">
                      ${{ number_format((float)$product->total_by_product, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->total_by_product) }} </div>
                    </td>
                    <td>
                        <a wire:click="removeP({{ $product->id }})" class="link link-dark-primary link-normal" style="cursor:pointer;"><i class="fas fa-times text-c-blue m-l-10"></i></a> 
                    </td>
                  </tr>
                  <tr>
                    <th class="text-right">
                      {{-- Ajustar --}}
                      <img src="{{ asset('img/icons/down-right.svg') }}" width="20" alt="Logo"> 
                    </th>
                    <th class="text-left" colspan="4">
                      <livewire:backend.components.edit-field :model="'\App\Models\ProductOrder'" :entity="$product" :field="'comment'" :key="'comments'.$product->id"/>
                    </th>
                  </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{!! $order->total_products_and_services_label !!}</td>
                    <td class="text-center">
                      ${{ number_format((float)$order->total_without_quotation, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($order->total_without_quotation) }} </div>
                    </td>
                    <td></td>
                  </tr>

                </tbody>
              </table>


            @if($quotationExists)

            @if($order->product_quotation->count() >= 1)
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

                  @foreach($order->product_quotation->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
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
                      {!! $order->total_products_and_services_label !!}
                    </td>
                    <td class="text-center">
                      ${{ number_format((float)$order->total_quotation, 2) }}
                      <div class="small text-muted"> ${{ priceWithoutIvaIncluded($order->total_quotation) }} </div>
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
                <a href="#" class="btn btn-primary pulsingButton" wire:click="processQuotation(true)" onclick="confirm('¿Seguro que desea procesar?') || event.stopImmediatePropagation()">Procesar</a>
              </div>
            </div>
            @endif



                    </div>
                </div>
            </div>
        </div>


<livewire:backend.cartdb.search-product-order :typeSearch="6" branchIdSearch="1" orderId="{{ $order_id }}"/>

<livewire:backend.order.add-service />

    </x-slot>

</x-backend.card>
