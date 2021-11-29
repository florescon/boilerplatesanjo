@if($orders->count())
	<div class="col-xl-6 col-md-12">
        <div class="row justify-content-md-center">
			<div class="card text-center col-md-9 mt-4 shadow">
			  <div class="card-body">
			    <h4 class="card-title">@lang('Daily cash closing') #{{ $cash_orders->id }}</h4>
			    <h3 class="text-info">{{ $cash_orders->title }}</h3>
			    <h4 class="text-dark">{{ $cash_orders->comment }}</h4>
			  </div>
			</div>
		</div>

        <h3 class="text-center text-dark mt-3">
            @lang('Orders')/@lang('Sales')
        </h3>

		<table class="table mt-3">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">@lang('User')</th>
		      <th scope="col">@lang('Comment')</th>
		      <th scope="col">@lang('Type')</th>
		    </tr>
		  </thead>
		  <tbody>
		  	@foreach($orders as $order)
		    <tr>
		      <th scope="row">{{ $order->id }}</th>
		      <td>{!! $order->user_name !!}</td>
		      <td>{{ $order->comment ?: '--' }}</td>
		      <td>{!! $order->type_order !!}</td>
		    </tr>
		    @endforeach
		  </tbody>
		</table>
		@if($orders->hasMorePages())
			<div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
				<div class="card-body">
					<button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
				</div>
			</div>
		@endif
	</div>
@else
	<div class="col-xl-6 col-md-12 mt-5">
        <h5 class="text-center text-dark font-italic">
            @lang('No orders were found matching your selection')
        </h5>
    </div>
@endif
