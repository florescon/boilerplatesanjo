	<div class="container">
		<div class="row">
			@if(count($cart['products']) > 0 || count($cart['products_sale']))
			<div class="col-lg-9">
				<div class="cart-table-container">
					<table class="table table-cart">
						<thead>
							<tr>
								<th class="product-col">@lang('Product')</th>
								<th class="price-col">@lang('Price')</th>
								<th class="qty-col">@lang('Qty')</th>
								<th>@lang('Subtotal')</th>
							</tr>
						</thead>
						<tbody>

							@foreach($cart['products'] as $product)
							<tr class="product-row">
								<td class="product-col">
									<figure class="product-image-container">
										<a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="product-image">
                    						@if($product->parent->file_name)
						                        <img src=" {{ asset('/storage/' . $product->parent->file_name) }}" alt="product" width="80" >
							                @else
							                    <img src="{{ asset('/porto/assets/images/not0.png')}}" alt="{{ $product->parent->name }}" width="80">
							                @endif

										</a>
									</figure>
									<h4 class="product-title">
										<a href="{{ route('frontend.shop.show', $product->parent->slug) }}">{!! $product->parent->name !!}</a>
									</h4>
									<h5 class="product-title">
										&nbsp;&nbsp;&nbsp; 
										<i class="sicon-arrow-right" style="color:red"></i>
										&nbsp;&nbsp;&nbsp; 
										<a style="color:blue;">{!! $product->only_attributes !!}</a>
									</h5>
								</td>
								<td>${{!is_null($product->price) || $product->price != 0 ? 
						      			$product->price : $product->parent->price 
						      	}}</td>
								<td>
									{{-- <input class="vertical-quantity form-control" type="text"> --}}
									<livewire:backend.cart-update-form :item="$product" :key="$product->id" :typeCart="'products'"/>
								</td>
								<td>$17.90</td>
							</tr>
							<tr class="product-action-row">
								<td colspan="4" class="clearfix">
									<div class="float-left">
										<a href="#" class="btn-move">@lang('Move to wishlist')</a>
									</div><!-- End .float-left -->
									
									<div class="float-right">
										<a  wire:click="removeFromCartList({{ $product->id }})" title="Remove product" class="btn-remove"><span class="sr-only">Remove</span>
											<i class="sicon-close"></i></a>
									</div><!-- End .float-right -->
								</td>
							</tr>
							@endforeach

						</tbody>

						<tfoot>
							<tr>
								<td colspan="4" class="clearfix">
									<div class="float-left">
										<a href="{{ route('frontend.shop.index') }}" class="btn btn-outline-secondary">@lang('Continue shopping')</a>
									</div><!-- End .float-left -->

									<div class="float-right">
										<a href="#" class="btn btn-outline-secondary btn-clear-cart">@lang('Clear cart')</a>
									</div><!-- End .float-right -->
								</td>
							</tr>
						</tfoot>
					</table>
				</div><!-- End .cart-table-container -->

				<div class="cart-discount">
					<h4>@lang('Apply discount code')</h4>
					<form action="#">
						<div class="input-group">
							<input type="text" class="form-control form-control-sm" placeholder="@lang('Enter discount code')"  required>
							<div class="input-group-append">
								<button class="btn btn-sm btn-primary" type="submit">@lang('Apply discount')</button>
							</div>
						</div><!-- End .input-group -->
					</form>
				</div><!-- End .cart-discount -->
			</div><!-- End .col-lg-8 -->

			<div class="col-lg-3">
				<div class="cart-summary">
					<h3>@lang('Summary')</h3>

					<h4>
						<a data-toggle="collapse" href="#total-estimate-section" class="collapsed" role="button" aria-expanded="false" aria-controls="total-estimate-section">@lang('Estimated prices')</a>
					</h4>

					<div class="collapse" id="total-estimate-section">
						<form action="#">
							<div class="form-group form-group-sm">
								<label>Country</label>
								<div class="select-custom">
									<select class="form-control form-control-sm">
										<option value="USA">United States</option>
										<option value="Turkey">Turkey</option>
										<option value="China">China</option>
										<option value="Germany">Germany</option>
									</select>
								</div><!-- End .select-custom -->
							</div><!-- End .form-group -->

							<div class="form-group form-group-sm">
								<label>State/Province</label>
								<div class="select-custom">
									<select class="form-control form-control-sm">
										<option value="CA">California</option>
										<option value="TX">Texas</option>
									</select>
								</div><!-- End .select-custom -->
							</div><!-- End .form-group -->

							<div class="form-group form-group-sm">
								<label>Zip/Postal Code</label>
								<input type="text" class="form-control form-control-sm">
							</div><!-- End .form-group -->

							<div class="form-group form-group-custom-control">
								<label>Flat Way</label>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="flat-rate">
									<label class="custom-control-label" for="flat-rate">Fixed $5.00</label>
								</div><!-- End .custom-checkbox -->
							</div><!-- End .form-group -->

							<div class="form-group form-group-custom-control">
								<label>Best Rate</label>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="best-rate">
									<label class="custom-control-label" for="best-rate">Table Rate $15.00</label>
								</div><!-- End .custom-checkbox -->
							</div><!-- End .form-group -->
						</form>
					</div><!-- End #total-estimate-section -->

					<table class="table table-totals">
						<tbody>
							<tr>
								<td>Subtotal</td>
								<td>$17.90</td>
							</tr>

							<tr>
								<td>IVA</td>
								<td>$0.00</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td>Order Total</td>
								<td>$17.90</td>
							</tr>
						</tfoot>
					</table>

					<div class="checkout-methods">
						<a href="#" class="btn btn-block btn-sm btn-primary">@lang('Go to checkout')</a>
						<a href="#" class="btn btn-link btn-block">Verificar direccion</a>
					</div><!-- End .checkout-methods -->
				</div><!-- End .cart-summary -->
			</div><!-- End .col-lg-4 -->
			@else

				<div class="container-fluid mt-100">
				    <div class="row">
				        <div class="col-md-12">
				            <div class="card">
				                <div class="card-header">
				                    <h5>Cart</h5>
				                </div>
				                <div class="card-body cart text-center">
				                    	{{-- <img src="{{ asset('img/cart.png') }}" c width="130" height="130" class="img-fluid mb-4 mr-3"> --}}
				                        <h3><strong>@lang('Your Cart is Empty')</strong></h3>
				                        <h4>@lang('Add something to make you happy')</h4> <a href="{{ route('frontend.shop.index') }}" class="btn btn-primary cart-btn-transform m-3" data-abc="true">@lang('continue shopping')</a>
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
			@endif
		</div><!-- End .row -->
	</div><!-- End .container -->
