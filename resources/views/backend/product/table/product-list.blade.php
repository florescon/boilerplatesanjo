<div class="card shadow-lg p-3 mb-5 bg-white rounded">
	<div class="card-header">
	  <strong style="color: #0061f2;"> @lang('List of products') </strong>
    <div class="page-header-subtitle mt-5 mb-2">@lang('Filter by update date range')</div>
    <div class="row input-daterange">
        <div class="col-md-3 mb-3">
          <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
        </div>
        <div class="col-md-3 mb-3">
          <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
        </div>
        <div class="col-md-3">
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
            <button type="button" class="btn btn-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
          </div>
        </div>
    </div>
	</div>

	<div class="card-body">
		<div class="row d-flex flex-row-reverse mb-2">
	    @if($selected && $products->count())
	    <div class="dropdown table-export" style="margin-right: 100px;">
	      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	        @lang('Export store stock')
	      </button>

	      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('csv', 'store')">CSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('xlsx', 'store')">Excel</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('xls', 'store')">Excel ('XLS')</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('html', 'store')">HTML</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('tsv', 'store')">TSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('ods', 'store')">ODS</a>
	      </div>
	    </div><!--export-dropdown-->
	    @endif
	    @if($selected && $products->count())
	    <div class="dropdown table-export">
	      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	        @lang('Export revision stock')
	      </button>

	      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('csv', 'revision')">CSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('xlsx', 'revision')">Excel</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('xls', 'revision')">Excel ('XLS')</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('html', 'revision')">HTML</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('tsv', 'revision')">TSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('ods', 'revision')">ODS</a>
	      </div>
	    </div><!--export-dropdown-->
	    @endif
	    @if($selected && $products->count())
	    <div class="dropdown table-export">
	      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	        @lang('Export main stock')        
	      </button>

	      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('csv', 'main')">CSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('xlsx', 'main')">Excel</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('xls', 'main')">Excel ('XLS')</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('html', 'main')">HTML</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('tsv', 'main')">TSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsiteCustom('ods', 'main')">ODS</a>
	      </div>
	    </div><!--export-dropdown-->
	    @endif

		</div>
		<div class="row mb-4 justify-content-md-center">
	    <div class="col form-inline">
	      @lang('Per page'): &nbsp;

	      <select wire:model="perPage" class="form-control">
	        <option>15</option>
	        <option>25</option>
	        <option>50</option>
	        <option>100</option>
	        <option>200</option>
	      </select>
	    </div><!--col-->

			<div class="col">
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

	    @if($selected && $products->count())
	    <div class="dropdown table-export">
	      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	        @lang('Export')        
	      </button>

	      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	        <a class="dropdown-item" wire:click="exportMaatwebsite('csv')">CSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsite('xlsx')">Excel</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsite('xls')">Excel ('XLS')</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsite('html')">HTML</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsite('tsv')">TSV</a>
	        <a class="dropdown-item" wire:click="exportMaatwebsite('ods')">ODS</a>
	      </div>
	    </div><!--export-dropdown-->
	    @endif

	</div>


	@if($selectPage)
		<x-utils.alert type="primary">
		  @unless($selectAll)
		  <span>Tienes seleccionado <strong>{{ $products->count() }}</strong> productos, ¿quieres seleccionar  <strong>{{ $products->total() }} </strong> productos?</span>
		    <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
		  @else
		    <span>Actualmente seleccionaste <strong>{{ $products->total() }}</strong> productos.</span>
		  @endif

	    <em>-- @lang('Order by name') --</em>

		</x-utils.alert>
	@endif

	  <div class="row mt-4">
	    <div class="col">
	      <div class="table-responsive">
	        <table class="table table-sm align-items-center table-flush table-bordered table-hover">
	          <thead style="color: #0061f2;">
	            <tr>

	              <th style="width:30px; max-width: 30px;">
	                <label class="form-checkbox">
	                  <input type="checkbox" wire:model="selectPage">
	                  <i class="form-icon"></i>
	                </label>
	              </th>

	              <th scope="col">
	                <a wire:click.prevent="sortBy('code')" role="button" href="#">
	                  @lang('Code')
	                  @include('backend.includes._sort-icon', ['field' => 'code'])
	                </a>
	              </th>
	              <th scope="col">
	                  @lang('Name')
	              </th>
					      <th scope="col">
	                <a wire:click.prevent="sortBy('stock')" role="button" href="#">
	                  @lang('Stock')
	                  @include('backend.includes._sort-icon', ['field' => 'stock'])
	                </a>
					    	</th>
					      <th scope="col">
	                <a wire:click.prevent="sortBy('stock_revision')" role="button" href="#">
	                  @lang('S.R.I')
	                  @include('backend.includes._sort-icon', ['field' => 'stock_revision'])
	                </a>
					      </th>
					      <th scope="col">
	                <a wire:click.prevent="sortBy('stock_store')" role="button" href="#">
	                  @lang('Store stock')
	                  @include('backend.includes._sort-icon', ['field' => 'stock_store'])
	                </a>
					      </th>

	              <th scope="col">
	                <a wire:click.prevent="sortBy('updated_at')" role="button" href="#">
	                  @lang('Updated at')
	                  @include('backend.includes._sort-icon', ['field' => 'updated_at'])
	                </a>
	              </th>
	              <th scope="col">
	                  @lang('Barcode Label')
	              </th>
	              <th scope="col" style="width:200px; max-width: 200px;">@lang('Actions')</th>
	            </tr>
	          </thead>
	          <tbody>
	            @foreach($products as $product)
    	        <tr>
	              <td>
	                <label class="form-checkbox">
	                    <input type="checkbox" wire:model="selected" value="{{ $product->id }}">
	                  <i class="form-icon"></i>
	                  </label>
	              </td>
	              <td scope="row">
	              	{!! $product->code_subproduct !!}
	              </td>
	              <td scope="row">
	                <div class="media align-items-center">
	                  <div class="media-body">
	                    <span class="mb-0 text-sm">{!! '<strong>' .optional($product->parent)->name.' </strong> ('.optional($product->color)->name.'  '.optional($product->size)->name.') ' !!}</span>
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

				          <a href="{{ route('admin.product.large-barcode', $product->id) }}" target="_blank"><span class='badge badge-dark'><i class="cil-print"></i> @lang('Large')</span></a>

				          <a href="{{ route('admin.product.short-barcode', $product->id) }}" target="_blank"><span class='badge badge-info'><i class="cil-print"></i> @lang('Short')</span></a>

	              </td>
	              <td>
						      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-warning text-white" :href="route('admin.product.consumption_filter', $product->id)" :text="__('Punctual consumption')" />
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