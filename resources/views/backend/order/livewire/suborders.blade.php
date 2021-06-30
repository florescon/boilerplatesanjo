<x-backend.card>
    <x-slot name="header">
        @lang('Suborders') - @lang('Order') #{{ $order_id }}
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.order.edit', $order_id)" :text="__('Go to edit order')" />

        <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">

        <div class="row ">
            <div class="col-16 col-md-8">

                <div class="card card-edit card-product_not_hover card-flyer-without-hover">
                  <div class="card-body">
            
                  <h4 class="card-title font-weight-bold mb-2"> </h4>


                    <livewire:backend.cart.user-cart/>

                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Cantidad orden</th>
                              <th>Disponible</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($model->product_order as $product)
                              <tr>
                                <td class="text-left">{!! $product->product->full_name !!}</td>
                                <td>{{ $product->quantity }}</td>

                                <td class="table-info"> 
                                    <input type="number" 
                                        wire:model="quantityy.{{ $product->id }}.available"
                                        wire:keydown.enter="savesuborder" 
                                        class="form-control"
                                        style="color: blue; font-family:Arial, FontAwesome" 
                                        placeholder="&#xf0a4; {{ $product->quantity - $model->getTotalAvailableByProduct($product->id) }}" 
                                    >
                                    @error('quantityy.'.$product->id.'.available') 
                                      <span class="error" style="color: red;">
                                        <p>@lang('Check the quantity')</p>
                                      </span> 
                                    @enderror
                                </td>
                              </tr>
                            @endforeach
                              <tr>
                                <td class="text-right">Total:</td>
                                <td>{{ $model->total_products }}</td>
                                <td>{{ $model->total_products - $model->total_products_all_suborders }}</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>


                  </div>
                </div>

            </div>

            <div class="col-12 col-md-4">

              <div class="list-group">

                @php
                  $colors_counter = 0;
                  $colors = array(0=>"primary", 1=>"info", 2=>"secondary", 3=>"light");
                @endphp

                @forelse($model->suborders as $suborder)
                  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start 
                    @if($colors_counter <= 3)
                      list-group-item-{{ $colors[$colors_counter] }}
                    @endif
                  ">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1 text-primary">#{{ $suborder->id}}</h5>
                    </div>
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">{{ optional($suborder->user)->name }}</h5>
                      <small>{{ $suborder->date_diff_for_humans }}</small>
                    </div>
                    <small class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</small>
                    <p>Total de productos: <strong class="text-danger">{{ $suborder->total_products_suborder }}</strong></p>
                  </a>
                    <?php $colors_counter++; ?>
                @empty
                  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start text-center">
                      <h5 class="mb-1"><em>@lang('Undefined suborders')</em></h5>
                  </a>

                @endforelse
              </div>

            </div>
        </div>
    </x-slot>

</x-backend.card>