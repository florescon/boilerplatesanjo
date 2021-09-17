<div>
	<div class="row pt-4">
		<div class="col-12">
			<h6 class="mb-3">
				@lang('Colors'):
			</h6>
		</div>
		<div class="col-12 col-md-9 col-lg-7 col-xl-12">
				<div class="form-group mx-auto">
					{{-- @json($color_id) --}}
					<div class="row px-1">
						@foreach($attributes->children->unique('color_id')->sortBy('color.name') as $children)
						<div class="col px-1" style="{{ optional($children->color)->color ? 'min-width:3rem; max-width: 3rem;' : 'max-width: 100px;'}} height:3rem">
							<input class="checkbox-color" type="radio" wire:model="color_id" value="{{ $children->color_id }}" name="color" id="color-{{ $children->color_id }}">
							<label class="for-checkbox-color" style=" color: {{ optional($children->color)->secondary_color ?  optional($children->color)->secondary_color .';'. 'border: 6px solid;' : 'black; border: 1px solid;' }} background-color: {{ optional($children->color)->color }};" for="color-{{ $children->color_id }}"> {{ optional($children->color)->color ? '' :  optional($children->color)->name }} </label>
						</div>  
						@endforeach
					</div>             
			        @error('color_id') 
			        	<span class="error">
			        		<p style="color:red;">{{ $message }}</p>
			        	</span> 
			        @enderror
				</div>
		</div>
	</div>
	<div class="row pt-4">
		<div class="col-12">
			<h6 class="mb-3">
				@lang('Sizes'):
			</h6>
		</div>
		<div class="col-12 col-md-3 col-lg-3 col-xl-12">
			<div class="form-group mx-auto">
				<div class="row px-2">
					@foreach($attributes->children->unique('size_id')->sortBy('size.name') as $children) 
					<div class="col px-2" style="min-width:3rem; max-width: 3rem ;height:3rem">
						<input class="checkbox-size" type="radio" wire:model="size_id" value="{{ $children->size_id }}" name="size" id="size-{{ $children->size_id }}">
						<label class="for-checkbox-size" for="size-{{ $children->size_id }}">{{ optional($children->size)->short_name ?? optional($children->size)->name }}</label>
					</div>  
					@endforeach

				</div>              

		        @error('size_id') 
		        	<span class="error">
		        		<p style="color:red;">{{ $message }}</p>
		        	</span> 
		        @enderror
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 py-4">
			<div class="section divider divider-gray"></div>
		</div>
	</div>
	<div class="row" wire:ignore>
		<div class="col-auto pr-sm-2">
			<div class="quantity shop-quantity">
				<input type="number" onchange="@this.set('amount', this.value)" min="1" max="9999" step="1" value="1" >
			</div>	
		</div>
		<div class="col-sm mt-2 mt-sm-0 pl-sm-0">
			<a  wire:click="add_cart" class="btn btn-dark-primary" href="javascript:void(0)"><i class="uil uil-cart size-20 mr-2"></i>@lang('Add to cart')</a>
		</div>
	</div>
    <div class="py-4">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>


</div>