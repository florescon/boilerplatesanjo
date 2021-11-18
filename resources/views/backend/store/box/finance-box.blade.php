@if($finances->count())
	<div class="col-xl-6 col-md-12">
		<table class="table">
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
		      <td class="{{ $finance->finance_text }}">{{ $finance->amount }}</td>
		      <td>{{ $finance->comment ?: '--' }}</td>
		    </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
@endif