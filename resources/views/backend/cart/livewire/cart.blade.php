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



		<table class="table">
		  <thead class="table-info">
		    <tr>
		      <th>ID</th>
		      <th scope="col">@lang('Product')</th>
		      {{-- <th scope="col">@lang('Amount')</th> --}}
		      <th scope="col">@lang('Amount')</th>
		      <th scope="col"></th>
		    </tr>
		  </thead>
		  <tbody>
		  	{{-- @json($cart['products']) --}}
            @foreach($cart['products'] as $product)
			    <tr>
		    	  <td>{{ $product->id }}</td>
			      <td>{!! '<strong>' .$product->parent->name.' </strong> ('.optional($product->color)->name.'  '.optional($product->size)->name.') ' !!}</td>
			      {{-- <td>{{ $product->amount }}</td> --}}

			      <td style="width:120px; max-width: 120px;" >
                    <livewire:backend.cart-update-form :item="$product" :key="$product->id" />
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