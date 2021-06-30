		<header class="header">
			<div class="header-top bg-primary text-uppercase">
				<div class="container">
					<div class="header-left">
						<div class="header-dropdown">
							<a href="#" class="pl-0"><img src="{{ asset('/porto/assets/images/flags/en.png')}}" alt="England flag">ENG</a>
							<div class="header-menu">
								<ul>
									<li><a href="#"><img src="{{ asset('/porto/assets/images/flags/en.png')}}" alt="England flag">ENG</a></li>
									<li><a href="#"><img src="{{ asset('/porto/assets/images/flags/fr.png')}}" alt="France flag">FRA</a></li>
								</ul>
							</div><!-- End .header-menu -->
						</div><!-- End .header-dropown -->
					</div><!-- End .header-left -->

					<div class="header-right header-dropdowns ml-0 ml-sm-auto">
						<p class="top-message mb-0 mr-lg-5 pr-3 d-none d-sm-block">@lang('Welcome to') {{ appName() }}!</p>
						<div class="header-dropdown dropdown-expanded mr-3">
							<a href="#">Links</a>
							<div class="header-menu">
								<ul>
									<li><a href="{{ route('frontend.shop.index') }}">@lang('Go to products') </a></li>
									<li><a href="about.html">@lang('About')</a></li>
									<li><a href="category.html">@lang('Our stores')</a></li>
									<li><a href="contact.html">@lang('Contact')</a></li>
									<li><a href="#">@lang('Help')</a></li>
									@auth
										<li>
				                            <x-utils.link
				                                :text="__('Logout')"
				                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
				                                <x-slot name="text">
				                                    @lang('Logout')
				                                    <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
				                                </x-slot>
				                            </x-utils.link>
				                        </li>
			                        @endauth
								</ul>
							</div><!-- End .header-menu -->
						</div><!-- End .header-dropown -->

						<span class="separator"></span>

						<div class="social-icons">
							<a href="#" class="social-icon social-instagram icon-instagram" target="_blank"></a>
							<a href="#" class="social-icon social-twitter icon-twitter" target="_blank"></a>
							<a href="#" class="social-icon social-facebook icon-facebook" target="_blank"></a>
						</div><!-- End .social-icons -->
					</div><!-- End .header-right -->
				</div><!-- End .container -->
			</div><!-- End .header-top -->


			<div class="header-middle text-dark">
				<div class="container">
					<div class="header-left col-lg-2 w-auto pl-0">
						<button class="mobile-menu-toggler mr-2" type="button">
							<i class="icon-menu"></i>
						</button>
						<a href="{{ url('/') }}" class="logo">
							<img width="105px"  src="{{ asset('/porto/assets/images/logo22.png')}}" alt="Porto Logo">
						</a>
					</div><!-- End .header-left -->

					<div class="header-right w-lg-max pl-2">
						<div class="header-search header-icon header-search-inline header-search-category w-lg-max mr-lg-4">
							<a href="#" class="search-toggle" role="button"><i class="icon-search-3"></i></a>
							<form action="{{ route('frontend.shop.index') }}" method="get">
								<div class="header-search-wrapper">
									<input type="search" class="form-control" name="searchTermShop" id="searchTermShop" placeholder="@lang('Search')..." required>

									<button class="btn p-0 icon-search-3" type="submit"></button>
								</div><!-- End .header-search-wrapper -->
							</form>
						</div><!-- End .header-search -->

							@auth
	                            @if ($logged_in_user->isAdmin())
		                            <ul class="menu">
		                                <li>
		                                    <a class="pl-4" href="{{ route('admin.dashboard') }}"> <strong> @lang('Administration') </strong></a>
		                                </li>
		                            </ul>
	                            @endif
                            @endauth

						<a href="{{ Auth::check() ? route('frontend.user.account') : route('frontend.auth.login') }}" class="header-icon "><i class="icon-user-2"></i></a>

						<a href="#" class="header-icon"><i class="icon-wishlist-2"></i></a>

						<div class="dropdown cart-dropdown">
							<a href="#" class="dropdown-toggle dropdown-arrow" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
								<i class="icon-shopping-cart"></i>
						        @livewire('frontend.header.header-cart-porto')
							</a>

							<div class="dropdown-menu">
								<div class="dropdownmenu-wrapper">
									<div class="dropdown-cart-header">
										<span>2 Items</span>
										
										<a href="{{ route('frontend.cart.index') }}" class="float-right">@lang('View cart')</a>
									</div><!-- End .dropdown-cart-header -->
									
									<div class="dropdown-cart-products">
										<div class="product">
											<div class="product-details">
												<h4 class="product-title">
													<a href="product.html">Woman Ring</a>
												</h4>
												
												<span class="cart-product-info">
													<span class="cart-product-qty">1</span>
													x $99.00
												</span>
											</div><!-- End .product-details -->
												
											<figure class="product-image-container">
												<a href="product.html" class="product-image">
													<img src="{{ asset('/porto/assets/images/products/cart/product-1.jpg')}}" alt="product" width="80" height="80">
												</a>
												<a href="#" class="btn-remove icon-cancel" title="Remove Product"></a>
											</figure>
										</div><!-- End .product -->
										
										<div class="product">
											<div class="product-details">
												<h4 class="product-title">
													<a href="product.html">Woman Necklace</a>
												</h4>
												
												<span class="cart-product-info">
													<span class="cart-product-qty">1</span>
													x $35.00
												</span>
											</div><!-- End .product-details -->
											
											<figure class="product-image-container">
												<a href="product.html" class="product-image">
													<img src="{{ asset('/porto/assets/images/products/cart/product-2.jpg')}}" alt="product" width="80" height="80">
												</a>
												<a href="#" class="btn-remove icon-cancel" title="Remove Product"></a>
											</figure>
										</div><!-- End .product -->
									</div><!-- End .cart-product -->
									
									<div class="dropdown-cart-total">
										<span>Total</span>
										
										<span class="cart-total-price float-right">$134.00</span>
									</div><!-- End .dropdown-cart-total -->
									
									<div class="dropdown-cart-action">
										<a href="checkout-shipping.html" class="btn btn-dark btn-block">@lang('Checkout')</a>
									</div><!-- End .dropdown-cart-total -->
								</div><!-- End .dropdownmenu-wrapper -->
							</div><!-- End .dropdown-menu -->
						</div><!-- End .dropdown -->
					</div><!-- End .header-right -->
				</div><!-- End .container -->
			</div><!-- End .header-middle -->
		</header><!-- End .header -->
