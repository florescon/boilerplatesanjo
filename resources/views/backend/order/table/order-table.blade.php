<div class="card shadow-lg p-3 mb-5 bg-white rounded">

	<div class="card-header">
    <strong style="color: #0061f2;"> @lang('List of orders') </strong>

    <div class="card-header-actions">
       <em> Última petición: {{ now()->format('h:i:s') }} </em>
    </div>

    <br>
    <br>
    &nbsp;

    <div class="page-header-subtitle">@lang('Filter by update date range')</div>

    <div class="row input-daterange">
        <div class="col-md-3">
          <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
        </div>
        &nbsp;

        <div class="col-md-3">
          <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
        </div>
        &nbsp;

        <div class="col-md-3">
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
            <button type="button" class="btn btn-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
          </div>
        </div>
        &nbsp;
    </div>
	</div>

	<div class="card-body">

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
	                  @lang('Type')
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
	              	{{ optional($order->user)->name }}
	              </td>
                <td>
                  {!! $order->comment ?? '<span class="badge badge-secondary">'.__('undefined').'</span>' !!}
                </td>
                <td>
                	{!! $order->approved_label !!}
                </td>
                <td>
                   {!! $order->last_status_order->name_status ?? '<span class="badge badge-secondary">'.__('undefined').'</span>' !!}
                </td>
	              <td>
	                <span class="badge badge-dot mr-4">
	                  <i class="bg-warning"></i> {{ $order->date_for_humans }}
	                </span>
	              </td>
                <td>
                	{!! $order->type_order !!}
                </td>
	            </tr>
	            @endforeach
	          </tbody>
	        </table>

	        @if($orders->count())
	        <div class="row">
	          <div class="col">
	            <nav>
	              {{ $orders->links() }}
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
	</div>


</div>


@push('after-scripts')

<script type="text/javascript">
	
$(function () {

    $(".js-table").on("click", "tr[data-url]", function () {
        window.location = $(this).data("url");
    });

});

</script>

@endpush