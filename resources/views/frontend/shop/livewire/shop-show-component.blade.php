
			<div class="section-1400">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-8 col-md-6 col-lg-5 col-xl-4 pr-xl-5">
							<div class="section swiper-product-page pb-1 over-hide">
								<div class="swiper-wrapper">
									<div class="swiper-slide">
										<a href="{{ asset('/storage/' . $origPhoto) }}" data-fancybox="gallery">
											<div class="section border-4 over-hide img-wrap">
												<img class="border-4" src="{{ asset('/storage/' . $origPhoto) }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';" alt="">
											</div>
										</a>
									</div>

									@foreach($model->pictures as $pict)
									<div class="swiper-slide">
										<a href="{{ asset('/storage/' . $pict->picture) }}" data-fancybox="gallery">
											<div class="section border-4 over-hide img-wrap">
												<img class="border-4" src="{{ asset('/storage/' . $pict->picture) }}" alt="">
											</div>
										</a>
									</div>
									@endforeach
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-8">
									<div class="section swiper-product-page-thumbs over-hide">
										<div class="swiper-wrapper">
											<div class="swiper-slide">
												<div class="section border-4 over-hide img-wrap product-thumbs">
													<img src="{{ asset('/storage/' . $origPhoto) }}" alt="">
												</div>
											</div>
											@foreach($model->pictures as $pict)
											<div class="swiper-slide">
												<div class="section border-4 over-hide img-wrap product-thumbs">
													<img src="{{ asset('/storage/' . $pict->picture) }}" alt="">
												</div>
											</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-8 col-md-6 col-lg-7 col-xl-5 pr-xl-5 mt-5 mt-md-0">
							<div class="row">
								<div class="col">
									<h3 class="mb-0 font-weight-700">
										<span class="text-line-through mr-1 color-gray size-22">${{ $model->price*((100+15))/100 }}</span> <span>${{ $model->price }}</span>
									</h3>
								</div>
								<div class="col-auto align-self-center">
									<p class="mb-0 size-18 color-yellow">
										@for ($i = 0; $i < rand(4,5); $i++)
											<i class="uil uil-star"></i>
										@endfor
										@if($i === 4)
											<i class="uil uil-star color-gray"></i>
										@endif
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-12 pt-2 pb-3">
									<div class="section divider divider-gray"></div>
								</div>
							</div>
							@if($model->description)
							<div class="row">
								<div class="col-12">
									<p class="mb-0">
										{{ $model->description }}
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-12 pt-3">
									<div class="section divider divider-gray"></div>
								</div>
							</div>
							@endif

								<livewire:frontend.shop.shop-parameters-component :product="$product_id"/>

							<div class="row">
								<div class="col-12 py-4">
									<div class="section divider divider-gray"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p class="mb-0 text-center-v">
										<i class="uil uil-check size-22 color-primary mr-2"></i> @lang('Dynamic Color Options')
									</p>
									<div class="w-100"></div>
									<p class="mb-0 text-center-v">
										<i class="uil uil-check size-22 color-primary mr-2"></i> @lang('Dynamic Size Options')
									</p>
									<div class="w-100"></div>
									<p class="mb-0 text-center-v">
										<i class="uil uil-check size-22 color-primary mr-2"></i> @lang('Create Order and/or Sale')
									</p>
								</div>
							</div>
						</div>
						<div class="col-xl-3 mt-5 mt-xl-0">
							<div class="row justify-content-center">
								<div class="col-sm-6 col-lg-4 col-xl-12">
									<div class="section">
										<h6 class="mb-3">
											@lang('Search product')
										</h6>
										<div class="row">
											<div class="col pr-0">
                                    		<form action="{{ route('frontend.shop.index') }}" method="get">
													<div class="form-group just-line-light">
														<input type="text" name="searchTermShop" id="searchTermShop" class="form-style" placeholder="{{ __('Search') }}..." autocomplete="off">
													</div>
												</form>
											</div>
											<div class="col-auto">
												<button type="button" class="btn btn-primary btn-44"><i class="uil uil-message size-20"></i></button>
											</div>
										</div>
										<p class="mb-0 size-13 color-secondary mt-1 text-left">* @lang('Search another product').</p>
									</div>
								</div>
								<div class="col-auto mt-5 pt-3 parallax-fade-hero-short">
									<a href="#related-products" class="text-decoration-none" data-gal='m_PageScroll2id'>
										<p class="font-weight-700 mb-0 size-18 color-primary">
											@lang('Go to related products')
											<i class="uil uil-arrow-down size-20 ml-2"></i>
										</p>
									</a>
								</div>
								<div class="col-sm-6 col-lg-4 col-xl-12 mt-5 mt-sm-0">
									<div class="section mt-xl-5">
										<h6 class="mb-3">
											@lang('Another products')
										</h6>
										<div class="section pl-3">
											@foreach($featured_products as $featured_product)
											{{-- @foreach($featured_products->split($featured_products->count()/3) as $featured_product) --}}

											<p class="mb-2 size-16">
												<a class="link link-normal font-weight-500" href="{{ route('frontend.shop.show', $featured_product->slug) }}">{{ $featured_product->name }}</a><br>
												<span class="color-yellow size-13">
													@for ($i = 0; $i < rand(4,5); $i++)
														<i class="uil uil-star"></i>
													@endfor
													@if($i === 4)
														<i class="uil uil-star color-gray"></i>
													@endif
												</span>
											</p>
											@endforeach
										</div>
									</div>
								</div>
								<div class="col-md-8 col-lg-4 col-xl-12 mt-5 mt-lg-0">
									<div class="section mt-xl-5">
										<h6 class="mb-3">
											Tag cloud
										</h6>
										<a href="#" class="tag hot mb-1">man</a>
										<a href="#" class="tag info mb-1">woman</a>
										<a href="#" class="tag popular mb-1">tops</a>
										<a href="#" class="tag sale mb-1">jeans</a>
										<a href="#" class="tag new mb-1">dresses</a>
										<a href="#" class="tag popular mb-1">jackets</a>
										<a href="#" class="tag hot mb-1">man</a>
										<a href="#" class="tag info mb-1">woman</a>
										<a href="#" class="tag popular mb-1">tops</a>
										<a href="#" class="tag sale mb-1">jeans</a>
										<a href="#" class="tag new mb-1">dresses</a>
										<a href="#" class="tag popular mb-1">jackets</a>
										<a href="#" class="tag hot mb-1">man</a>
										<a href="#" class="tag info mb-1">woman</a>
										<a href="#" class="tag popular mb-1">tops</a>
										<a href="#" class="tag sale mb-1">jeans</a>
										<a href="#" class="tag new mb-1">dresses</a>
										<a href="#" class="tag popular mb-1">jackets</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-12 mt-5">
							<div class="row">
									<div class="col-sm">
										<div class="accordion accordion-shadow" id="accordionExample">
											<div class="card">
												<div class="card-header" id="headingOne-1">
													<div class="btn-accordion collapsed" role="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
														<i class="uil uil-microphone size-20 mr-2 pr-1"></i>@lang('Description')
													</div>
												</div>
												<div id="collapseOne" class="collapse show" aria-labelledby="headingOne-1" data-parent="#accordionExample">
													<div class="card-body">
														{!! $model->advanced->description ?? '' !!}
													</div>
												</div>
											</div>
										</div>
									</div>          
									<div class="col-sm">
										<div class="accordion accordion-shadow" id="accordionExample-2">
											<div class="card">
												<div class="card-header" id="headingTwo-2">
													<div class="btn-accordion collapsed" role="button" data-toggle="collapse" data-target="#collapseTwo-2" aria-expanded="true" aria-controls="collapseTwo-2">
														<i class="uil uil-exclamation-triangle size-20 mr-2 pr-1"></i>Informacion tecnica
													</div>
												</div>
												<div id="collapseTwo-2" class="collapse show" aria-labelledby="headingTwo-2" data-parent="#accordionExample-2">
													<div class="card-body">
														{!! $model->advanced->information ?? '' !!}
													</div>
												</div>
											</div>
										</div>
									</div>          
									<div class="col-sm">
										<div class="accordion accordion-shadow" id="accordionExample-3">
											<div class="card">
												<div class="card-header" id="headingThree-3">
													<div class="btn-accordion collapsed" role="button" data-toggle="collapse" data-target="#collapseThree-3" aria-expanded="true" aria-controls="collapseThree-3">
														<i class="uil uil-restaurant size-20 mr-2 pr-1"></i>Documentacion
													</div>
												</div>
												<div id="collapseThree-3" class="collapse show" aria-labelledby="headingThree-3" data-parent="#accordionExample-3">
													<div class="card-body">
						                                <li>
						                                    <a href="{{ route('frontend.shop.datasheet', $model->slug) }}"
						                                       target="_blank">@lang('Datasheet')</a>
						                                </li>
													</div>
												</div>
											</div>
										</div>
									</div>          

							</div>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-12 mt-5">
							<div class="row">
								<div class="col-sm-6 col-lg-3">
									<div class="section p-3 p-lg-4 border-4 bg-white landing-shadow-4">
										<div class="row">
											<div class="col-auto">
												<p class="mb-0 align-self-center">
													<i class="uil uil-archive-alt size-28 color-dark-blue"></i>
												</p>
											</div>
											<div class="col align-self-center">
												<p class="mb-0">
													@lang('Free shipping on orders')
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-lg-3 mt-4 mt-sm-0">
									<div class="section p-3 p-lg-4 border-4 bg-white landing-shadow-4">
										<div class="row">
											<div class="col-auto">
												<p class="mb-0 align-self-center">
													<i class="uil uil-truck size-28 color-dark-blue"></i>
												</p>
											</div>
											<div class="col align-self-center">
												<p class="mb-0">
													@lang('Shipping on orders 3-5 day delivery')
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-lg-3 mt-4 mt-lg-0">
									<div class="section p-3 p-lg-4 border-4 bg-white landing-shadow-4">
										<div class="row">
											<div class="col-auto">
												<p class="mb-0 align-self-center">
													<i class="uil uil-redo size-28 color-dark-blue"></i>
												</p>
											</div>
											<div class="col align-self-center">
												<p class="mb-0">
													@lang('30-Day from order return policy')
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-lg-3 mt-4 mt-lg-0">
									<div class="section p-3 p-lg-4 border-4 bg-white landing-shadow-4">
										<div class="row">
											<div class="col-auto">
												<p class="mb-0 align-self-center">
													<i class="uil uil-shield-check size-28 color-dark-blue"></i>
												</p>
											</div>
											<div class="col align-self-center">
												<p class="mb-0">
													@lang('100% safe & secure checkout')
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
