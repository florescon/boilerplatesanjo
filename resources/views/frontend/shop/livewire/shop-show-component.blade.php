			<div class="container">
				<div class="row">
					<div class="col-lg-9 main-content">
						<div class="product-single-container product-single-default">
							<div class="row">
								<div class="col-lg-7 col-md-6 product-single-gallery">
									<div class="product-slider-container" wire:ignore>
										<div class="product-single-carousel owl-carousel owl-theme">
											<div class="product-item">
												<img class="product-single-image" src="{{ asset('/storage/' . $origPhoto) }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';" data-zoom-image="{{ asset('/storage/' . $origPhoto) }}"/>
											</div>

											@foreach($model->pictures as $picture)
											<div class="product-item">
												<img class="product-single-image" src="{{ asset('/storage/' . $picture->picture) }}" data-zoom-image="{{ asset('/storage/' . $picture->picture) }}"/>
											</div>
											@endforeach
										</div>
										<!-- End .product-single-carousel -->
										<span class="prod-full-screen">
											<i class="icon-plus"></i>
										</span>
									</div>
									@if($model->pictures->count())
										<div class="prod-thumbnail owl-dots" id='carousel-custom-dots'>
											<div class="owl-dot">
												<img src="{{ asset('/storage/' . $origPhoto) }}" onerror="this.style.display='none'" />
											</div>
											@foreach($model->pictures as $picture)
											<div class="owl-dot">
												<img src="{{ asset('/storage/' . $picture->picture) }}"/>
											</div>
											@endforeach
										</div>
									@endif
								</div><!-- End .product-single-gallery -->

								<div class="col-lg-5 col-md-6 product-single-details">
									<h1 class="product-title">{{ $model->name }}</h1>

									<div class="ratings-container">
										<div class="product-ratings">
											<span class="ratings" style="width:60%"></span><!-- End .ratings -->
										</div><!-- End .product-ratings -->

										<a href="#" class="rating-link">( 6 Reviews )</a>
									</div><!-- End .product-container -->

									<hr class="short-divider">

									<div class="price-box">
										{{-- <span class="old-price">${{ $model->price }}</span> --}}
										<span class="product-price">${{ $model->price }}</span>
									</div><!-- End .price-box -->

									<div class="product-desc">
										<p>{{ $model->description }}</p>
									</div><!-- End .product-desc -->

									<livewire:frontend.shop.shop-parameters-component :product="$product_id"/>

									<hr class="divider mb-1">

									<div class="product-single-share">
										<label class="sr-only">@lang('Share'):</label>

										<div class="social-icons mr-2">
											<a href="#" class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
											<a href="#" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
											<a href="#" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank" title="Linkedin"></a>
											<a href="#" class="social-icon social-gplus fab fa-google-plus-g" target="_blank" title="Google +"></a>
											<a href="#" class="social-icon social-mail icon-mail-alt" target="_blank" title="Mail"></a>
										</div><!-- End .social-icons -->

										{{-- @json($getWhislist) --}}

										<a href="#" wire:click="wishlist" class="{{ $getWhislist ? 'add-wishlist-red' : 'add-wishlist' }}" title="Add to Wishlist">@lang('Add to wishlist')</a>
									</div><!-- End .product single-share -->
								</div><!-- End .product-single-details -->
							</div><!-- End .row -->
						</div><!-- End .product-single-container -->

						<div class="product-single-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link" id="product-tab-desc" data-toggle="tab" href="#product-desc-content" role="tab" aria-controls="product-desc-content" aria-selected="false">Description</a>
								</li>
								<li class="nav-item">
									<a class="nav-link active" id="product-tab-size" data-toggle="tab" href="#product-size-content" role="tab" aria-controls="product-size-content" aria-selected="true">Informacion tecnica</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="product-tab-documentation" data-toggle="tab" href="#product-documentation-content" role="tab" aria-controls="product-documentation-content" aria-selected="true">Documentacion</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade show" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
									<div class="product-desc-content">
										{!! $model->advanced->description ?? '' !!}
									</div><!-- End .product-desc-content -->
								</div><!-- End .tab-pane -->
		
								<div class="tab-pane fade active" id="product-size-content" role="tabpanel" aria-labelledby="product-tab-size">
									<div class="product-size-content">
											{!! $model->advanced->information ?? '' !!}
									</div><!-- End .product-size-content -->
								</div><!-- End .tab-pane -->

								<div class="tab-pane fade" id="product-documentation-content" role="tabpanel" aria-labelledby="product-tab-documentation">
									<div class="product-documentation-content">
										<h5>
                                            <li>
                                                <a href="{{ route('frontend.shop.datasheet', $model->slug) }}"
                                                   target="_blank">@lang('Datasheet')</a>
                                            </li>
                                        </h5>
									</div><!-- End .product-documentation-content -->
								</div><!-- End .tab-pane -->
		
							</div><!-- End .tab-content -->
						</div><!-- End .product-single-tabs -->
					</div><!-- End .col-lg-9 -->

					<div class="sidebar-overlay"></div>
					<div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>
					<aside class="sidebar-product col-lg-3 mobile-sidebar">
						<div class="sidebar-wrapper">
							<div class="widget widget-collapse">
								<h3 class="widget-title">
									<a data-toggle="collapse" href="#widget-body-1" role="button" aria-expanded="true" aria-controls="widget-body-1">@lang('Lines')</a>
								</h3>

								<div class="collapse show" id="widget-body-1">
									<div class="widget-body">
										<ul class="cat-list">
											@foreach($lines as $line)
												<li>
													<a href="#">{{ $line->name }}</a>
												</li>
											@endforeach
										</ul>
									</div><!-- End .widget-body -->
								</div><!-- End .collapse -->
							</div><!-- End .widget -->

							<div class="widget widget-banners px-5 pb-5 text-center">
								<div class="banner d-flex flex-column align-items-center">
									<h3 class="badge-sale bg-primary d-flex flex-column align-items-center justify-content-center text-uppercase"><em class="pt-3 ls-0">Scan</em></h3>
									<p>QR del producto.</p>
	                                {!! QrCode::size(100)->generate(Request::url()); !!}
								</div><!-- End .banner -->
							</div><!-- End .widget -->

							<div class="widget widget-featured">
								<h3 class="widget-title">@lang('Featured')</h3>
								
								<div class="widget-body">
									<div class="owl-carousel widget-featured-products">
										@foreach($featured_products->split($featured_products->count()/3) as $featured_product)

										<div class="featured-col">
											@foreach($featured_product as $featured)
											<div class="product-default left-details product-widget">
												<figure>
													<a href="{{ route('frontend.shop.show', $featured->slug) }}">
														<img src="{{ asset('/storage/' . $featured->file_name) }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';">
													</a>
												</figure>
												<div class="product-details">
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
										</div><!-- End .featured-col -->
										@endforeach

									</div><!-- End .widget-featured-slider -->
								</div><!-- End .widget-body -->
							</div><!-- End .widget -->
						</div>
					</aside><!-- End .col-md-3 -->
				</div><!-- End .row -->

				<div class="products-section pt-0">
					<h2 class="section-title">@lang('Related products')</h2>

					<div class="products-slider owl-carousel owl-theme dots-top">
						@foreach($featured_products as $featured_product)
						<div class="product-default inner-quickview inner-icon">
							<figure>
								<a href="{{ route('frontend.shop.show', $featured_product->slug) }}">
									<img src="{{ asset('/storage/' . $featured_product->file_name) }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';">
								</a>
								<div class="label-group">
									<span class="product-label label-sale">-20%</span>
								</div>
								<div class="btn-icon-group">
									<button class="btn-icon btn-add-cart" data-toggle="modal" data-target="#addCartModal"><i class="icon-shopping-cart"></i></button>
								</div>
								{{-- <a href="ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick View</a>  --}}
							</figure>
							<div class="product-details">
								<div class="category-wrap">
									<div class="category-list">
										<a href="#" class="product-category">{{ optional($featured_product->line)->name }}</a>
									</div>
								</div>
								<h3 class="product-title">
									<a href="{{ route('frontend.shop.show', $featured_product->slug) }}">{{ $featured_product->name }}</a>
								</h3>
								<div class="ratings-container">
									<div class="product-ratings">
										<span class="ratings" style="width:100%"></span><!-- End .ratings -->
										<span class="tooltiptext tooltip-top"></span>
									</div><!-- End .product-ratings -->
								</div><!-- End .ratings-container -->
								<div class="price-box">
									<span class="old-price">$59.00</span>
									<span class="product-price">$49.00</span>
								</div><!-- End .price-box -->
							</div><!-- End .product-details -->
						</div>
						@endforeach

					</div><!-- End .products-slider -->
				</div><!-- End .products-section -->

				<div class="mb-lg-4"></div><!-- margin -->
			</div><!-- End .container -->
