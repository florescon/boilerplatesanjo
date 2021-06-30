			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<div class="cart-table-container">
							<table class="table table-cart">
								<thead>
									<tr>
										<th class="product-col">@lang('Product')</th>
										<th class="price-col">@lang('Price')</th>
										<th class="qty-col">@lang('Qty')</th>
										<th>@lang('Subtotal')</th>
									</tr>
								</thead>
								<tbody>
									<tr class="product-row">
										<td class="product-col">
											<figure class="product-image-container">
												<a href="product.html" class="product-image">
													<img src="{{ asset('/porto/assets/images/products/product-4.jpg')}}" alt="product">
												</a>
											</figure>
											<h2 class="product-title">
												<a href="product.html">Men Watch</a>
											</h2>
										</td>
										<td>$17.90</td>
										<td>
											<input class="vertical-quantity form-control" type="text">
										</td>
										<td>$17.90</td>
									</tr>
									<tr class="product-action-row">
										<td colspan="4" class="clearfix">
											<div class="float-left">
												<a href="#" class="btn-move">@lang('Move to wishlist')</a>
											</div><!-- End .float-left -->
											
											<div class="float-right">
												<a href="#" title="Edit product" class="btn-edit"><span class="sr-only">Edit</span><i class="icon-pencil"></i></a>
												<a href="#" title="Remove product" class="btn-remove"><span class="sr-only">Remove</span></a>
											</div><!-- End .float-right -->
										</td>
									</tr>

									<tr class="product-row">
										<td class="product-col">
											<figure class="product-image-container">
												<a href="product.html" class="product-image">
													<img src="{{ asset('/porto/assets/images/products/product-3.jpg')}}" alt="product">
												</a>
											</figure>
											<h2 class="product-title">
												<a href="product.html">Computer Mouse</a>
											</h2>
										</td>
										<td>$8.90</td>
										<td>
											<input class="vertical-quantity form-control" type="text">
										</td>
										<td>$8.90</td>
									</tr>
									<tr class="product-action-row">
										<td colspan="4" class="clearfix">
											<div class="float-left">
												<a href="#" class="btn-move">Move to Wishlist</a>
											</div><!-- End .float-left -->
											
											<div class="float-right">
												<a href="#" title="Edit product" class="btn-edit"><span class="sr-only">Edit</span><i class="icon-pencil"></i></a>
												<a href="#" title="Remove product" class="btn-remove"><span class="sr-only">Remove</span></a>
											</div><!-- End .float-right -->
										</td>
									</tr>
								</tbody>

								<tfoot>
									<tr>
										<td colspan="4" class="clearfix">
											<div class="float-left">
												<a href="category.html" class="btn btn-outline-secondary">@lang('Continue shopping')</a>
											</div><!-- End .float-left -->

											<div class="float-right">
												<a href="#" class="btn btn-outline-secondary btn-clear-cart">@lang('Clear cart')</a>
											</div><!-- End .float-right -->
										</td>
									</tr>
								</tfoot>
							</table>
						</div><!-- End .cart-table-container -->

						<div class="cart-discount">
							<h4>@lang('Apply discount code')</h4>
							<form action="#">
								<div class="input-group">
									<input type="text" class="form-control form-control-sm" placeholder="@lang('Enter discount code')"  required>
									<div class="input-group-append">
										<button class="btn btn-sm btn-primary" type="submit">@lang('Apply discount')</button>
									</div>
								</div><!-- End .input-group -->
							</form>
						</div><!-- End .cart-discount -->
					</div><!-- End .col-lg-8 -->

					<div class="col-lg-4">
						<div class="cart-summary">
							<h3>@lang('Summary')</h3>

							<h4>
								<a data-toggle="collapse" href="#total-estimate-section" class="collapsed" role="button" aria-expanded="false" aria-controls="total-estimate-section">@lang('Estimated prices')</a>
							</h4>

							<div class="collapse" id="total-estimate-section">
								<form action="#">
									<div class="form-group form-group-sm">
										<label>Country</label>
										<div class="select-custom">
											<select class="form-control form-control-sm">
												<option value="USA">United States</option>
												<option value="Turkey">Turkey</option>
												<option value="China">China</option>
												<option value="Germany">Germany</option>
											</select>
										</div><!-- End .select-custom -->
									</div><!-- End .form-group -->

									<div class="form-group form-group-sm">
										<label>State/Province</label>
										<div class="select-custom">
											<select class="form-control form-control-sm">
												<option value="CA">California</option>
												<option value="TX">Texas</option>
											</select>
										</div><!-- End .select-custom -->
									</div><!-- End .form-group -->

									<div class="form-group form-group-sm">
										<label>Zip/Postal Code</label>
										<input type="text" class="form-control form-control-sm">
									</div><!-- End .form-group -->

									<div class="form-group form-group-custom-control">
										<label>Flat Way</label>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="flat-rate">
											<label class="custom-control-label" for="flat-rate">Fixed $5.00</label>
										</div><!-- End .custom-checkbox -->
									</div><!-- End .form-group -->

									<div class="form-group form-group-custom-control">
										<label>Best Rate</label>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="best-rate">
											<label class="custom-control-label" for="best-rate">Table Rate $15.00</label>
										</div><!-- End .custom-checkbox -->
									</div><!-- End .form-group -->
								</form>
							</div><!-- End #total-estimate-section -->

							<table class="table table-totals">
								<tbody>
									<tr>
										<td>Subtotal</td>
										<td>$17.90</td>
									</tr>

									<tr>
										<td>Tax</td>
										<td>$0.00</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td>Order Total</td>
										<td>$17.90</td>
									</tr>
								</tfoot>
							</table>

							<div class="checkout-methods">
								<a href="checkout-shipping.html" class="btn btn-block btn-sm btn-primary">@lang('Go to checkout')</a>
								<a href="#" class="btn btn-link btn-block">Verificar direccion</a>
							</div><!-- End .checkout-methods -->
						</div><!-- End .cart-summary -->
					</div><!-- End .col-lg-4 -->
				</div><!-- End .row -->
			</div><!-- End .container -->
