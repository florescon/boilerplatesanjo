@if($finances->count())
	<div class="col-xl-6 col-md-12">
		<table class="table">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">f.º</th>
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
		      <td>{{ $finance->comment ?: '--' }}</td>
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
	</div>
@endif