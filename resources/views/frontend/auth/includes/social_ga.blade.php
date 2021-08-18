@if(config('services.google.active') != false)
	<div class="col-md-4">
		<a role="button" href="{{ route('frontend.auth.social.login', 'google') }}" class="btn btn-google btn-fluid text-white"><i class="uil uil-google size-22 mr-2"></i>Google</a> 
	</div>
@endif
@if(config('services.twitter.active') != false)
	<div class="col-md-4 mt-2 mt-md-0">
		<a role="button" href="{{ route('frontend.auth.social.login', 'twitter') }}" class="btn btn-twitter btn-fluid text-white"><i class="uil uil-twitter size-22 mr-2"></i>Twitter</a> 
	</div>
@endif
@if(config('services.facebook.active') != false)
	<div class="col-md-4 mt-2 mt-md-0">
		<a role="button" href="{{ route('frontend.auth.social.login', 'facebook') }}" class="btn btn-facebook btn-fluid text-white"><i class="uil uil-facebook-f size-22 mr-2"></i>Facebook</a> 
	</div>
@endif