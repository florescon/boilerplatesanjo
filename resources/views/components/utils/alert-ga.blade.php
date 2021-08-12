@props(['dismissable' => true, 'type' => 'success', 'ariaLabel' => __('Close')])

<div {{ $attributes->merge(['class' => 'alert alert-style-fixed alert-dismissible fade show']) }} role="alert">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md align-self-center">
				<p class="text-left mb-0">
					<i class="uil uil-info-circle color-primary size-18 mr-1"></i>
					<span class="color-light">
					    {{ $slot }}
					</span>
				</p>
			</div>
		    @if ($dismissable)
			<div class="col-md-auto mt-3 mt-md-0 align-self-center">
				<button type="button" class="btn btn-primary btn-h-34 px-3" data-dismiss="alert" >Ok</button>
			</div>
			@endif
		</div>
	</div>
</div>
