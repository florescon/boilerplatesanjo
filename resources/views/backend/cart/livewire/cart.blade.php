<x-backend.card>


	@if(count($cart['products']) > 0)
		<x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Cart order') </strong>
	 	</x-slot>
	@endif

  	<x-slot name="headerActions">
      <x-utils.link class="card-header-action" wire:click="clearCart" :text="__('Clear cart')" />
	</x-slot>


    <x-slot name="body">

	  	{{-- @json($cart)	 --}}

		<div class="row mb-4 justify-content-md-center">
			<div class="col-8">
                {{-- <livewire:backend.cart-add-form/> --}}
			</div>
		</div>

        @if(count($cart['products']) > 0 || count($cart['products_sale']))
		<div class="row ">
			<div class="col-12 col-sm-6 col-md-8">

				@if(count($cart['products']) > 0)
				<div class="table-responsive">
					<table class="table">
					  <thead class="table-primary">
					    <tr>
					      <th>ID</th>
					      <th scope="col">@lang('Code')</th>
					      <th scope="col">@lang('Product')</th>

					      {{-- <th scope="col">@lang('Amount')</th> --}}
					      <th scope="col">@lang('Price')</th>
					      <th scope="col">@lang('Amount')</th>
					      <th scope="col"></th>
					    </tr>
					  </thead>
					  <tbody>
			            @foreach($cart['products'] as $product)
						    <tr>
					    	  <td>{{ $product->id }}</td>
					    	  <td>
					    	  	{{!is_null($product->code) || !empty($product->code)? 
						      			$product->code : $product->parent->code 
						      		}}
					    	  </td>
						      <td>
								<a href="{{ route('admin.product.consumption_filter', $product->id) }}" target=”_blank”> <span class="badge badge-warning"> <i class="cil-color-fill"></i></span></a>
						      	{!! $product->full_name !!} </td>
						      <td>${{!is_null($product->price) || $product->price != 0 ? 
						      			$product->price : $product->parent->price 
						      	}}
						      </td>

						      <td style="width:120px; max-width: 120px;" >
			                    <livewire:backend.cart-update-form :item="$product" :key="$product->id" />
						      </td>

						      <td>
								<a wire:click="removeFromCart({{ $product->id }})" class="badge badge-danger text-white">@lang('Delete')</a>
						  	  </td>
						    </tr>
					    @endforeach
					  </tbody>
					</table>
				</div>
				@endif

				@if(count($cart['products_sale']) > 0)
				<div class="table-responsive">
					<table class="table">
					  <thead class="table-success">
					    <tr>
					      <th>ID</th>
					      <th scope="col">@lang('Product')</th>
					      {{-- <th scope="col">@lang('Amount')</th> --}}
					      <th scope="col">@lang('Amount')</th>
					      <th scope="col"></th>
					    </tr>
					  </thead>
					  <tbody>
			            @foreach($cart['products_sale'] as $product_sale)
						    <tr>
					    	  <td>{{ $product_sale->id }}</td>
						      <td>							
						      	{!! $product_sale->full_name !!} </td>
						      <td>{{ $product_sale->amount }}</td>
						      <td>
								<a wire:click="removeFromCartSale({{ $product_sale->id }})" class="badge badge-danger text-white">@lang('Delete')</a>
						  	  </td>
						    </tr>
					    @endforeach
					  </tbody>
					</table>
				</div>
				@endif

			</div>
			<div class="col-12 col-md-4">
			    <div class="card card-product_not_hover card-product card-flyer-without-hover">
			      <div class="card-body">

                    <div x-data="{ internalControl : @entangle('isVisible')  }">
                        <div class="form-group row">
                            <label for="internal_control" class="col-md-8 col-form-label"><h5>Control interno <span class="badge badge-secondary">@lang('Click me')</span></h5>
							</label>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="internal_control"
                                        id="internal_control"
                                        value="1"
                                        class="custom-control-input"
                                        x-on:click="internalControl = !internalControl"
                                        {{ old('internal_control') ? 'checked' : '' }} />
                                </div><!--form-check-->
                            </div>
                        </div><!--form-group-->

                        <div x-show="internalControl">
							<span class="badge badge-success">Stock {{ appName() }}</span>
                        </div>
                        <br>
                        <div x-show="!internalControl">

		                    <livewire:backend.cart.user-cart/>

                        </div>
                    </div>

					<div class="form-group">
						<label for="comment">@lang('Comment')</label>
						<textarea class="form-control" wire:model.defer="comment" id="comment" rows="3"></textarea>
					</div>

			      </div>

			    </div>
			</div>
		</div>
		@else

			<div class="card text-center border-light">
			  <div class="card-body">
			    <p class="card-text">@lang('¡Your cart order is empty!')</p>
			    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">@lang('Go to products')</a>
			  </div>
			</div>

		@endif
	</x-slot>


	@if(count($cart['products']) > 0)
		<x-slot name="footer">
		  <footer class="float-right">
		  	<button type="button" wire:click="checkout" class="btn btn-primary">@lang('Checkout')</button>
		  </footer>
		</x-slot>
	@endif

</x-backend.card>

