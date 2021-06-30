{{-- <div class="card"> --}}
<div class="card shadow-lg p-3 mb-5 bg-white rounded">
	<div class="card-header">
	    <strong style="color: #0061f2;"> @lang('List of products') </strong>
	</div>

	<div class="card-body">


	<div class="row mb-4 justify-content-md-center">
		<div class="col-8">
		  <div class="input-group">
		    <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search') }}..." />
		    @if($searchTerm !== '')
		    <div class="input-group-append">
		      <button type="button" wire:click="clear" class="close" aria-label="Close">
		        <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
		      </button>

		    </div>
		    @endif
		  </div>
		</div>
	</div>

	  <div class="row mt-4">
	    <div class="col">
	      <div class="table-responsive">
	        <table class="table table-sm align-items-center table-flush table-bordered table-hover">
	          <thead style="color: #0061f2;">
	            <tr>
	              <th scope="col">
	                  @lang('Name')
	              </th>

			      <th scope="col">@lang('Stock')</th>
			      <th scope="col">@lang('S.R.I')</th>
			      <th scope="col">@lang('Store stock')</th>

	              <th scope="col">
	                  @lang('Updated at')
	              </th>
	              <th scope="col" style="width:200px; max-width: 200px;">@lang('Actions')</th>
	            </tr>
	          </thead>
	          <tbody>
	            @foreach($products as $product)
    	        <tr>
	              <td scope="row">
	                <div class="media align-items-center">
	                  <div class="media-body">
	                    <span class="mb-0 text-sm">{!! '<strong>' .$product->parent->name.' </strong> ('.optional($product->color)->name.'  '.optional($product->size)->name.') ' !!}</span>
	                  </div>
	                </div>
	              </td>

	              <td>
	              	{{ $product->stock }}
	              </td>
	              <td>
	              	{{ $product->stock_revision }}
	              </td>
	              <td>
	              	{{ $product->stock_store }}
	              </td>

	              <td>
	                <span class="badge badge-dot mr-4">
	                  <i class="bg-warning"></i> {{ $product->date_for_humans }}
	                </span>
	              </td>
	              <td>

                    <a  href="{{ route('admin.product.consumption_filter', $product->id) }}" class="btn btn-transparent-dark">
                      <i class='cil-list-filter'></i>
                      Consumo
                    </a>

	              </td>
    	        </tr>
    	        @endforeach

	          </tbody>
	      	</table>
	  	  </div>
		</div>
	  </div>

		<div class="row mt-4">
			<div class="col">
			    @if($products->count())
			    <div class="row">
			      <div class="col">
			        <nav>
			          {{ $products->links() }}
			        </nav>
			      </div>
			          <div class="col-sm-3 text-muted text-right">
			            Mostrando {{ $products->firstItem() }} - {{ $products->lastItem() }} de {{ $products->total() }} resultados
			          </div>
			    </div>

			    @else
			      @lang('No search results') 
			      @if($searchTerm)
			        "{{ $searchTerm }}" 
			      @endif

			      @if($page > 1)
			        {{ __('in the page').' '.$page }}
			      @endif
			    @endif

			</div>
		</div>

	</div>

 {{-- </div> --}}