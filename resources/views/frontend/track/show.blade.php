@extends('frontend.layouts.app_ga')

@section('title', __('Track Order'))

@section('content')
	@if(!$result || $order->checkUser())
		<div class="section over-hide padding-top-80">
			<div class="section-1400">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 padding-top-120 padding-top-mob-nav" data-scroll-reveal="enter left move 40px over 1.5s after 0.3s">
							<div class="section border-4 bg-dark-blue padding-top-bottom-80">
								<div class="container">
									<div class="row">
										<div class="col-12 text-center">
											<h4 class="mb-0 color-white">@lang('Order') 
												<span class="color-primary">
													#{{ $order_id }}
												</span>
												<br>
												<div>
													@lang('Tracking number'): 
													<span class="color-purple">{{ $tracking_number ?? '' }}</span>
												</div>
												<div class="mt-4">
													<div class="countdown">
														<h5 class="mb-0 color-gray text-center">
															<span class="countdown-block"><span id="days"></span><br>@lang('days')</span>
															<span class="countdown-block"><span id="hours"></span><br>@lang('hours')</span>
															<span class="countdown-block"><span id="minutes"></span><br>@lang('min')</span>
															<span class="countdown-block"><span id="seconds"></span><br>@lang('seconds')</span>
														</h5>
														<h5 class="color-gray mt-4">
															Restante para visualizar
														</h5>
													</div>
												</div>
											</h4>
											@if($order->last_status_order)
											<div class="row mt-5">
												<div class="col-12">
													<p class="lead font-weight-bold mb-4 color-white">{{ $order->last_status_order->name_status ?? '' }}</p>
												</div> 
												<div class="col-12">
													<div class="progress w-label">
														<div class="progress-bar" role="progressbar" style="width: {{ $percentage_status }}%" aria-valuenow="{{ $percentage_status }}" aria-valuemin="0" aria-valuemax="100"><span>{{ $percentage_status }}%</span></div>
													</div>									
												</div>           
											</div> 
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 px-0 mb-5">
							<div class="section padding-top padding-sm-top-50 z-bigger" id="sticker">
								<div class="container">
									<div class="row">
										<div class="col-12">
											<nav class="navbar navbar-expand-lg navbar-light d-lg-block mt-5">
												<h4 class="mb-0 d-inline-block d-lg-none">@lang('Detail')</h4>							
												<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
													<span class="navbar-toggler-icon"></span>
												</button>								
												<div class="collapse navbar-collapse text-left flex-column" id="navbarMenu">
													<a href="#information" class="link size-16 w-100 mt-1 mt-lg-0" data-hover="{{ __('Information') }}">@lang('Information')</a> 
													@if($order->product_order->count())	
														<a href="#order" class="link size-16 w-100 mt-1" data-hover="{{ __('Order') }}">@lang('Order')</a> 
													@endif
													@if($order->product_sale->count())
														<a href="#sale" class="link size-16 w-100 mt-1" data-hover="{{ __('Sale') }}">@lang('Sale')</a>
													@endif 
													@if($order->product_suborder->count())	
														<a href="#order" class="link size-16 w-100 mt-1" data-hover="{{ __('Suborder') }}">@lang('Suborder')</a> 
													@endif
												</div>									
											</nav>	
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-9 px-0">
							<div class="container padding-top" id="information">
								<div class="row">
									<div class="col-lg-12 mt-4">	
										<div class="anime-box text-center border-0" data-scroll-reveal="enter left move 80px over 1.2s after 0.3s">
											<h4 class="mb-0 text-dark">{{ optional($order->user)->name }}</h4>
										</div>									
									</div>
									@if($order->last_status_order)
									<div class="col-lg-6 mt-4">	
										<div class="anime-box text-center shadow-lg" data-scroll-reveal="enter left move 80px over 1.2s after 0.3s">
											<h5 class="mb-1">{{ $order->last_status_order->name_status ?? '' }}</h5>
											<p class="mb-0">@lang('Order production status')</p>
										</div>									
									</div>
									@endif
									@if($order->date_entered)
									<div class="col-lg-6 mt-4">	
										<div class="anime-box text-center shadow-lg" data-scroll-reveal="enter right move 80px over 1.2s after 0.3s">
											<h5 class="mb-1">{{ $order->date_entered ?? '' }}</h5>
											<p class="mb-0">@lang('Date')</p>
										</div>									
									</div>
									@endif
								</div>
							</div>	
							@if($order->product_order->count())	
								<div class="container padding-top" id="order">
									<div class="row">
										<div class="col-lg-8">
											<h4 class="mb-2">@lang('Order')</h4>
											<p class="mb-3 pb-3">@lang('Order details').</p>
										</div>
										<div class="section"></div>
										<div class="col-12">
											<div class="table-responsive">
												<table class="table table-hover">
													<thead>
														<tr>
										                    <th scope="col">@lang('Product')</th>
										                    <th scope="col" class="text-center">@lang('Quantity')</th>
										                    <th scope="col" class="text-center">@lang('Price')</th>
										                    <th scope="col" class="text-center">Total</th>

														</tr>
													</thead>
													<tbody>
										                @foreach($order->product_order as $product)
														<tr>
															<td>{!! clean($product->product->full_name) !!}</td>
															<td class="text-center">{{ $product->quantity }}</td>
															<td class="text-center">${{ $product->price }}</td>
															<td class="text-center">${{ $product->total_by_product }}</td>
														</tr>
														@endforeach
													</tbody>
												</table>	
											</div>
										</div>
									</div>
								</div>
							@endif
							@if($order->product_suborder->count())	
								<div class="container padding-top" id="order">
									<div class="row">
										<div class="col-lg-8">
											<h4 class="mb-2">@lang('Order')</h4>
											<p class="mb-3 pb-3">@lang('Order details').</p>
										</div>
										<div class="section"></div>
										<div class="col-12">
											<div class="table-responsive">
												<table class="table table-hover">
													<thead>
														<tr>
										                    <th scope="col">@lang('Product')</th>
										                    <th scope="col" class="text-center">@lang('Quantity')</th>
										                    <th scope="col" class="text-center">@lang('Price')</th>
										                    <th scope="col" class="text-center">Total</th>

														</tr>
													</thead>
													<tbody>
											            @php($total = 0)
										                @foreach($order->product_suborder as $product)
														<tr>
															<td>{{ $product->parent_order->product->parent->name}}
											                    {{ $product->parent_order->product->color->name. '  '.$product->parent_order->product->size->name }}
															</td>
															<td class="text-center">{{ $product->quantity }}</td>
															<td class="text-center">${{ $product->parent_order->price }}</td>
															<td class="text-center">${{ number_format($totalprod = $product->parent_order->price * $product->quantity, 2, ".", ",") }}</td>
														</tr>
														@endforeach
											            @php($total += $totalprod)
													</tbody>
												</table>	
											</div>
										</div>
									</div>
								</div>
							@endif
							@if($order->product_sale->count())
								<div class="container padding-top" id="sale">
									<div class="row">
										<div class="col-lg-8">
											<h4 class="mb-2">@lang('Sale')</h4>
											<p class="mb-3 pb-3">@lang('Sale details').</p>
										</div>
										<div class="section"></div>
										<div class="col-12">
											<div class="table-responsive">
												<table class="table table-hover">
													<thead>
														<tr>
										                    <th scope="col">@lang('Product')</th>
										                    <th scope="col" class="text-center">@lang('Quantity')</th>
										                    <th scope="col" class="text-center">@lang('Price')</th>
										                    <th scope="col" class="text-center">Total</th>

														</tr>
													</thead>
													<tbody>
										                @foreach($order->product_sale as $product)
														<tr>
															<td>{!! clean($product->product->full_name) !!}</td>
															<td class="text-center">{{ $product->quantity }}</td>
															<td class="text-center">${{ $product->price }}</td>
															<td class="text-center">${{ $product->total_by_product }}</td>
														</tr>
														@endforeach
													</tbody>
												</table>	
											</div>
										</div>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="section over-hide height-80 section-background-20">	
			<div class="hero-center-section">
				<div class="section-1400">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<h2 class="display-8 text-center mb-4">
									@lang('Order')/@lang('Sale'): 
									<span class="color-primary">
										#{{ $order_id }}
									</span>

								</h2>
								<p class="lead text-center mb-0">
									Tiempo de visualización vencido, inicie sesión para ver detalles.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection


@push('after-scripts')
	<script>
		const second = 1000,
			  minute = second * 60,
			  hour = minute * 60,
			  day = hour * 24,
			  week = hour * 24 * 7;
		var time = @json($limit);

		let countDown = new Date(time).getTime(),
			x = setInterval(function() {

			  let now = new Date().getTime(),
				  distance = countDown - now;

			  document.getElementById('days').innerText = Math.floor(distance / (day)),
				document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
				document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
				document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);

		}, second)
	</script>
@endpush