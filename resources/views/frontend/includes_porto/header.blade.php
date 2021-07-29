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
						        @livewire('frontend.header.header-cart-porto-drop')
						</div><!-- End .dropdown -->
					</div><!-- End .header-right -->
				</div><!-- End .container -->
			</div><!-- End .header-middle -->
		</header><!-- End .header -->
