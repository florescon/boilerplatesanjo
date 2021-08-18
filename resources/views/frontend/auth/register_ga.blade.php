@extends('frontend.layouts.app_ga')

@section('title', __('Register'))

@section('content')
	<div class="section over-hide padding-top-120 padding-top-mob-nav section-background-20">	
		<div class="section-1400 pt-xl-4">
			<div class="container-fluid padding-top-bottom-80">
				<div class="row">
					<div class="col-12 col-lg">
						<h2 class="display-8 mb-3">
							@lang('Register')
						</h2>
						<p class="lead mb-0 title-text-left-line-small">
							@lang('Register now')
						</p>
					</div>
					<div class="col-lg-auto align-self-center mt-4 mt-lg-0">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb no-border">
								<li class="breadcrumb-item"><a href="{{ url('/') }}" class="link link-dark-primary size-14" data-hover="@lang('Home')">@lang('Home')</a></li>
								<li class="breadcrumb-item active font-weight-500" aria-current="page"><span class="size-14">@lang('Register')</span></li>
							</ol>
						</nav>				
					</div>
				</div>
			</div>
		</div>
		<div class="section" >
			<div class="section-1400 padding-bottom-120">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-9 mt-5 mt-md-0">
							<h4 class="mb-4">
								@lang('Don\'t have an account? Register now.')
							</h4>
	                        <x-forms.post :action="route('frontend.auth.register')">
							<div class="row">

								<div class="col-lg-6">
									<div class="form-group">
										<input type="text" name="name" id="name" class="form-style big form-style-with-icon section-shadow-blue" placeholder="{{ __('Name') }}" value="{{ old('name') }}"   maxlength="100" required autofocus autocomplete="name" />
										<i class="input-icon big uil uil-user"></i>
									</div>	
								</div>
								<div class="col-lg-6 mt-4 mt-lg-0">
									<div class="form-group">
										<input type="email" name="email" id="email" class="form-style big form-style-with-icon section-shadow-blue" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required autocomplete="email" />
										<i class="input-icon big uil uil-at"></i>
									</div>	
								</div>	
								<div class="col-lg-6 mt-4">
									<div class="form-group">
										<input type="password" name="password" id="password" class="form-style big form-style-with-icon section-shadow-blue" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="new-password" />
										<i class="input-icon big uil uil-unlock-alt"></i>
									</div>
								</div>
								<div class="col-lg-6 mt-4">
									<div class="form-group">
										<input type="password" name="password_confirmation" id="password_confirmation" class="form-style big form-style-with-icon section-shadow-blue" placeholder="{{ __('Password Confirmation') }}" maxlength="100" required autocomplete="new-password" />
										<i class="input-icon big uil uil-lock-alt"></i>
									</div>
								</div>
								<div class="col-12 mt-4">
									<div class="form-group">
										<input type="checkbox" name="terms" value="1" id="terms" required>
										<label class="checkbox mb-0 font-weight-500 size-15" for="terms">@lang('I agree to the') <a href="{{ route('frontend.pages.terms') }}" target="_blank">@lang('Terms & Conditions')</a></label>
									</div>
								</div>


		                        @if(config('boilerplate.access.captcha.registration'))
		                            <div class="row">
		                                <div class="col">
		                                    @captcha
		                                    <input type="hidden" name="captcha_status" value="true" />
		                                </div><!--col-->
		                            </div><!--row-->
		                        @endif

								<div class="col-12 mt-4">
									<button type="submit" class="btn btn-dark-primary">@lang('Register')<i class="d-none d-sm-inline uil uil-arrow-right size-22 ml-3"></i></button> 
								</div>
							</div>
							</x-forms.post>	
	
							{{-- <div class="row">
								<div class="col-12 mt-4">
									<h6 class="mb-3">
										or register with:
									</h6>
								</div>
								<div class="col-md-4">
									<button type="button" class="btn btn-google btn-fluid"><i class="uil uil-google size-22 mr-2"></i>Google</button> 
								</div>
								<div class="col-md-4 mt-2 mt-md-0">
									<button type="button" class="btn btn-twitter btn-fluid"><i class="uil uil-twitter size-22 mr-2"></i>Twitter</button> 
								</div>
								<div class="col-md-4 mt-2 mt-md-0">
									<button type="button" class="btn btn-facebook btn-fluid"><i class="uil uil-facebook-f size-22 mr-2"></i>Facebook</button> 
								</div>
							</div> --}}
						</div>		
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
