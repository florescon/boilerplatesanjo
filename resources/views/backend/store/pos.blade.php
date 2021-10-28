@extends('backend.layouts.app')

@section('title', __('Shop panel'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/pos.css') }}">
@endpush

@section('content')
<div class="pcoded-content">

	<div class="page-header">
		<div class="page-block" style="background: rgba(11,13,255,.5);">
			<div class="row align-items-center">
				<div class="col-md-8">
					<div class="page-header-title">
						<h5 class="m-b-10"> {{ now()->format('h:i:s') }}</h5>
						<p class="m-b-0">@lang('Welcome to shop panel') {{ Auth::user()->name }}</p>
					</div>
				</div>
				<div class="col-md-4">
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#!" data-toggle="modal" wire:click="searchproduct()" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Search product')</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="pcoded-inner-content">

		<div class="main-body">
			<div class="page-wrapper">

				<div class="page-body">
					<div class="row">

						<livewire:backend.store.pos.cart-pos />

						<div class="col-xl-6 col-md-12">
							<div class="row">

								<div class="col-md-6">
									<div class="card text-center order-visitor-card">
										<div class="card-block">
											<h6 class="m-b-0">@lang('Yesterday\'s expense')</h6>
											<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-down m-r-15 text-c-red"></i>7652</h4>
											<p class="m-b-0">48% From Last 24 Hours</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card text-center order-visitor-card">
										<div class="card-block">
											<h6 class="m-b-0">@lang('Yesterday\'s income')</h6>
											<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-up m-r-15 text-c-green"></i>6325</h4>
											<p class="m-b-0">36% From Last 6 Months</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card bg-c-red total-card">
										<div class="card-block">
											<div class="text-left">
												<h4>489</h4>
												<p class="m-0">@lang('Expenses')</p>
											</div>
											<span class="label bg-c-red value-badges">15%</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card bg-c-green total-card">
										<div class="card-block">
											<div class="text-left">
												<h4>$5782</h4>
												<p class="m-0">@lang('Incomes')</p>
											</div>
											<span class="label bg-c-green value-badges">20%</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card text-center order-visitor-card">
										<div class="card-block">
											<h6 class="m-b-0">@lang('Expense for the week')</h6>
											<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-down m-r-15 text-c-red"></i>652</h4>
											<p class="m-b-0">36% From Last 6 Months</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card text-center order-visitor-card">
										<div class="card-block">
											<h6 class="m-b-0">@lang('Income for the week')</h6>
											<h4 class="m-t-15 m-b-15"><i class="fa fa-arrow-up m-r-15 text-c-green"></i>5963</h4>
											<p class="m-b-0">36% From Last 6 Months</p>
										</div>
									</div>
								</div>

							</div>
						</div>


						<div class="col-xl-12">
							<div class="card proj-progress-card">
								<div class="card-block">
									<div class="row">
										<div class="col-xl-3 col-md-6">
											<h6>Published Project</h6>
											<h5 class="m-b-30 f-w-700">532<span class="text-c-green m-l-10">+1.69%</span></h5>
											<div class="progress">
												<div class="progress-bar bg-c-red" style="width:25%"></div>
											</div>
										</div>
										<div class="col-xl-3 col-md-6">
											<h6>Completed Task</h6>
											<h5 class="m-b-30 f-w-700">4,569<span class="text-c-red m-l-10">-0.5%</span></h5>
											<div class="progress">
												<div class="progress-bar bg-c-blue" style="width:65%"></div>
											</div>
										</div>
										<div class="col-xl-3 col-md-6">
											<h6>Successfull Task</h6>
											<h5 class="m-b-30 f-w-700">89%<span class="text-c-green m-l-10">+0.99%</span></h5>
											<div class="progress">
												<div class="progress-bar bg-c-green" style="width:85%"></div>
											</div>
										</div>
										<div class="col-xl-3 col-md-6">
											<h6>Ongoing Project</h6>
											<h5 class="m-b-30 f-w-700">365<span class="text-c-green m-l-10">+0.35%</span></h5>
											<div class="progress">
												<div class="progress-bar bg-c-yellow" style="width:45%"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>

			</div>
			<div id="styleSelector"> </div>
		</div>
	</div>
</div>


	<livewire:backend.store.pos.search-product />

@endsection
