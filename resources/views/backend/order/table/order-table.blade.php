<x-backend.card borderClass="{{ $title['color'] }}">

  <x-slot name="header">
  	<h3>
	    <strong class="text-{{ $title['color'] }}"> @lang($title['title']) </strong>
	  </h3>
    <div class="card-header-actions mb-3">
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-secondary text-dark {{ $status == 'all' ? 'button-large pulsate' : '' }}" :href="route('admin.order.all')" :text="__('all')" />
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-primary text-white {{ $status == '' ? 'button-large pulsate' : '' }}" :href="route('admin.order.index')" :text="__('Orders')" />
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-success text-white {{ $status == 'sales' ? 'button-large pulsate' : '' }}" :href="route('admin.order.sales')" :text="__('Sales')" />
      <x-utils.link class="mt-2 mr-2 card-header-action btn btn-warning text-white {{ $status == 'mix' ? 'button-large pulsate' : '' }}" :href="route('admin.order.mix')" :text="__('Mix')" />
      <x-utils.link style="background-color: purple;" class="mt-2 card-header-action btn text-white {{ $status == 'suborders' ? 'button-large pulsate' : '' }}" :href="route('admin.order.suborders')" :text="__('Suborders')" />
    </div>

    <div class="page-header-subtitle mt-5 mb-2"><em>@lang('Filter by update date range')</em></div>

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
            <button type="button" class="btn btn-{{ $title['color'] }}" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
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
        <option>10</option>
        <option>25</option>
        <option>50</option>
        <option>100</option>
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
	        <table class="table table-sm align-items-center table-flush table-bordered table-hover js-table">
	          <thead style="color: #0061f2;">
	            <tr>
	              <th scope="col">
	              	@lang('Folio')
	              </th>
	              <th scope="col">
	              	@lang('Name')
	              </th>
                <th scope="col">
                  @lang('Comment')
                </th>
                <th scope="col">
                  @lang('Approved')
                </th>
                <th scope="col">
                  @lang('Status')
                </th>
	              <th scope="col">
	                  @lang('Date')
	              </th>
	              <th scope="col">
	                  @lang('Details')
	              </th>
	            </tr>
	          </thead>
	          <tbody>
	            @foreach($orders as $order)
	            <tr class="table-tr" data-url="{{ route('admin.order.edit', $order->id) }}">
	            	<td>
	            		#{{ $order->id }}
	            	</td>
	              <td>
	              	{!! $order->user_name !!}
	              </td>
                <td>
                  {!! Str::limit($order->comment, 200) ?? '<span class="badge badge-secondary">'.__('undefined').'</span>' !!}
                </td>
                <td>
                	{!! $order->approved_label !!}
                </td>
                <td>
                   {!! $order->last_status_order_label !!}

                </td>
	              <td>
	                <span class="badge badge-dot mr-4">
	                  <i class="bg-warning"></i> {{ $order->date_for_humans }}
	                </span>
	              </td>
                <td>
                	{!! $order->type_order !!}
                  <span class="badge badge-secondary"><strong>{{ $order->payment_method }}</strong></span>
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
	              <div class="col-sm-3 text-muted text-right">
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