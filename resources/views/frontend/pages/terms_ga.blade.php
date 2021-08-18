@extends('frontend.layouts.app_ga')

@section('title', __('Terms & Conditions'))

@section('content')

<div class="section over-hide padding-top-120 padding-top-mob-nav">	
	<div class="section-1400 pt-xl-4">
		<div class="container-fluid padding-top-bottom-80">
			<div class="row">
				<div class="col-lg">
					<h2 class="display-8 mb-0">
						@lang('Terms & Conditions')
					</h2>
				</div>
				<div class="col-lg-auto align-self-center mt-4 mt-lg-0">

					@if (config('boilerplate.frontend_breadcrumbs'))
					    @include('frontend.includes_ga.partials.breadcrumbs')
					@endif

				</div>
			</div>
		</div>
	</div>
	<div class="section-1400 padding-bottom-120">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-12">
					<p class="mb-0">
						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
					</p>
				</div>
				<div class="col-md-6 mt-4">
					<h6 class="mb-3">
						Half width
					</h6>
					<p class="mb-0">
						Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.
					</p>
				</div>
				<div class="col-md-6 mt-4">
					<h6 class="mb-3">
						Half width
					</h6>
					<p class="mb-0">
						Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.
					</p>
				</div>
				<div class="col-lg-8 mt-4">
					<h6 class="mb-3">
						2/3 width
					</h6>
					<p class="mb-0">
						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores.
					</p>
				</div>
				<div class="col-lg-4 mt-4">
					<h6 class="mb-3">
						1/3 width
					</h6>
					<p class="mb-0">
						Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
					</p>
				</div>
				<div class="col-lg-9 mt-4">
					<h6 class="mb-3">
						3/4 width
					</h6>
					<p class="mb-0">
						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores.
					</p>
				</div>
				<div class="col-lg-3 mt-4">
					<h6 class="mb-3">
						1/4 width
					</h6>
					<p class="mb-0">
						Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia magni dolores eos qui ratione.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
