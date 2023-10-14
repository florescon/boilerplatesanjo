<div class="col-sm-9">

	<div class="card shadow-lg">
	  <div class="card-header">
	    <h5 class="text-dark">@lang('List of feedstocks')</h5>
	  </div>
	  <div class="card-body">

	    @if($products->count() >= 1)

        <div class="table-responsive">
			<table class="table table-sm table-striped">
			  <caption>@lang('List of feedstocks') {{ $products->count() }} @lang('records')</caption>
			  <thead>
			    <tr>
			      <th scope="col">@lang('Name')</th>
			      <th scope="col" class="text-center">@lang('Price')</th>
			      <th scope="col" class="text-center">@lang('Quantity')</th>
			      <th scope="col" class="text-center">@lang('Total')</th>
			      <th scope="col"></th>
			      <th scope="col" >@lang('Updated at')</th>
			    </tr>
			  </thead>
			  <tbody>
	            
	            @php($totalquantities = 0)
	            @php($total = 0)

			  	@foreach($products->sortBy([['material.name', 'asc'], ['material.color.name', 'asc'], ['material.size.sort', 'asc']])  as $product)

				    <tr>
				      <td>{!! optional($product->material)->full_name !!}</td>


					      <td class="text-center">
					      	
						      	${{ number_format($product->price, 2) }}
								<div class="small text-muted"> ${{ priceIncludeIva($product->price) }} </div>
					      </td>

				      	<td wire:ignore.self>
                    		<livewire:backend.material.quantity-update-feedstock :item="$product" :key="now()->timestamp.$product->id" />
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
				      <th>
					  </th>
				      <th>
				      	<span class="badge badge-primary">@lang('Comment')</span>
					  </th>
				      <th class="text-left" colspan="{{ '5' }}">
                    	<livewire:backend.material.edit-inline-feedstock :cart="$product" :key="$product->id"/>
				      </th>
				    </tr>

                  @php($totalquantities += $product->quantity)
                  @php($total += $product->total)
			    
			    @endforeach

				    <td>
							<th class="text-right" colspan="1">Total</th>	    	
						<td class="text-center">{{ $totalquantities }}</td>
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

	    	<a href="#" wire:click="clearAllProducts" onkeydown="return event.key != 'Enter';" class="btn btn-danger btn-sm">@lang('Clear feedstocks')</a>

	    @endif

	  </div>
	</div>
</div>
