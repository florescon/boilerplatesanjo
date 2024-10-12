<x-backend.card >

  <x-slot name="header">
  	<h3>
	    <strong class="text-{{ $title['color'] }}"> @lang($title['title']) </strong> <div class="d-inline p-3 bg-light rounded-circle"><i class="fas fa-store text-"></i></div> 
	  </h3>
    <div class="card-header-actions mb-3">
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-secondary text-dark {{ $status == 'all_store' ? 'button-large pulsate' : '' }}" :href="route('admin.store.all.index')" :text="__('all')" />
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-aqua text-dark {{ $status == 'quotations_store' ? 'button-large pulsate' : '' }}" :href="route('admin.store.all.quotations')" :text="__('Quotations')" />
      {{-- <x-utils.link class="mt-2 mr-2 card-header-action btn btn-primary text-white {{ $status == 'orders_store' ? 'button-large pulsate' : '' }}" :href="route('admin.store.all.orders')" :text="__('Orders')" /> --}}
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-coral text-white {{ $status == 'requests_store' ? 'button-large pulsate' : '' }}" :href="route('admin.store.all.requests')" :text="__('Requests')" />
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-success text-white {{ $status == 'sales_store' ? 'button-large pulsate' : '' }}" :href="route('admin.store.all.sales')" :text="__('Sales')" />
      {{-- <x-utils.link class="mt-2 mr-2 card-header-action btn btn-warning text-white {{ $status == 'mix_store' ? 'button-large pulsate' : '' }}" :href="route('admin.store.all.mix')" :text="__('Mix')" /> --}}
      <x-utils.link class="mt-2 mr-2 card-header-action btn text-dark {{ $status == 'output_products_store' ? 'button-large pulsate' : '' }}" style="background-color: #d5c5ff;" :href="route('admin.store.all.output_products')" :text="__('Output products')" />
    </div>
    
    <div class="page-header-subtitle mt-5 mb-2">
    	<em>
    		@lang('Filter by created date range')
    	</em>
    </div>

    <div class="row input-daterange">
        <div class="col-md-3 mr-2 mb-2 pr-5=">
          <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
        </div>

        <div class="col-md-3 mr-2 mb-2">
          <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
        </div>
        &nbsp;

        @if($dateInput && $dateOutput && ($status == 'requests_store'))
            <button class="btn btn-primary btn-sm ml-4 mr-4"
                wire:click="printExportOrdersForDate">
                @lang('Export')
            </button>
        @endif

        <div class="col-md-3 mb-2">
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-dark" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
            <button type="button" class="btn btn-outline-dark" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
          </div>
        </div>
        &nbsp;
    </div>
  </x-slot>

  <x-slot name="body">

    <div class="col-6 col-lg-6">

	      <strong>@lang('Delivery'): </strong>
	    <div class="col form-inline mb-2">
	      <select class="form-control text-center mt-2" style="border: 1px solid #fe8a71" wire:model.debounce.800ms="statusOrderDelivery">
	        <option value="" hidden>@lang('Select order delivery status')</option>
	        @foreach($OrderStatusDelivery as $key => $value)
	              <option value="{{ $key }}">{{ $value }}</option>
	        @endforeach
	      </select>

				@if($statusOrderDelivery)
					<button class="btn btn-danger btn-sm ml-4 pb-2 mt-2" wire:click="clearFilterStatusOrderDelivery">
						@lang('Clear status')
					</button>
				@endif
			</div>

    </div>

	  <div class="row mb-4">
	    <div class="col form-inline">
	      @lang('Per page'): &nbsp;

	      <select wire:model="perPage" class="form-control">
	        <option>12</option>
	        <option>25</option>
	      </select>
	    </div><!--col-->

	    <div class="col">
	      <div class="input-group">
	        <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search by folio, customer, tracking number or comment') }}..." />
	        @if($searchTerm !== '')
		        <div class="input-group-append">
		          <button type="button" wire:click="clear" class="close" aria-label="Close">
		            <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
		          </button>
		        </div>
	        @endif
	      </div>
	    </div>

	    @if($selected && $colors->count())
		    <div class="dropdown table-export">
		      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		        @lang('Export')        
		      </button>

		      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		        <a class="dropdown-item" wire:click="export">CSV</a>
		      </div>
		    </div><!--export-dropdown-->
	    @endif
	  </div><!--row-->

	  <div class="row mt-4">
	    <div class="col">
	      <div class="table-responsive">
	        <table class="table table-sm align-items-center js-table">
	          <thead  class="thead-dark">
	            <tr>
	              <th style="width:5%" scope="col">
                  <a style="color:white;" wire:click.prevent="sortBy('id')" role="button" href="#">
                    f.º
                    @include('backend.includes._sort-icon', ['field' => 'id'])
                  </a>
	              </th>
	              <th style="width:22%" scope="col">
	              	@lang('Customer')
	              </th>
                <th style="width:7%" scope="col" class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('date_entered')" role="button" href="#">
	                  @lang('Date')
                    @include('backend.includes._sort-icon', ['field' => 'date_entered'])
                  </a>
                </th>
                <th style="width:8%" scope="col" class="text-center">
                  @lang('Total')
                </th>
                <th style="width:8%" scope="col" class="text-center">
                	Anticipo
                </th>
                <th style="width:8%" scope="col" class="text-center">
                  @lang('Remaining')
                </th>
                <th style="width:10%" scope="col" class="text-center">
                  @lang('Status')
                </th>
	              <th style="width:10%" scope="col" class="text-center">
	                @lang('Details')
	              </th>
                <th style="width:22%" scope="col">
                  @lang('Comment')
                </th>
	            </tr>
	          </thead>
	          <tbody>
	            @foreach($orders as $order)
		            <tr class="table-tr" style="{{ $order->type_order_classes }}">
		            	<td class="align-middle">
		            		<a style="text-decoration: none !important" href="{{ route('admin.store.all.edit', $order->id) }}" target="_blank">
			            		<strong>
				            		#{!! $order->folio_or_id !!}
				            	</strong>
				            </a>
		            	</td>
		              <td class="align-middle">
		            		<a style="text-decoration: none !important; color: #000;" href="{{ route('admin.store.all.edit', $order->id) }}" target="_blank">
			              	<strong>{!! $order->user_name !!}</strong>
			              </a>
		              	<em>{{ $order->info_customer }}</em>
		              </td>
	                <td class="align-middle text-center">
	                   {{ $order->date_entered->isoFormat('D, MMM, YY') ?? __('undefined') }}
	                </td>
	                <td class="align-middle text-center">
	                	@if(!$order->isOutputProducts())
			                ${{ number_format((float)$order->total_sale_and_order, 2) }}
										@else
											N/A
										@endif
		              </td>
									<td class="align-middle text-center">
			            	{!! $order->advanced_order_label !!}
	                </td>
	                <td class="align-middle text-center">
			              {!! $order->remaining_order_label  !!}
		              </td>
	                <td class="align-middle text-center" style="text-decoration: underline;">
	                   {!! $order->last_status_order_label !!}
	                </td>
	                <td class="text-center">

										@if((!$order->exist_user_departament || $order->isFromStore()) && ($order->type != 6 && !$order->isOutputProducts()))
											{!! $order->payment_label !!}
										@else
											<span class="badge badge-dark">@lang('Internal control')</span>
	                	@endif

	                	@if($order->parent_order_id)
		                  <span class="badge badge-primary">
		                  	@lang('Order'): <strong class="ml-1">{{ $order->parent_order }}</strong>
		                  </span>
	                  @endif

	                  @if(($order->type != 6))
	                  	{!! $order->last_order_delivery->order_delivery  ?? "<span class='badge text-dark' style='background-color: white;'>".__('Pending').'</span>' !!}
	                  @endif

										{{-- {!! $order->from_store_or_user_label !!} --}}
	                </td>
	                <td class="align-middle">
	                  {!! Str::limit($order->comment, 100) ?? '<span class="badge badge-secondary">'.__('undefined').'</span>' !!}
	                  @if($order->service_orders->count())
	                  	<br>
	                    <strong>@lang('Service Orders'):</strong> <span class="badge badge-success">{{ $order->service_orders->count() }}</span>
	                  @endif
	                </td>
		            </tr>
	            @endforeach
	          </tbody>
	        </table>

	        @if($orders->count())
		        <div class="row">
		          <div class="col">
		            <nav>
		              {{ $orders->onEachSide(1)->links() }}
		            </nav>
		          </div>
		              <div class="col-sm-3 mb-2 text-muted text-right">
		                Mostrando {{ $orders->firstItem() }} - {{ $orders->lastItem() }} de {{ $orders->total() }} resultados
		              </div>
		        </div>
	        @else
	          @lang('No search results') 
	          @if($searchTerm)
	            "{{ $searchTerm }}" 
	          @endif

	          @if($dateInput) 
	            @lang('from') {{ $dateInput }} {{ $dateOutput ? __('To') .' '.$dateOutput : __('to this day') }}
	          @endif

	          @if($page > 1)
	            {{ __('in the page').' '.$page }}
	          @endif
	        @endif

	      </div>

	    </div>
	  </div>
	</x-slot>

</x-backend.card>

@push('after-scripts')
	<script type="text/javascript">
		$(function () {
		    $(".js-table").on("click", "tr[data-url]", function () {
		        window.location = $(this).data("url");
		    });
		});
	</script>
@endpush