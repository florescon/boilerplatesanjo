<x-backend.card borderClass="{{ $title['color'] }}">

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
    </div>
    
    <div class="page-header-subtitle mt-5 mb-2">
    	<em>
    		@lang('Filter by updated date range')
    	</em>
    </div>

    <div class="row input-daterange">
        <div class="col-md-3 mr-2 mb-2 pr-5=">
          <x-input.date wire:model="dateInput" id="dateInput" borderClass="{{ $title['color'] }}" placeholder="{{ __('From') }}"/>
        </div>

        <div class="col-md-3 mr-2 mb-2">
          <x-input.date wire:model="dateOutput" id="dateOutput" borderClass="{{ $title['color'] }}" placeholder="{{ __('To') }}"/>
        </div>
        &nbsp;

        <div class="col-md-3 mb-2">
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-{{ $title['color'] }}" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
            <button type="button" class="btn btn-outline-{{ $title['color'] }}" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
          </div>
        </div>
        &nbsp;
    </div>
  </x-slot>

  <x-slot name="body">

	  <div class="row mb-4">
	    <div class="col form-inline">
	      @lang('Per page'): &nbsp;

	      <select wire:model="perPage" class="form-control">
	        <option>12</option>
	        <option>25</option>
	        <option>50</option>
	      </select>
	    </div><!--col-->

	    <div class="col">
	      <div class="input-group">
	        <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search by folio, tracking number or comment') }}..." />
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
	              <th scope="col">
                  <a style="color:white;" wire:click.prevent="sortBy('id')" role="button" href="#">
                    f.º
                    @include('backend.includes._sort-icon', ['field' => 'id'])
                  </a>
	              </th>
                <th scope="col">
                  @lang('Comment')
                </th>
	              <th scope="col">
	              	@lang('User')
	              </th>
                <th scope="col" class="text-center">
                  @lang('Information')
                </th>
                <th scope="col" class="text-center">
                  @lang('Status')
                </th>
                <th scope="col" class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('date_entered')" role="button" href="#">
	                  @lang('Date')
                    @include('backend.includes._sort-icon', ['field' => 'date_entered'])
                  </a>
                </th>
	              <th scope="col" class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('created_at')" role="button" href="#">
	                  @lang('Created at')
                    @include('backend.includes._sort-icon', ['field' => 'created_at'])
                  </a>
	              </th>
	              <th scope="col" class="text-center">
	                  @lang('Details')
	              </th>
	            </tr>
	          </thead>
	          <tbody>
	            @foreach($orders as $order)
		            <tr class="table-tr" data-url="{{ route('admin.store.all.edit', $order->id) }}" style="{{ $order->type_order_classes }}">
		            	<td class="align-middle">
		            		<strong>
			            		#{{ $order->id }}
			            	</strong>
		            	</td>
	                <td class="align-middle">
	                  {!! Str::limit($order->comment, 100) ?? '<span class="badge badge-secondary">'.__('undefined').'</span>' !!}
	                </td>
		              <td class="align-middle">
		              	{!! $order->user_name !!}
		              </td>
	                <td class="align-middle text-center">
	                	{!! $order->approved_label !!}
	                	{!! $order->type_order !!}
	                </td>
	                <td class="align-middle text-center" style="text-decoration: underline;">
	                   {!! $order->last_status_order_label !!}
	                </td>
	                <td class="align-middle text-center">
	                   {{ $order->date_entered->isoFormat('D, MMM') ?? __('undefined') }}
	                </td>
		              <td class="align-middle text-center">
		                <span class="badge badge-dot">
		                  <i class="bg-warning"></i> {{ $order->date_for_humans }}
		                </span>
		                {!! $order->date_diff_for_humans_created !!}
		              </td>
	                <td class="text-center">
										@if(!$order->exist_user_departament || $order->isFromStore())
											{!! $order->payment_label !!}
										@else
											<span class="badge badge-dark">@lang('Internal control')</span>
	                	@endif
	                	@if($order->parent_order_id)
		                  <span class="badge badge-primary">
		                  	@lang('Order'): <strong class="ml-1">{{ $order->parent_order }}</strong>
		                  </span>
	                  @endif
	                  {!! $order->last_order_delivery->order_delivery  ?? "<span class='badge text-dark' style='background-color: white;'>".__('Pending').'</span>' !!}
										{{-- {!! $order->from_store_or_user_label !!} --}}
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