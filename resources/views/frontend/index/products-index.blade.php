<div class="products-slider owl-carousel owl-theme dots-top m-b-1 pb-1">
	@foreach($featured_products as $featured)
	<div class="product-default inner-quickview inner-icon">
		<figure>
			<a href="{{ route('frontend.shop.show', $featured->slug) }}">
				<img src="{{ asset('/storage/' . $featured->file_name) }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';">
			</a>
			<div class="label-group">
				@if($featured->created_at->gt(\Carbon\Carbon::now()->subMonth()))
					<div class="product-label label-hot">
						@lang('New')
					</div>
				@endif
			</div>

				<div class="btn-icon-group">
				{{-- <button class="btn-icon btn-add-cart" data-toggle="modal" data-target="#addCartModal"><i class="icon-shopping-cart"></i></button> --}}
			</div>
		</figure>
		<div class="product-details">
			<div class="category-wrap">
				<div class="category-list">
					<a href="#" class="product-category">{{ optional($featured->line)->name }}</a>
				</div>
				<a href="#" class="btn-icon-wish"><i class="icon-heart"></i></a>
			</div>
			<h2 class="product-title">
				<a href="{{ route('frontend.shop.show', $featured->slug) }}">{{ $featured->name }}</a>
			</h2>
			<div class="ratings-container">
				<div class="product-ratings">
					<span class="ratings" style="width:100%"></span><!-- End .ratings -->
					<span class="tooltiptext tooltip-top"></span>
				</div><!-- End .product-ratings -->
			</div><!-- End .product-container -->
			<div class="price-box">
				<span class="product-price">${{ $featured->price }}</span>
			</div><!-- End .price-box -->
		</div><!-- End .product-details -->
	</div>
	@endforeach
</div><!-- End .featured-proucts -->
