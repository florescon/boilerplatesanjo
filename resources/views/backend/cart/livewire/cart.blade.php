<x-backend.card>


	@if(count($cart['products']) > 0)
		<x-slot name="header">
            <strong style="color: #0061f2;"> @lang('Cart order') </strong>
	 	</x-slot>
	 @endif

  	<x-slot name="headerActions">
      <x-utils.link class="card-header-action" wire:click="clearCart" :text="__('Clear cart')" />
	</x-slot>


    <x-slot name="body">
        @if(count($cart['products']) > 0)


		<div class="col-12">
			<div class="d-flex justify-content-center">

				<div class="custom-control custom-switch custom-control-inline">
					<input type="checkbox" wire:model="editAmount" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
				<label class="custom-control-label" for="customRadioInline1">@lang('Edit amount')</label>
				</div>
	        </div>
	    </div>

	    <br>

		<table class="table">
		  <thead class="table-info">
		    <tr>
		      <th scope="col">@lang('Product')</th>
		      <th scope="col">@lang('Amount')</th>
		      <th scope="col"></th>
		      <th scope="col"></th>
		    </tr>
		  </thead>
		  <tbody>
		  	{{-- @json($cart['products']) --}}
            @foreach($cart['products'] as $product)
			    <tr>
			      <td>{!! '<strong>' .$product->parent->name.' </strong> ('.optional($product->color)->name.'  '.optional($product->size)->name.') ' !!}</td>
			      <td>{{ $product->amount }}</td>

			      <td style="width:100px; max-width: 100px;" >
				      	<input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit;" wire:model.defer="inputedit.{{ $product->amount }}.amount" wire:keydown.enter="increase({{ $product->id }})" type="number" min="1" placeholder="+" required>
			      </td>

			      <td>

					<a wire:click="removeFromCart({{ $product->id }})" class="badge badge-danger text-white">@lang('Delete')</a>

			  	  </td>
			    </tr>
		    @endforeach
		  </tbody>
		</table>
		@else

			<div class="card text-center border-light">
			  <div class="card-body">
			    <p class="card-text">@lang('¡Your cart order is empty!')</p>
			    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">@lang('Go to products')</a>
			  </div>
			</div>

		@endif
	</x-slot>


	@if(count($cart['products']) > 0)
		<x-slot name="footer">
		  <footer class="float-right">
		  	<button type="button" class="btn btn-primary">@lang('Checkout')</button>
		  </footer>
		</x-slot>
	@endif

</x-backend.card>