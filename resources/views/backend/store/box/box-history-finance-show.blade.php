<div class="col-xl-6 col-md-12">
    <div class="row justify-content-md-center">
		<div class="card text-center col-md-9 mt-4 shadow">
		  <div class="card-body">
		    <h4 class="card-title">Balance </h4>
		    <h3 class="text-info">${{ $cash_finances->daily_cash_closing }}</h3>
		    <h5>@lang('Initial'): ${{ $cash_finances->initial }}</h5>
		    <a href="#" class="card-link text-primary">{{ $cash_finances->incomes->count() }} @lang('Incomes'): <strong>{{ '+$'.number_format($cash_finances->amount_incomes, 2, '.', '') }}</strong></a>
		    <a href="#" class="card-link text-danger">{{ $cash_finances->expenses->count() }} @lang('Expenses'): <strong>{{ '-$'.number_format($cash_finances->amount_expenses, 2, '.', '') }}</strong></a>
		  </div>
		</div>
	</div>

	@if($cash_finances->finances->count())
	    <div class="row justify-content-md-center">
			<div class="card text-center col-md-9 shadow">
				<div class="card-body">
					<h5 class="card-title">@lang('Payment methods')</h5>
					<p class="card-text">@lang('Select payment method to filter').</p>
					@foreach($payment_methods as $payment_method) 	
						<span class="badge text-white mt-2 mr-2 {{ in_array($payment_method->id, $filter) ? 'bg-primary' : 'bg-dark' }}" 
			                  wire:click="$emit('filterByPayment', {{ $payment_method->id }})"
							  style="cursor:pointer"
						>{{ $payment_method->title }}</span>
					@endforeach
				</div>
			</div>
		</div>

	    <h3 class="text-center text-dark mt-3">
	        @lang('Incomes and expenses')<br>
	    </h3>

		<table class="table mt-3">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">@lang('Name')</th>
		      <th scope="col">@lang('Amount')</th>
		      <th scope="col">@lang('Comment')</th>
		    </tr>
		  </thead>
		  <tbody>
		  	@foreach($finances as $finance)
		    <tr>
		      <th scope="row">{{ $finance->id }}</th>
		      <td>{{ $finance->name }}</td>
		      <td class="{{ $finance->finance_text }}">
		      	{{ $finance->amount }}
		      	<p>
	            	<span class="badge badge-secondary">{{ $finance->payment_method }}</span>
				</p>
		      </td>
		      <td>
		      	{{ $finance->comment ?: '--' }}
		      	<p>
	                {!! $finance->order->folio_or_id !!}
	                {!! $finance->user_name !!}
	                {!! $finance->order_track !!}
		      	</p>
		      </td>
		    </tr>
		    @endforeach
		  </tbody>
		</table>
		@if($finances->hasMorePages())
			<div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
				<div class="card-body">
					<button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
				</div>
			</div>
		@endif

	@else
		<div class="col-xl-12 col-md-12 mt-5">
	        <h5 class="text-center text-dark font-italic">
	            @lang('No incomes and expenses were found matching your selection')
	        </h5>
	    </div>
	@endif
</div>
