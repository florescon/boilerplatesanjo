<div class="col-sm-9">

	<div class="card shadow-lg">
	  	<div class="card-header">
	    	<h5 class="text-dark">@lang('List of products')</h5>
	  	</div>
	  	<div class="card-body">

	    @if($products->count() >= 1)

        <div class="table-responsive">
			<table class="table table-sm table-striped">
			  <caption>@lang('List of products') {{ $products->count() }} @lang('records')</caption>
			  <thead>
			    <tr>
			      	<th scope="col">@lang('Name')</th>
			      	<th scope="col" class="text-center">@lang('Price')</th>
			    	<th scope="col" class="text-center">@lang('Quantity')</th>
			      	<th scope="col" class="text-center">@lang('Stock')</th>
			      	<th scope="col" class="text-center">@lang('Total')</th>
			      	<th scope="col"></th>
			     	<th scope="col" >@lang('Updated at')</th>
			    </tr>
			  </thead>
			  <tbody>
	            
	            @php($totalquantities = 0)
	            @php($total = 0)

			  	@foreach($products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)

				    <tr>
				      	<td>{!! optional($product->product)->full_name_and_vendor_link !!}</td>
				      	<td class="text-center">
				      		${{ number_format($product->price, 2) }}
							<div class="small text-muted"> ${{ priceIncludeIva($product->price) }} </div>
				      	</td>
				      	<td class="text-center">
				      		{{ $product->quantity }}
				      	</td>
				      	<td class="text-center">
				      		{{ optional($product->product)->stock }}
				      	</td>
				      	<td>
				      		${{ number_format($product->total, 2) }}
							<div class="small text-muted"> ${{ priceIncludeIva($product->total) }} </div>
				      	</td>
				      	<td>
           					<a wire:click="removeMaterial({{ $product->id }})" class="link link-dark-primary link-normal" style="cursor:pointer;" onclick="confirm('¿Seguro que desea eliminar este registro?') || event.stopImmediatePropagation()"><i class="fas fa-times text-c-blue m-l-10"></i></a> 
           			  	</td>
				      	<td>{{ $product->updated_at }}</td>
				    </tr>

				    <tr>
				    </tr>

                  @php($totalquantities += $product->quantity)
                  @php($total += $product->total)
			    
			    @endforeach

				    <td>
						<th class="text-right" colspan="1">Total</th>	    	
						<td class="text-center">{{ $totalquantities }}</td>
						<td class="text-center"></td>
						<td class="text-left text-left" colspan="3">
							${{ number_format($total, 2) }}
							<div class="small text-muted"> ${{ priceIncludeIva($total) }} </div>
						</td>
				    </td>
		
			  </tbody>
			</table>
		</div>

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
