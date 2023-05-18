<div class="{{ $countOrders ? 'col-xl-6' : 'col-xl-12' }} col-md-12">
	<table class="table">
	  <thead class="thead-light">
	    <tr>
	      <th scope="col">f.º</th>
	      <th scope="col">@lang('User')</th>
	      <th scope="col">@lang('Comment')</th>
	      <th scope="col">@lang('Type')</th>
	    </tr>
	  </thead>
	  <tbody>
	  	@forelse($orders as $order)
		    <tr>
		      <th scope="row">{!! $order->folio_or_id !!}</th>
		      <td>{!! $order->user_name !!}</td>
		      <td>{{ $order->comment ?: '--' }}</td>
		      <td>{!! $order->type_order !!}</td>
		    </tr>
	    @empty
		    <tr>
		    	<td colspan="4" class="text-center">
		    		@lang('No results!')
		    	</td>
		    </tr>
	    @endforelse
	  </tbody>
	</table>
</div>
