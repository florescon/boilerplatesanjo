				<div class="row justify-content-center">

						<div class="col-sm-8 col-md-5 col-lg-3">
							<div class="section ">
								<h5 class="mb-3">
									@lang('Search')
								</h5>
								<div class="section">	
									<input type="search" class="form-style big form-style-with-icon section-shadow-blue" placeholder="{{ __('Search product') }}" name="search" id="search" wire:model.debounce.350ms="searchTermShop" />
										<i class="input-icon big uil uil-search"></i>
								</div>
							</div>


							<div class="section pt-5">

								<div class="section border-4 p-4 bg-transparent section-background-21">
									<div class="row pt-4">
										<div class="col-12 text-center">
										    <div class="col-auto">
										      <div class="input-group mb-2">
										        <div class="input-group-prepend">
										          <div class="input-group-text">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
													  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
													  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
													</svg>										          	
										          </div>
										        </div>
												<select wire:model="perPage" class="form-control">
													<option value="3">3</option>
													<option value="6">6</option>
													<option value="12">12</option>
													<option value="24">24</option>
													<option value="36">36</option>
												</select>
										      </div>
										    </div>
										</div>  							
									</div>

									<div class="row pt-4">
										<div class="col-12 text-center">
										    <div class="col-auto">
										      <div class="input-group mb-2">
										        <div class="input-group-prepend">
										          <div class="input-group-text">
										          	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
  														<path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
													</svg>
										          </div>
										        </div>
												<select name="orderby" class="form-control" wire:model='sorting'>
													<option value="menu_order" selected="selected">@lang('Default sorting')</option>
													<option value="newness">@lang('Sort by newness')</option>
													<option value="price">@lang('Sort by price'): @lang('low to high')</option>
													<option value="price-desc">@lang('Sort by price'): @lang('high to low')</option>
												</select>										      </div>
										    </div>
										</div>  							
									</div>
								</div>

								<div class="section border-4 p-4 bg-dark-blue section-background-13">
									<div class="row pt-4">
										<div class="col-12 text-center">
						                    <livewire:frontend.attributes.color-change/>
										</div>
										@if($color)
										<div class="col-12 text-center mt-4">
											<button class="btn btn-danger" wire:click="clearFilterColor">
												@lang('Clear filter')
											</button>
										</div>
										@endif
									</div>
									<div class="row pt-4">
										<div class="col-12 text-center">
						                    <livewire:frontend.attributes.line-change/>
										</div>
										@if($line)
										<div class="col-12 text-center mt-4">
											<button class="btn btn-danger" wire:click="clearFilterLine">
												@lang('Clear filter')
											</button>
										</div>
										@endif
									</div>

									<div class="row pt-4">
										<div class="col-12 text-center">
						                    <livewire:frontend.attributes.size-change/>
										</div>
										@if($size)
										<div class="col-12 text-center mt-4">
											<button class="btn btn-danger" wire:click="clearFilterSize">
												@lang('Clear filter')
											</button>
										</div>
										@endif
									</div>

									<div class="row pt-4">
										@if($color || $size || $line)
										<div class="col-12 text-center mt-4">
											<span class="border-bottom-primary">
												<a wire:click="clearFilters" style="cursor: pointer;"> 
													@lang('Clear filters')
												</a>
											</span>										
										</div>
										@endif
									</div>

								</div>
							</div>

							{{-- <div class="section pt-5" id="sticker-blog-1-col" wire:ignore>
								<div class="section border-4 p-4 bg-light-2">
									<div class="row justify-content-center">
										<div class="col-9 col-sm-8 col-lg-10 col-xl-8 img-wrap">
											<img src="{{ asset('/ga/img/shop-11.svg' )}}" alt="">
										</div>
										<div class="col-12 text-center">
											<h6 class="mb-0">
												Summer Sale
											</h6>
											<h3 class="mb-0">
												Up to 60%
											</h3>
										</div>
										<div class="col-12 mt-4 text-center">
											<div class="section divider">
												<div class="divider-icon"><p class="size-14 mb-0">Don’t miss this chance</p></div>
											</div>
										</div>
										<div class="col-12 mt-4 text-center">
											<button type="button" class="btn btn-yellow" data-dismiss="modal">Shop now</button>
										</div>
									</div>
								</div>
							</div> --}}
						</div>
						<div class="col-lg-9 mt-5 mt-lg-0">
							<div class="row shop-mix-wrapper-1">
								@foreach($products as $product)
								<div class="col-sm-6 col-lg-4 pb-4">
									<div class="section shop-wrap-3 img-wrap border-4">
									  	@if($product->file_name)

										<figure>
											<img class="border-4" src="{{ asset('/storage/' . $product->file_name) }}"  alt="{{ $product->name }}" onerror="this.onerror=null;this.src='/porto/assets/images/not0.png';" >
										</figure>
										@else
										<figure>

									    	<a href="{{ route('frontend.shop.show', $product->slug) }}">
									    		<div class="readme-link__figure">
										    		<img src="{{ asset('/porto/assets/images/not0.png')}}" class="border-4 readme-link__figure" alt="{{ $product->name }}">
										    	</div>
										    </a>
										</figure>
										@endif

										<div class="shop-wrap-2-left">
											@if($product->created_at->gt(\Carbon\Carbon::now()->subMonth()))
												<div class="mt-2 shop-wrap-2-left-circle bg-blue color-white size-13 font-weight-600">new</div>
											@endif
										</div>
										<div class="shop-wrap-2-right">
											@if($product->file_name)
											<a href="{{ asset('/storage/' . $product->file_name) }}" class="shop-wrap-2-right-circle" data-fancybox=""><i class="uil uil-search size-16"></i></a>
											@endif
											<a href="{{ route('frontend.shop.show', $product->slug) }}" class="shop-wrap-2-right-circle animsition-link mt-2"><i class="uil uil-plus size-16"></i></a>
										</div>
										<div class="shop-wrap-2-text">
											@if($product->children->unique('size_id')->count())
											<div class="shop-wrap-2-size">
												<p class="mb-0 color-white text-uppercase size-13 font-weight-600">
													@foreach($product->children->unique('size_id')->sortBy('size.name')->slice(0, 3) as $children) 
														<span class="mx-1">{{ optional($children->size)->short_name }}</span>
													@endforeach
													@if($product->children->unique('size_id')->count() > 3)
														<span class="mx-1">...</span>
													@endif
												</p>
											</div>
											@endif
											<div class="row">
												<div class="col">
													<h6 class="mb-2">
														<a href="{{ route('frontend.shop.show', $product->slug) }}" class="link-heading animsition-link">{{ $product->name }}</a>
													</h6>
													<p class="lead mb-1 font-weight-600">
														<span class="text-line-through mr-1">${{ $product->price*((100+15))/100 }}</span> <span class="color-primary">${{ $product->price }}</span>
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
														<a href="{{ route('frontend.shop.show', $product->slug) }}" class="link-heading animsition-link">
															<i class="uil uil-cart size-28"></i>
															<span class="btn-small-icon bg-dark-blue color-white">
																<i class="uil uil-arrow-right "></i>
															</span>
														</a>
													</p>
												</div>
											</div>
										</div>
									</div>	
								</div>
								@endforeach
								<div class="col-12 pt-4">
									<nav aria-label="Page navigation example">
				
									    @if($products->count())
										<div class="row">
									        <div class="col-sm-9">
									        	{{ $products->links() }}
										    </div>
									        <div class="col-sm-3 text-muted text-right">
									        	Mostrando {{ $products->firstItem() }} - {{ $products->lastItem() }} de {{ $products->total() }} resultados
									        </div>
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
								</div>
							</div>							
						</div>
				</div>
