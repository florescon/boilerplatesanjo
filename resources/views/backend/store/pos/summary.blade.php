<div class="col-12 col-md-4">
    <div class="card card-product_not_hover card-product card-flyer-without-hover">
      <div class="card-body">

		<div class="card border-primary">
		  <div class="card-body">

			@if($cartVar['user'])
				<h5 class="justify-content-center text-center">
					<p>{{ $cartVar['user'][0]->name ?? '' }}</p>	
				</h5>
				<h6 class="justify-content-center text-center">
					<em>{{ $cartVar['user'][0]->customer->type_price_label ?? __('Retail price') }}</em>
				</h6>
				<h5 class="justify-content-center text-center mt-4">
					<span class="badge badge-danger" wire:click="clearUser" style="cursor:pointer;">@lang('Clear user')</span>
				</h5>
			@else
			    <livewire:backend.cart.user-cart :clear="true"/>
	        	@error('user') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
	        @endif 

		  </div>
		</div>
      </div>
  	</div>

	@if($cartVar['user'])
	    <div class="card card-product_not_hover card-product card-flyer-without-hover">
	      	<div class="card-body">

				<div class="form-group row" wire:ignore>
				    <label for="payment" class="col-sm-3 col-form-label">@lang('Payment')</label>
				    <div class="col-sm-9" >
						<input class="form-control" wire:model.defer="payment" type="number" step="any" id="payment" />
				    </div>
				</div><!--form-group-->

		        @error('payment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

	            <livewire:backend.setting.select-payment-method/>
		         
		        @error('payment_method') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

				<div class="form-group">
					<label for="comment">@lang('Comment')</label>
					<textarea class="form-control" wire:model.defer="comment" id="comment" rows="3"></textarea>
				</div>
			</div>
		</div>
	@endif

	<div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
		<div class="card-body">
			<a href="#" wire:click="checkout" class="btn btn-primary ml-3">@lang('Checkout')</a>
		</div>
	</div>

</div>