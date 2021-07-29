		<div>
			<div class="category-banner-container bg-gray">
				<div class="category-banner banner text-uppercase" style="background: no-repeat 60%/cover url('{{ asset('/porto/assets/images/banners/cocina.jpg')}}');">
					<div class="container position-relative">
						<div class="row">
							<div class="pl-lg-5 pb-5 pb-md-0 col-md-5 col-xl-4 col-lg-4 offset-1">
								{{-- <h3 class="ml-lg-5 mb-2 ls-10">Electronic<br>Deals</h3> --}}
								{{-- <a href="#" class="ml-lg-5 btn btn-dark btn-black ls-10"></a> --}}
							</div>
							<div class="pl-lg-5 col-md-4 offset-md-0 offset-1 pt-4">
								<div class="coupon-sale-content">
									<br><br><br><br><br><br><br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="container">
				<nav aria-label="breadcrumb" class="breadcrumb-nav">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index-2.html"><i class="icon-home"></i></a></li>
						<li class="breadcrumb-item"><a href="#">Men</a></li>
						<li class="breadcrumb-item active" aria-current="page">Accessories</li>
					</ol>
				</nav>

				<div class="row">
					<div class="col-lg-9">
						<nav class="toolbox">
							<div class="toolbox-left">
								<div class="toolbox-item toolbox-sort">
									<label>@lang('Sort by'):</label>

									<div class="select-custom">
										<select name="orderby" class="form-control" wire:model='sorting'>
											<option value="menu_order" selected="selected">@lang('Default sorting')</option>
											<option value="newness">@lang('Sort by newness')</option>
											<option value="price">@lang('Sort by price'): @lang('low to high')</option>
											<option value="price-desc">@lang('Sort by price'): @lang('high to low')</option>
										</select>
									</div><!-- End .select-custom -->

									&nbsp;&nbsp;&nbsp;
									<div class="header-search header-icon header-search-inline header-search-category w-lg-max mr-lg-4">
										<a href="#" class="search-toggle" role="button"><i class="icon-search-3"></i></a>
										{{-- <form action="#" method="get"> --}}
											<div class="header-search-wrapper">
												<input type="search" class="form-control" name="search" id="search" wire:model.debounce.350ms="searchTermShop" placeholder="@lang('Search')..." required>

												<button class="btn p-0 icon-search-3" disabled></button>

											    @if($searchTermShop !== '')
											    <div class="input-group">
											      <button type="button" wire:click="clear" class="close" aria-label="Close">
											        <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
											      </button>

											    </div>
											    @endif

											</div><!-- End .header-search-wrapper -->
										{{-- </form> --}}
									</div><!-- End .header-search -->

								</div><!-- End .toolbox-item -->
							</div><!-- End .toolbox-left -->

							<div class="toolbox-right">
								<div class="toolbox-item toolbox-show">
									<label>@lang('Show'):</label>

									<div class="select-custom">
										<select wire:model="perPage" class="form-control">
											<option value="4">4</option>
											<option value="8">8</option>
											<option value="12">12</option>
											<option value="24">24</option>
											<option value="36">36</option>
										</select>
									</div><!-- End .select-custom -->
								</div><!-- End .toolbox-item -->

								<div class="toolbox-item layout-modes">
									<a href="#" class="layout-btn btn-grid active" title="Grid">
										<i class="icon-mode-grid"></i>
									</a>

									<a href="#" class="layout-btn btn-list" title="List">
										<i class="icon-mode-list"></i>
									</a>
								</div><!-- End .layout-modes -->
							</div><!-- End .toolbox-right -->
						</nav>

						@if($color || $size || $line)
							<div class="filter-price-action d-flex align-items-center 	justify-content-between flex-wrap">
								<button class="btn btn-danger" wire:click="clearFilters">@lang('Clear filters')</button>
							</div>
						@endif

						<div class="row">
							@foreach($products as $product)
							<div class="col-6 col-sm-4 col-md-3">
								<div class="product-default inner-quickview inner-icon">

								  	@if($product->file_name)
									<figure>
								    	<a href="{{ route('frontend.shop.show', $product->slug) }}">
								    		<div class="readme-link__figure">
										    	<img src="{{ asset('/storage/' . $product->file_name) }}"  alt="{{ $product->name }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';" >
										    </div>
									    </a>

										<div class="label-group">
											@if($product->created_at->gt(\Carbon\Carbon::now()->subMonth()))
												<div class="product-label label-hot">
													@lang('New')
												</div>
											@endif
											@if($product->discount > 0)
												<div class="product-label label-sale">-{{ $product->discount }}%</div>
											@endif
										</div>

									<div class="btn-icon-group">
										<button class="btn-icon btn-add-cart" data-toggle="modal" data-target="#addCartModal"><i class="icon-shopping-cart"></i></button>
									</div>

									</figure>
									@else
									<figure>
								    	<a href="{{ route('frontend.shop.show', $product->slug) }}">
								    		<div class="readme-link__figure">
									    		<img src="{{ asset('/porto/assets/images/not0.png')}}" class="readme-link__figure" alt="{{ $product->name }}">
									    	</div>
									    </a>

										<div class="label-group">
											@if($product->created_at > \Carbon\Carbon::now()->subMonth())
												<div class="product-label label-hot">
													@lang('New')
												</div>
											@endif
											@if($product->discount > 0)
												<div class="product-label label-sale">-{{ $product->discount }}%</div>
											@endif
										</div>
									<div class="btn-icon-group">
										<button class="btn-icon btn-add-cart" data-toggle="modal" data-target="#addCartModal"><i class="icon-shopping-cart"></i></button>
									</div>

									</figure>
								    @endif

									<div class="product-details">
										<div class="category-wrap">
											<div class="category-list">
												<a href="#" class="product-category">{{ optional($product->line)->name }}</a>
											</div>
											<a href="#" class="btn-icon-wish"><i class="icon-heart"></i></a>
										</div>
										<h2 class="product-title">
											<a href="{{ route('frontend.shop.show', $product->slug) }}">{{ $product->name }}</a>
										</h2>
										<div class="ratings-container">
											<div class="product-ratings">
												<span class="ratings" style="width:100%"></span><!-- End .ratings -->
												<span class="tooltiptext tooltip-top"></span>
											</div><!-- End .product-ratings -->
										</div><!-- End .product-container -->
										<div class="price-box">
											<span class="old-price">$90.00</span>
											<span class="product-price">${{ $product->price }}</span>
										</div><!-- End .price-box -->
									</div><!-- End .product-details -->
								</div>
							</div><!-- End .col-md-3 -->
							@endforeach
						</div><!-- End .row -->

						<nav class="toolbox toolbox-pagination">
							<div class="toolbox-item toolbox-show">
								<label>@lang('Show'):</label>

								<div class="select-custom">
									<select wire:model="perPage" class="form-control">
										<option value="4">4</option>
										<option value="8">8</option>
										<option value="12">12</option>
										<option value="24">24</option>
										<option value="36">36</option>
									</select>
								</div><!-- End .select-custom -->
							</div><!-- End .toolbox-item -->


						    @if($products->count())
								<ul class="pagination toolbox-item">
									<li class="page-item">
							          {{ $products->links() }}
									</a></li>
								</ul>

						        <div class="col-sm-3 text-muted text-right">
						        	Mostrando {{ $products->firstItem() }} - {{ $products->lastItem() }} de {{ $products->total() }} resultados
						        </div>
						    @else
							    @lang('No search results') 
						      	@if($searchTermShop)
						        	"{{ $searchTermShop }}" 
						      	@endif

						      	@if($page > 1)
						        	{{ __('in the page').' '.$page }}
						      	@endif
						    @endif


						</nav>
					</div><!-- End. col-lg-9 -->

					<div class="sidebar-overlay"></div>
					<div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>
					<aside class="sidebar-shop col-lg-3 order-lg-first mobile-sidebar">
						<div class="sidebar-wrapper">
							<div class="widget">
								<h3 class="widget-title">
									<a data-toggle="collapse" href="#widget-body-2" role="button" aria-expanded="true" aria-controls="widget-body-2">@lang('Line')</a>
								</h3>

								{{-- @json($color) --}}
								<div class="collapse show" id="widget-body-2">
									<div class="widget-body">
					                    <livewire:frontend.attributes.line-change/>

					                @if($line)
									<div class="filter-price-action d-flex align-items-center 	justify-content-between flex-wrap">
										<button class="btn btn-danger" wire:click="clearFilterLine">
											@lang('Clear filter')
										</button>
									</div>
									<br>
									@endif
										<ul class="cat-list">
											@foreach($lines as $line)
												<li>
													<a wire:click="lineID({{ $line->id }})" style="cursor: pointer;">
														{{ $line->name }}
													</a>
												</li>
											@endforeach
										</ul>
									</div><!-- End .widget-body -->
								</div><!-- End .collapse -->
							</div><!-- End .widget -->


							<div class="widget">
								<h3 class="widget-title">
									<a data-toggle="collapse" href="#widget-body-3" role="button" aria-expanded="true" aria-controls="widget-body-3">@lang('Price')</a>
								</h3>

								<div class="collapse show" id="widget-body-3">
									<div class="widget-body">
										<form action="#">
											<div class="filter-price-action d-flex align-items-center justify-content-between flex-wrap">

												<input type="number" name="firstValue" class="form-control form-control-sm">
												<input type="number" name="secondValue" class="form-control form-control-sm">

												<button type="submit" class="btn btn-primary">@lang('Filter')</button>

												<div class="filter-price-text">
													@lang('Price'):
													<span id="filter-price-range"></span>
												</div><!-- End .filter-price-text -->
											</div><!-- End .filter-price-action -->
										</form>
									</div><!-- End .widget-body -->
								</div><!-- End .collapse -->
							</div><!-- End .widget -->


							<div class="widget">
								<h3 class="widget-title">
									<a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="true" aria-controls="widget-body-4">@lang('Size_')</a>
								</h3>

								<div class="collapse show" id="widget-body-4">
									<div class="widget-body">
					                    <livewire:frontend.attributes.size-change/>

						                @if($size)
										<div class="filter-price-action d-flex align-items-center 	justify-content-between flex-wrap">
											<button class="btn btn-danger" wire:click="clearFilterSize">
												@lang('Clear filter')
											</button>
										</div>
										<br>
										@endif

										<ul class="cat-list">
											@foreach($sizes as $size)
												<li>
													<a wire:click="sizeID({{ $line->id }})" style="cursor: pointer;">
														{{ $size->name }}
													</a>
												</li>
											@endforeach
										</ul>
									</div><!-- End .widget-body -->
								</div><!-- End .collapse -->
							</div><!-- End .widget -->


							<div class="widget">
								<h3 class="widget-title">
									<a data-toggle="collapse" href="#widget-body-6" role="button" aria-expanded="true" aria-controls="widget-body-6">Color</a>
								</h3>

								<div class="collapse show" id="widget-body-6">
									<div class="widget-body">
					                    <livewire:frontend.attributes.color-change/>
						                @if($color)
										<div class="filter-price-action d-flex align-items-center 	justify-content-between flex-wrap">
											<button class="btn btn-danger" wire:click="clearFilterColor">
												@lang('Clear filter')
											</button>
										</div>
										<br>
										@endif

										<ul class="config-swatch-list">
											@foreach($colors as $color)
												<li 
													wire:click="colorID({{ $color->id }})"
													{{-- class="active" --}}
												>
													<a style="background-color: {{ $color->color }};" style="cursor: pointer;">
													</a>
													<span>{{ $color->name }}</span>
												</li>
											@endforeach
										</ul>
									</div><!-- End .widget-body -->
								</div><!-- End .collapse -->
							</div><!-- End .widget -->
						</div><!-- End .sidebar-wrapper -->
					</aside><!-- End .col-lg-3 -->
				</div><!-- End .row -->
			</div><!-- End .container -->

			<div class="mb-3"></div><!-- margin -->

		</div>