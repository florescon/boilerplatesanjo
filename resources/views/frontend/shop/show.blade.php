@extends('frontend.layouts.app_ga')

@section('title', __('Show shop'))

@section('content')
	<div class="section over-hide padding-top-120 padding-top-mob-nav pb-5 section-background-20 background-img-top">	
		<div class="section-1400 pt-xl-4">
			<div class="container-fluid padding-top-bottom-80">
				<div class="row">
					<div class="col-lg">
						<h2 class="display-8 mb-3">
							{{ $shop->name }}
						</h2>
						<p class="lead mb-0 title-text-left-line-small">
							{{ $shop->code }} 
						</p>
					</div>
					<div class="col-lg-auto align-self-center mt-4 mt-lg-0">
						@if (config('boilerplate.frontend_breadcrumbs'))
						    @include('frontend.includes_ga.partials.breadcrumbs')
						@endif
					</div>
				</div>
			</div>
		</div>

	<livewire:frontend.shop.shop-show-component :product="$shop"/>

	</div>


		<!-- section
		================================================== -->

		<div class="section over-hide padding-bottom-120 pt-5 section-background-24" id="related-products">
			<div class="section-1400">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-12 text-center mb-5">
							<h2 class="mb-0">
								@lang('Related products')<span class="font-weight-600"><span class="color-primary">.</span></span>
							</h2>
						</div>
						@foreach($related_products as $related_product)
						<div class="col-sm-6 col-lg-3 mt-4 mt-lg-0">
							<div class="section shop-wrap-3 img-wrap border-4">

								<img class="border-4" src="{{ asset('/storage/' . $related_product->file_name) }}" alt="{{ $related_product->name }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';" >

								<div class="shop-wrap-2-left">

									@if($related_product->discount > 0)
										<div class="shop-wrap-2-left-circle bg-orange color-white size-13 font-weight-600">
											-{{ $related_product->discount }}%
										</div>
									@endif

									@if($related_product->created_at->gt(\Carbon\Carbon::now()->subMonth()))
										<div class="mt-2 shop-wrap-2-left-circle bg-blue color-white size-13 font-weight-600">
											@lang('New')
										</div>
									@endif
								</div>
								<div class="shop-wrap-2-right">
									@if($related_product->file_name)
										<a href="{{ asset('/storage/' . $related_product->file_name) }}" class="shop-wrap-2-right-circle" data-fancybox=""><i class="uil uil-search size-16"></i></a>
									@endif
									<a href="{{ route('frontend.shop.show', $related_product->slug) }}" class="shop-wrap-2-right-circle animsition-link mt-2"><i class="uil uil-plus size-16"></i></a>
								</div>
								<div class="shop-wrap-2-text">
									@if($related_product->children->unique('size_id')->count())
									<div class="shop-wrap-2-size">
										<p class="mb-0 color-white text-uppercase size-13 font-weight-600">
											@foreach($related_product->children->unique('size_id')->sortBy('size.name')->slice(0, 3) as $children) 
												<span class="mx-1">{{ optional($children->size)->short_name }}</span>
											@endforeach
											@if($related_product->children->unique('size_id')->count() > 3)
												<span class="mx-1">...</span>
											@endif
										</p>
									</div>
									@endif
									<div class="row">
										<div class="col">
											<h6 class="mb-2">
												<a href="{{ route('frontend.shop.show', $related_product->slug) }}" class="link-heading animsition-link">{{ $related_product->name }}</a>
											</h6>
											<p class="lead mb-1 font-weight-600">
												<span class="text-line-through mr-1">$167</span> <span class="color-primary">$117</span>
											</p>
											<p class="mb-0 color-yellow">
												@for ($i = 0; $i < rand(4,5); $i++)
													<i class="uil uil-star"></i>
												@endfor
												@if($i === 4)
													<i class="uil uil-star color-gray"></i>
												@endif
											</p>
										</div>
										<div class="col-auto align-self-center">
											<p class="mb-0">
												<a href="{{ route('frontend.shop.show', $related_product->slug) }}" class="link-heading animsition-link">
													<i class="uil uil-cart size-28"></i>
													<span class="btn-small-icon bg-dark-blue color-white">+</span>
												</a>
											</p>
										</div>
									</div>
								</div>
							</div>	
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>

@endsection