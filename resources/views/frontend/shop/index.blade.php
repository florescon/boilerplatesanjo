@extends('frontend.layouts.app_ga')

@section('title', __('Login'))

@section('content')

	<div class="section over-hide padding-top-120 padding-top-mob-nav pb-5 section-background-20 background-img-top">	
		<div class="section-1400 pt-xl-4">
			<div class="container-fluid padding-top-bottom-80">
				<div class="row">
					<div class="col-lg">
						<h2 class="display-8 mb-3">
							<span class="typed-fashion"></span>
						</h2>
						<p class="lead mb-0 title-text-left-line-small">
							{{ appName() }} 
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

		<div class="section-1400">
			<div class="container-fluid">
	
					<livewire:frontend.shop.shop-component />

			</div>
		</div>
	</div>

@endsection


@push('after-scripts')
	<script>		
		// Type text

		var purchase = @json( __('Purchase of products'));
		var order = @json(__('Order products'));
		
		var typed = new Typed('.typed-fashion', {
			strings: [order, purchase],
			typeSpeed: 300,
			backSpeed: 0,
			startDelay: 200,
			backDelay: 2500,
			loop:false,
			loopCount:false,
			showCursor:true,
			cursorChar:"_",
			attr:null
		});	
	</script> 

@endpush