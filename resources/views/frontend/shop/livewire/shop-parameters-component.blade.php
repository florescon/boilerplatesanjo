<div>
	{{-- {{ $amount }} --}}
	<div class="product-filters-container">
		<div class="product-single-filter">
			<label>@lang('Colors'):</label>
			<ul class="config-size-list">
				@foreach($attributes->children->unique('color_id')->sortBy('color.name') as $children)

					<li>
						<a 
							class="{{ $color_id ==  $children->color_id ? 'gradient-border' : '' }}"
		                	wire:click.prevent="setColor({{ $children->color_id }})" href="#"
							style="background-color: {{ optional($children->color)->color }}; ">
						</a>
					</li>

				@endforeach
			</ul>
		</div><!-- End .product-single-filter -->
        @error('color_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

		<div class="product-single-filter">
			<label>@lang('Sizes'): </label>
			<ul class="config-size-list">
				@foreach($attributes->children->unique('size_id')->sortBy('size.name') as $children) 

					<li>
						<a 
		                	class="{{ $size_id ==  $children->size_id ? 'gradient-border' : '' }}"
		                	wire:click.prevent="setSize({{ $children->size_id }})" href="#"
						>{{ optional($children->size)->short_name }}
						</a>
					</li>

				@endforeach
			</ul>
		</div><!-- End .product-single-filter -->
        @error('size_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
	</div><!-- End .product-filters-container -->

	<div wire:ignore>
		<hr class="divider">

		<div class="product-action">
			<div class="product-single-qty">
				<input onchange="@this.set('amount', this.value)" id="amount" name="amount" class="horizontal-quantity form-control" type="text">
			</div><!-- End .product-single-qty -->

			<a wire:click="add_cart" class="btn btn-dark add-cart icon-shopping-cart text-white" title="Add to Cart">@lang('Add to cart')</a>

          {{-- <button type="button" class="btn btn-primary btn-md mr-1 mb-2">Buy now</button>
          <button type="button" class="btn btn-light btn-md mr-1 mb-2"><i class="fas fa-shopping-cart pr-2"></i>Add to cart</button> --}}

		</div><!-- End .product-action -->
	</div>

    <div>
		<hr class="divider">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>

</div>