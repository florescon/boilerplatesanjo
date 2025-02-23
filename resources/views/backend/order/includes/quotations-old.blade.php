@php         
	$now = Carbon\Carbon::now();
 
        $orders = \App\Models\Order::where('type', 6)
            ->where('branch_id', '<>', 0)
            ->whereNotNull('quotation')
            ->where('created_at', '<', $now->subDays(30))
            ->get();
@endphp

@if($orders->count())
<div class="container">
  	<div class="row justify-content-md-center">
	    <div class="col col-lg-6">
			<div class="alert alert-danger text-center" role="alert">
			  	Tienes {{ $orders->count() }} cotizaciones mayores a 30 días

				    <x-utils.form-button
				        :action="route('admin.run_delete_old_orders')"
				        name="confirm-item"
		            	button-class="btn btn-outline-danger btn-lg btn-block btn-md"
		            	icon="fas fa-trash"

				        {{-- button-class="dropdown-item" --}}
				    >
				        {{ __('Delete').' '.__('Quotations') }}
				    </x-utils.form-button>
			</div>
		</div>
	</div>
</div>

@endif
