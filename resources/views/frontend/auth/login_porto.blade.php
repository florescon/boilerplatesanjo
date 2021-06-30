@extends('frontend.layouts.app_porto')

@section('title', __('Login'))

@section('content')

			<nav aria-label="breadcrumb" class="breadcrumb-nav">
				<div class="container">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index-2.html"><i class="icon-home"></i></a></li>
						<li class="breadcrumb-item active" aria-current="page">Login</li>
					</ol>
				</div><!-- End .container -->
			</nav>

			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="heading">
							<h2 class="title">Login</h2>
							<p>If you have an account with us, please log in.</p>
						</div><!-- End .heading -->

                        <form action="{{ route('frontend.auth.login') }}" method="post">
                    	    @csrf
							<input type="email"  name="email" id="email"class="form-control" placeholder="@lang('E-mail Address')" maxlength="255" required autofocus autocomplete="email">
							<input type="password" class="form-control" name="password" id="password" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="current-password">

							<div class="form-footer">
								<button type="submit" class="btn btn-primary">LOGIN</button>
								<a href="#" class="forget-pass"> Forgot your password?</a>
							</div><!-- End .form-footer -->
						</form>
					</div><!-- End .col-md-6 -->

					<div class="col-md-6">
						<div class="heading">
							<h2 class="title">Create An Account</h2>
							<p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
						</div><!-- End .heading -->

						<form action="#">
							<input type="text" class="form-control" placeholder="First Name" required>
							<input type="text" class="form-control" placeholder="Middle Name" required>
							<input type="text" class="form-control" placeholder="Last Name" required>

							<h2 class="title mb-2">Login information</h2>
							<input type="email" class="form-control" placeholder="Email Address" required>
							<input type="password" class="form-control" placeholder="Password" required>
							<input type="password" class="form-control" placeholder="Confirm Password" required>

							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="newsletter-signup">
								<label class="custom-control-label" for="newsletter-signup">Sing up our Newsletter</label>
							</div><!-- End .custom-checkbox -->

							<div class="form-footer">
								<button type="submit" class="btn btn-primary">Create Account</button>
							</div><!-- End .form-footer -->
						</form>
					</div><!-- End .col-md-6 -->
				</div><!-- End .row -->
			</div><!-- End .container -->

			<div class="mb-5"></div><!-- margin -->

@endsection
