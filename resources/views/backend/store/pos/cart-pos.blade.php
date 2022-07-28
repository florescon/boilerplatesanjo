<div class="col-xl-6 col-md-12">

	@if(count($cartVar['products']) || count($cartVar['products_sale']))
		<div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
			<div class="card-body">
				<a href="#" wire:click="clearCartAll" class="btn btn-danger mr-3">@lang('Clear cart')</a>
				<a href="#" wire:click="checkout" class="btn btn-primary ml-3">@lang('Checkout')</a>
			</div>
		</div>
    @endif

	<div class="card table-card">
		<div class="card-header">

			<div class="badge {{ count($cartVar['products']) ? 'badge-primary' : 'badge-secondary' }} text-wrap" >
			  <h5 class="text-white">
			  	@lang('Cart order')
			  </h5>
			</div>

			<div class="card-header-right">
				<ul class="list-unstyled card-option">
					<li class="text-monospace text-c-blue font-weight-bold">
						{{ count($cartVar['products']) != 0 ? count($cartVar['products']) : '' }}
					</li>
				</ul>
			</div>
		</div>
		<div class="card-block">
			<div class="table-responsive">
				<table class="table table-hover m-b-0 without-header">
					<tbody>
			            @forelse($cartVar['products'] as $product)
							<tr>
								<td>
									<div class="d-inline-block align-middle">
	                                    <img alt="product image" class="img-40 align-top m-r-15" src="{{ asset('/storage/' . optional($product->parent)->file_name) }}" onerror="this.onerror=null;this.src='/img/ga/not0.png';" >

										<div class="d-inline-block">
											<h6>{!! $product->full_name !!}</h6>
											<p class="text-muted m-b-0">@lang('General price'): ${{ $product->getPriceWithIvaApply(optional($product->parent)->price) }} </p>
											<p class="m-b-0">${{ $product->getPriceWithIvaApply($product->price_subproduct_label) }}</p>
										</div>
									</div>
								</td>
								<td class="text-right col-3">
			                    	<livewire:backend.cart-update-form :item="$product" :key="now()->timestamp.$product->id" :typeCart="'products'" />
								</td>
								<td class="text-right">
									<h6 class="f-w-700 mt-2">
										<livewire:backend.cart-show-price :product="$product" :key="now()->timestamp.$product->id" :typeCart="'products'" />
									</h6>

                       				<a wire:click="removeFromOrderList({{ $product->id }})" class="link link-dark-primary link-normal"  style="cursor:pointer;"><i class="fas fa-times text-c-blue m-l-10 mt-4"></i></a> 

								</td>
							</tr>
						@empty
						<tr>
							<td>
								<div class="d-inline-block align-middle">
									<div class="d-inline-block">
										<h6>@lang('Your cart order is empty!')</h6>
									</div>
								</div>
							</td>
						</tr>
						@endforelse
						@if($cartVar['products'])
							<tr class="text-monospace">
								<td class="text-right">
									Total
								</td>
								<td class="text-center">
									<livewire:backend.cart-show-total/>
								</td>
								<td class="text-center">
							      	<livewire:backend.cart-show-price-total :typeCart="'products'"/>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card table-card">
		<div class="card-header">
			<div class="badge {{ count($cartVar['products_sale']) ? 'badge-success' : 'badge-secondary' }} text-wrap" >
			  <h5 class=" text-white">
			  	@lang('Shopping cart')
			  </h5>
			</div>

			<div class="card-header-right">
				<ul class="list-unstyled card-option">
					<li class="text-monospace text-c-green font-weight-bold">
						{{ count($cartVar['products_sale']) != 0 ? count($cartVar['products_sale']) : ''  }}
					</li>
				</ul>
			</div>
		</div>
		<div class="card-block">
			<div class="table-responsive">
				<table class="table table-hover m-b-0 without-header">
					<tbody>
			            @forelse($cartVar['products_sale'] as $product)
							<tr>
								<td>
									<div class="d-inline-block align-middle">
	                                    <img alt="product image" class="img-40 align-top m-r-15" src="{{ asset('/storage/' . optional($product->parent)->file_name) }}" onerror="this.onerror=null;this.src='/img/ga/not0.png';" >
										<div class="d-inline-block">
											<h6>{!! $product->full_name !!}</h6>
											<p class="text-muted m-b-0">@lang('General price'): ${{ optional($product->parent)->price }}</p>
											<p class="m-b-0">${!! $product->price_subproduct !!}</p>
										</div>
									</div>
								</td>
								<td class="text-right col-3">
			                    	<livewire:backend.cart-update-form :item="$product" :key="now()->timestamp.$product->id" :typeCart="'products_sale'" />
								</td>
								<td class="text-right">
									<h6 class="f-w-700 mt-2">
										<livewire:backend.cart-show-price :product="$product" :key="now()->timestamp.$product->id" :typeCart="'products_sale'" />
									</h6>
                           				<a wire:click="removeFromSaleList({{ $product->id }})" class="link link-dark-primary link-normal"  style="cursor:pointer;" ><i class="fas fa-times text-c-green m-l-10  mt-4"></i></a> 

								</td>
							</tr>
						@empty
						<tr>
							<td>
								<div class="d-inline-block align-middle">
									<div class="d-inline-block">
										<h6>@lang('Your cart is empty!')</h6>
									</div>
								</div>
							</td>
						</tr>
						@endforelse
						@if($cartVar['products_sale'])
							<tr class="text-monospace">
								<td class="text-right">
									Total
								</td>
								<td class="text-center">
									<livewire:backend.cart-show-sale-total/>
								</td>
								<td class="text-center">
							      	<livewire:backend.cart-show-price-total :typeCart="'products_sale'"/>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	@if(count($cartVar['products']) || count($cartVar['products_sale']))
		<div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
			<div class="card-body">
				<a href="#" wire:click="clearCartAll" class="btn btn-danger mr-3">@lang('Clear cart')</a>
				<a href="#" wire:click="checkout" class="btn btn-primary ml-3">@lang('Checkout')</a>
			</div>
		</div>
    @endif

</div>