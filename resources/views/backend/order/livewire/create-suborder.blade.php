@push('after-styles')
    <style>
          .form-control {border-color: purple;}
    </style>
@endpush

<x-backend.card>
    <x-slot name="header">
        @lang('Suborders') - @lang('Order') #{{ $order_id }}
    </x-slot>

    <x-slot name="headerActions">
        {{-- <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.order.edit', $order_id)" :text="__('Go to edit order')" /> --}}

        <x-utils.link class="card-header-action" :href="route('admin.order.suborders')" icon="fa fa-chevron-left" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">

        <div class="row ">
            <div class="col-16 col-md-8">

                <div class="card card-edit card-product_not_hover card-flyer-without-hover">
                  <div class="card-body">
            
                  <h4 class="card-title font-weight-bold mb-2"> </h4>

                    <span style="color:purple;">
                      <i class="c-icon c-icon-4x cil-library"></i>
                    </span>

                    <livewire:backend.departament.select-departaments/>
                    <div class="row mt-4 justify-content-md-center">
                      <div class="col-3 form-inline">
                        @lang('Per page'): &nbsp;

                        <select wire:model="perPage" class="form-control">
                          <option>10</option>
                          <option>25</option>
                          <option>50</option>
                          <option>100</option>
                        </select>
                      </div><!--col-->
                    </div>

                    <div class="row mb-4 justify-content-md-center">

                        <div class="col-9">
                          <div class="input-group">
                            <input wire:model.debounce.350ms="searchTerm" class="input-search" type="text" placeholder="Buscar producto terminado..." />
                            <span class="border-input-search"></span>
                          </div>
                        </div>
                        @if($searchTerm !== '')
                            <div class="input-group-append">
                              <button type="button" wire:click="clear" class="close" aria-label="Close">
                                <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
                              </button>
                            </div>
                        @endif
                    </div>

                    <div class="row justify-content-end">
                      <div class="col-9">
                        @error('departament') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                      </div>
                    </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th class="border-right-0">Producto terminado</th>
                              <th style="color:purple;">Disponible para suborden</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($products as $product)
                              <tr>
                                <td class="text-left">{!! $product->full_name !!}</td>
                                <td class="border-right-0">{{ $product->stock }}</td>
                                <td > 
                                    <input type="text" 
                                        wire:model="quantityy.{{ $product->id }}.available"
                                        {{-- wire:keydown.enter="savesuborder"  --}}
                                        class="form-control"
                                        style="color: red;" 
                                        {{-- placeholder="{{ $product->quantity - $model->getTotalAvailableByProduct($product->id) }}"  --}}
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
                                <td class="border-right-0">{{ $products->sum('stock') }}</td>
                                <td style="color:purple;"></td>
                              </tr>
                              @if($quantityy)
                                <tr>
                                  <td colspan="2"></td>
                                  <td>
                                    <button type="button" wire:click="savesuborder" style="background: purple; color: white;" class="btn btn-sm">@lang('Save suborder')</button>
                                  </td>
                                </tr>
                              @endif
                          </tbody>
                        </table>

                        {{ $products->links() }}

                      </div> 
                  </div>
                </div>

            </div>

            <div class="col-12 col-md-4">

              <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
                <div class="card-body">
                  <a type="button" href="{{ route('admin.order.suborders') }}" style="background-color:purple;" class="btn text-white" >@lang('View all')</a>
                </div>
              </div>

              <div class="list-group">
                @php
                  $colors_counter = 0;
                  $colors = array(0=>"primary", 1=>"info", 2=>"secondary", 3=>"light");
                @endphp

                @forelse($model as $suborder)
                  <a href="{{ route('admin.order.edit', $suborder->id) }}" class="list-group-item list-group-item-action flex-column align-items-start 
                    @if($colors_counter <= 3)
                      list-group-item-{{ $colors[$colors_counter] }}
                    @endif
                  ">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1 text-primary">#{{ $suborder->id}}</h5>
                      <small>{{ $suborder->date_diff_for_humans }}</small>
                    </div>
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">{{ optional($suborder->departament)->name }}</h5>
                    </div>
                    {{-- <small class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</small> --}}
                    <p>Total de productos: <strong class="text-danger">{{ $suborder->total_products_suborder }}</strong></p>
                    <hr>
                    @if($suborder->slug)
                      <div class="d-flex w-100  justify-content-center">
                        <h6 class="mb-1 text-dark mr-2">@lang('Tracking number'):</h6>
                        <h6>{{ $suborder->slug }}</h6>
                      </div>
                    @endif
                  </a>
                    <?php $colors_counter++; ?>
                @empty
                  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start text-center">
                      <h5 class="mb-1"><em>@lang('Undefined suborders')</em></h5>
                  </a>

                @endforelse
              </div>

              <div class="card text-center mt-4" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
                <div class="card-body">
                  <a type="button" href="{{ route('admin.order.suborders') }}" style="background-color:purple;" class="btn text-white" >@lang('View all')</a>
                </div>
              </div>

            </div>
        </div>
    </x-slot>

</x-backend.card>