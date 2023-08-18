<div class="col-sm-9">

	<div class="card">
	  <div class="card-header">
	    @lang('List of products')
	  </div>
	  <div class="card-body">

    	@if($products->count() >= 1 && $type == 'quotation')
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

	    <h5 class="card-title">{{ __(ucfirst($type)) }}</h5>

	    @if($type_price != 'retail' && $products->count() >= 1)
			<h3 class="text-center">
				<span class="badge badge-primary" wire:click="updatePrices" style="cursor:pointer;">@lang('Update prices')</span>
			</h3>
		@endif

	    <hr>

	    @if($products->count() >= 1)

        <div class="table-responsive">
			<table class="table table-sm table-striped">
			  <caption>@lang('List of products') {{ $products->count() }} @lang('records') - {{ __(ucfirst($type)) }}</caption>
			  <thead>
			    <tr>
			      <th scope="col">@lang('Name')</th>
			      @if($type != 'output_products')
				      <th scope="col" class="text-center">@lang('Price')</th>
				  @endif
				      <th scope="col" class="text-center">@lang('Quantity')</th>
			      @if($type != 'output_products')
				      <th scope="col" class="text-center">@lang('Total')</th>
				  @endif
			      <th scope="col"></th>
			      <th scope="col" >@lang('Updated at')</th>
			    </tr>
			  </thead>
			  <tbody>
	            
	            @php($totalquantities = 0)
	            @php($total = 0)

			  	@foreach($products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)

				    <tr>
				      <td>{!! optional($product->product)->full_name_link !!}</td>

				      	@if($type != 'output_products')

					      <td class="text-center">
					      	@if(!$product->product->isProduct() or $type == 'quotation')

								@if($showPriceWithoutTax == false)
			                    	<livewire:backend.cartdb.price-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$type" />
									<div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
								@else
									<div class="small text-muted"> ${{ $product->price }} </div>
			                    	<livewire:backend.cartdb.price-without-taxes-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$type" />
			                    @endif

							@else
						      	${{ number_format($product->price, 2) }}
								<div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->price) }} </div>
							@endif
					      </td>

					    @endif

				      	<td wire:ignore.self>
                    		<livewire:backend.cartdb.quantity-update :item="$product" :key="now()->timestamp.$product->id" :typeCart="$type" />
				      	</td>

				      	@if($type != 'output_products')
					      <td>
					      	${{ number_format($product->total, 2) }}
							<div class="small text-muted"> ${{ priceWithoutIvaIncluded($product->total) }} </div>
					      </td>

				      	@endif

				      <td>
           				<a wire:click="removeProduct({{ $product->id }})" class="link link-dark-primary link-normal" style="cursor:pointer;" onclick="confirm('¿Seguro que desea eliminar este registro?') || event.stopImmediatePropagation()"><i class="fas fa-times text-c-blue m-l-10"></i></a> 
           			  </td>

				      <td>{{ $product->updated_at }}</td>

				    </tr>

				    <tr>
				      <th>
					  </th>
				      <th>
					  	<i class="cil-arrow-thick-top"></i>
					  	<i class="cil-arrow-thick-left"></i>
						<i class="cil-arrow-thick-left"></i>
					  </th>
				      <th class="text-left" colspan="{{ $type != 'output_products' ? '5' : '2' }}">
                    	<livewire:backend.edit-inline :cart="$product" :key="$product->id"/>
				      </th>
				    </tr>

                  @php($totalquantities += $product->quantity)
                  @php($total += $product->total)
			    
			    @endforeach

				    <td>
					    @if($type != 'output_products')
							<th class="text-right" colspan="1">Total</th>	    	
						@endif
						<td class="text-center">{{ $totalquantities }}</td>
					    @if($type != 'output_products')
							<td class="text-left text-left" colspan="3">
								${{ number_format($total, 2) }}
								<div class="small text-muted"> ${{ priceWithoutIvaIncluded($total) }} </div>
							</td>
						@else
							<td class="text-center" colspan="2"></td>
						@endif
				    </td>
		
			  </tbody>
			</table>
		</div>

		    <hr>
		    <hr>

	    @else

		    <p class="text-center">@lang('Empty')</p>

		@endif

	    @if($products->count() >= 1)

	    	<a href="#" wire:click="clearAllProducts" onkeydown="return event.key != 'Enter';" class="btn btn-danger btn-sm">@lang('Clear products')</a>

	    @endif

	  </div>
	</div>
</div>
