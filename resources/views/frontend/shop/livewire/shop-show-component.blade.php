			<div class="container">
				<div class="row">
					<div class="col-lg-9 main-content">
						<div class="product-single-container product-single-default">
							<div class="row">
								<div class="col-lg-7 col-md-6 product-single-gallery">
									<div class="product-slider-container">
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

									<div class="product-filters-container">
										<div class="product-single-filter">
											<label>@lang('Colors'):</label>
											<ul class="config-swatch-list">

												@foreach($attributes->children->unique('color_id')->sortBy('color.name') as $children)

													<li>
														<a href="#" style="background-color: {{ optional($children->color)->color }};"></a>
													</li>

												@endforeach

											</ul>
										</div><!-- End .product-single-filter -->

										<div class="product-single-filter">
											<label>@lang('Sizes'):</label>
											<ul class="config-size-list">
						  						@foreach($attributes->children->unique('size_id')->sortBy('size.name') as $children) 
						
												<li>
													<a href="#">{{ optional($children->size)->short_name }}
													</a>
												</li>

												@endforeach
											</ul>
										</div><!-- End .product-single-filter -->
									</div><!-- End .product-filters-container -->

									<hr class="divider">

									<div class="product-action">
										<div class="product-single-qty">
											<input class="horizontal-quantity form-control" type="text">
										</div><!-- End .product-single-qty -->

										<a href="cart.html" class="btn btn-dark add-cart icon-shopping-cart" title="Add to Cart">@lang('Add to cart')</a>
									</div><!-- End .product-action -->

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

										<a href="#" class="add-wishlist" title="Add to Wishlist">@lang('Add to wishlist')</a>
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
									<a class="nav-link active" id="product-tab-size" data-toggle="tab" href="#product-size-content" role="tab" aria-controls="product-size-content" aria-selected="true">Size Guide</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="product-tab-tags" data-toggle="tab" href="#product-tags-content" role="tab" aria-controls="product-tags-content" aria-selected="false">Tags</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="product-tab-reviews" data-toggle="tab" href="#product-reviews-content" role="tab" aria-controls="product-reviews-content" aria-selected="false">Reviews</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
									<div class="product-desc-content">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat.</p>
										<ul>
											<li><i class="fa fa-check-circle"></i>Any Product types that You want - Simple, Configurable</li>
											<li><i class="fa fa-check-circle"></i>Downloadable/Digital Products, Virtual Products</li>
											<li><i class="fa fa-check-circle"></i>Inventory Management with Backordered items</li>
										</ul>
										<p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <br>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
									</div><!-- End .product-desc-content -->
								</div><!-- End .tab-pane -->
		
								<div class="tab-pane fade show active" id="product-size-content" role="tabpanel" aria-labelledby="product-tab-size">
									<div class="product-size-content">
										<div class="row">
											<div class="col-md-4">
												<img src="{{ asset('/porto/assets/images/products/single/body-shape.png')}}" alt="body shape">
											</div><!-- End .col-md-4 -->
		
											<div class="col-md-8">
												<table class="table table-size">
													<thead>
														<tr>
															<th>SIZE</th>
															<th>CHEST (in.)</th>
															<th>WAIST (in.)</th>
															<th>HIPS (in.)</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>XS</td>
															<td>34-36</td>
															<td>27-29</td>
															<td>34.5-36.5</td>
														</tr>
														<tr>
															<td>S</td>
															<td>36-38</td>
															<td>29-31</td>
															<td>36.5-38.5</td>
														</tr>
														<tr>
															<td>M</td>
															<td>38-40</td>
															<td>31-33</td>
															<td>38.5-40.5</td>
														</tr>
														<tr>
															<td>L</td>
															<td>40-42</td>
															<td>33-36</td>
															<td>40.5-43.5</td>
														</tr>
														<tr>
															<td>XL</td>
															<td>42-45</td>
															<td>36-40</td>
															<td>43.5-47.5</td>
														</tr>
														<tr>
															<td>XLL</td>
															<td>45-48</td>
															<td>40-44</td>
															<td>47.5-51.5</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div><!-- End .row -->
									</div><!-- End .product-size-content -->
								</div><!-- End .tab-pane -->
		
								<div class="tab-pane fade" id="product-tags-content" role="tabpanel" aria-labelledby="product-tab-tags">
									<div class="product-tags-content">
										<form action="#">
											<h4>Add Your Tags:</h4>
											<div class="form-group">
												<input type="text" class="form-control form-control-sm" required>
												<input type="submit" class="btn btn-primary" value="Add Tags">
											</div><!-- End .form-group -->
										</form>
										<p class="note">Use spaces to separate tags. Use single quotes (') for phrases.</p>
									</div><!-- End .product-tags-content -->
								</div><!-- End .tab-pane -->
		
								<div class="tab-pane fade" id="product-reviews-content" role="tabpanel" aria-labelledby="product-tab-reviews">
									<div class="product-reviews-content">
										<div class="row">
											<div class="col-xl-7">
												<h2 class="reviews-title">3 reviews for Product Long Name</h2>

												<ol class="comment-list">
													<li class="comment-container">
														<div class="comment-avatar">
															<img src="{{ asset('/porto/assets/images/avatar/avatar1.jpg')}}" width="65" height="65" alt="avatar"/>
														</div><!-- End .comment-avatar-->

														<div class="comment-box">
															<div class="ratings-container">
																<div class="product-ratings">
																	<span class="ratings" style="width:80%"></span><!-- End .ratings -->
																</div><!-- End .product-ratings -->
															</div><!-- End .ratings-container -->

															<div class="comment-info mb-1">
																<h4 class="avatar-name">John Doe</h4> - <span class="comment-date">Novemeber 15, 2019</span>
															</div><!-- End .comment-info -->

															<div class="comment-text">
																<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>
															</div><!-- End .comment-text -->
														</div><!-- End .comment-box -->
													</li><!-- comment-container -->

													<li class="comment-container">
														<div class="comment-avatar">
															<img src="{{ asset('/porto/assets/images/avatar/avatar2.jpg')}}" width="65" height="65" alt="avatar"/>
														</div><!-- End .comment-avatar-->

														<div class="comment-box">
															<div class="ratings-container">
																<div class="product-ratings">
																	<span class="ratings" style="width:80%"></span><!-- End .ratings -->
																</div><!-- End .product-ratings -->
															</div><!-- End .ratings-container -->

															<div class="comment-info mb-1">
																<h4 class="avatar-name">John Doe</h4> - <span class="comment-date">Novemeber 15, 2019</span>
															</div><!-- End .comment-info -->

															<div class="comment-text">
																<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>
															</div><!-- End .comment-text -->
														</div><!-- End .comment-box -->
													</li><!-- comment-container -->
														
													<li class="comment-container">
														<div class="comment-avatar">
															<img src="{{ asset('/porto/assets/images/avatar/avatar3.jpg')}}" width="65" height="65" alt="avatar"/>
														</div><!-- End .comment-avatar-->

														<div class="comment-box">
															<div class="ratings-container">
																<div class="product-ratings">
																	<span class="ratings" style="width:80%"></span><!-- End .ratings -->
																</div><!-- End .product-ratings -->
															</div><!-- End .ratings-container -->

															<div class="comment-info mb-1">
																<h4 class="avatar-name">John Doe</h4> - <span class="comment-date">Novemeber 15, 2019</span>
															</div><!-- End .comment-info -->

															<div class="comment-text">
																<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>
															</div><!-- End .comment-text -->
														</div><!-- End .comment-box -->
													</li><!-- comment-container -->
												</ol><!-- End .comment-list -->
											</div>

											<div class="col-xl-5">
												<div class="add-product-review">
													<form action="#" class="comment-form m-0">
														<h3 class="review-title">Add a Review</h3>

														<div class="rating-form">
															<label for="rating">Your rating</label>
															<span class="rating-stars">
																<a class="star-1" href="#">1</a>
																<a class="star-2" href="#">2</a>
																<a class="star-3" href="#">3</a>
																<a class="star-4" href="#">4</a>
																<a class="star-5" href="#">5</a>
															</span>

															<select name="rating" id="rating" required="" style="display: none;">
																<option value="">Rate…</option>
																<option value="5">Perfect</option>
																<option value="4">Good</option>
																<option value="3">Average</option>
																<option value="2">Not that bad</option>
																<option value="1">Very poor</option>
															</select>
														</div>

														<div class="form-group">
															<label>Your Review</label>
															<textarea cols="5" rows="6" class="form-control form-control-sm"></textarea>
														</div><!-- End .form-group -->


														<div class="row">
															<div class="col-md-6 col-xl-12">
																<div class="form-group">
																	<label>Your Name</label>
																	<input type="text" class="form-control form-control-sm" required>
																</div><!-- End .form-group -->
															</div>

															<div class="col-md-6 col-xl-12">
																<div class="form-group">
																	<label>Your E-mail</label>
																	<input type="text" class="form-control form-control-sm" required>
																</div><!-- End .form-group -->
															</div>
														</div>

														<input type="submit" class="btn btn-dark ls-n-15" value="Submit">
													</form>
												</div><!-- End .add-product-review -->
											</div>
										</div>
									</div><!-- End .product-reviews-content -->
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
													<a href="{{ route('frontend.shop.show', $featured->id) }}">
														<img src="{{ asset('/storage/' . $featured->file_name) }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';">
													</a>
												</figure>
												<div class="product-details">
													<h2 class="product-title">
														<a href="{{ route('frontend.shop.show', $featured->id) }}">{{ $featured->name }}</a>
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
								<a href="{{ route('frontend.shop.show', $featured_product->id) }}">
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
										<a href="category.html" class="product-category">{{ optional($featured_product->line)->name }}</a>
									</div>
								</div>
								<h3 class="product-title">
									<a href="{{ route('frontend.shop.show', $featured_product->id) }}">{{ $featured_product->name }}</a>
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
