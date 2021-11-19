<div class="col-xl-6 col-md-12">
	<div class="row">
		<div class="col-md-6">
			<div class="card text-center order-visitor-card">
				<div class="card-block">
					<h6 class="m-b-0">@lang('Yesterday\'s income')</h6>
					<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-up m-r-15 text-c-green"></i>${{ $incomes_yesterday->sum('amount') }}</h4>
					<p class="m-b-0">{{ $incomes_yesterday->count() }} @lang('yesterday')</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card text-center order-visitor-card">
				<div class="card-block">
					<h6 class="m-b-0">@lang('Yesterday\'s expense')</h6>
					<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-down m-r-15 text-c-red"></i>${{ $expenses_yesterday->sum('amount') }}</h4>
					<p class="m-b-0">{{ $expenses_yesterday->count() }} @lang('yesterday')</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card bg-c-blue total-card">
				<div class="card-block">
					<div class="text-left">
						<h4>${{ $incomes_today->sum('amount') }}</h4>
						<p class="m-0">@lang('Today\'s income')</p>
					</div>
					<span class="label bg-c-dark value-badges">{{ $incomes_today->count() }}</span>
				</div>
		        <div class="bg-c-blue card-footer text-right">
				    <a href="#" wire:click="redirectIncomes" class="card-link text-dark">@lang('Show the list of incomes')</a>
		        </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card bg-c-red total-card">
				<div class="card-block">
					<div class="text-left">
						<h4>${{ $expenses_today->sum('amount') }}</h4>
						<p class="m-0">@lang('Today\'s expense')</p>
					</div>
					<span class="label bg-c-dark value-badges">{{ $expenses_today->count() }} </span>
				</div>
		        <div class="bg-c-red card-footer text-right">
				    <a href="#" wire:click="redirectExpenses" class="card-link text-dark">@lang('Show the list of expenses')</a>
		        </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card text-center order-visitor-card">
				<div class="card-block">
					<h6 class="m-b-0">@lang('Incomes for the week')</h6>
					<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-up m-r-15 text-c-green"></i>${{ $incomes_week->sum('amount') }}</h4>
					<p class="m-b-0">{{ $incomes_week->count() }} @lang('this week')</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card text-center order-visitor-card">
				<div class="card-block">
					<h6 class="m-b-0">@lang('Expenses for the week')</h6>
					<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-down m-r-15 text-c-red"></i>${{ $expenses_week->sum('amount') }}</h4>
					<p class="m-b-0">{{ $expenses_week->count() }} @lang('this week')</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">@lang('Name')</th>
		      <th scope="col">@lang('Amount')</th>
		      <th scope="col">@lang('Created')</th>
		    </tr>
		  </thead>
		  <tbody>
		  	@foreach($finances as $finance)
			    <tr>
			      <td>{{ $finance->name }}</td>
			      <td class="{{ $finance->finance_text }}">${{ $finance->amount }}</td>
			      <td>{{ $finance->created_at }}</td>
			    </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
	@if($finances->hasMorePages())
		<div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
			<div class="card-body">
				<button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
			</div>
		</div>
	@endif

</div>
