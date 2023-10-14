<div class="col-sm-3">
	<div class="card shadow-lg">
	  <div class="card-header">
	    <h5 class="text-dark">@lang('Summary')</h5>
	  </div>
	  <div class="card-body">
	    {{-- <h5 class="card-title">Special title treatment</h5> --}}
	    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
	    @if($customer)
			<h5 class="justify-content-center text-center">
				<p> {{ $summary->customer->name }} </p>	
			</h5>

			@if($type != 'output_products')
				<h6 class="justify-content-center text-center">
					<strong><em>{{ $summary->customer->customer->type_price_label ?? __('Retail price') }}</em></strong>
				</h6>
			@endif

			@if($address)
				<h6 class="justify-content-center text-center mt-4">
					<em><strong>@lang('Address'): </strong>{{ $address }}</em>
				</h6>
			@endif

			@if($phone)
				<h6 class="justify-content-center text-center">
					<em><strong>@lang('Phone'): </strong>{{ $phone }}</em>
				</h6>
			@endif

			@if($rfc)
				<h6 class="justify-content-center text-center">
					<em><strong>@lang('RFC'): </strong>{{ $rfc }}</em>
				</h6>
			@endif

			<h5 class="justify-content-center text-center mb-4 mt-4">
				<span class="badge badge-danger" wire:click="clearUser" style="cursor:pointer;">@lang('Clear customer')</span>
			</h5>
		@else
		    <livewire:backend.cart.user-cart :clear="true"/>
    		@error('user') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
		@endif

		<div class="input-group mb-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="basic-addon1">@lang('Description')</span>
		  </div>
		  <textarea class="form-control text-center" wire:model.lazy="description" aria-label="description" aria-describedby="basic-addon1" rows="3">
		  </textarea>
		</div>

		@if($customer && $countProducts)
			{{-- <div class="text-center mt-5">
			    <a href="#" wire:click="checkout" onclick="confirm('¿Verificó cantidades y totales?') || event.stopImmediatePropagation()" class="btn btn-success" onkeydown="return event.key != 'Enter';"> @lang('Save') {{ __(ucfirst($type)) }}</a>
			</div> --}}
		@endif

	  </div>
	</div>
</div>
