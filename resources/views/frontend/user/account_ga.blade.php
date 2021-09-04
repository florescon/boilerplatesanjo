@extends('frontend.layouts.app_ga')

@section('title', __('My Account'))

@section('content')
	<div class="section over-hide padding-top-bottom-120 padding-top-mob-nav section-background-24" id="page-top">	
		<div class="section-1400 pt-xl-4">
			<div class="container-fluid">
				<div class="row" id="accordionExample">
					<div class="col-lg-4">
						<div class="section border-4 bg-white landing-shadow-4 p-3 p-xl-4">
							<div class="row">
								<div class="col-sm-6 img-wrap">
									<img class="border-4" src="{{ $logged_in_user->avatar }}" alt="">
								</div>
								<div class="col-sm-6 align-self-center mt-2 mt-sm-0">
									<p class="mb-2">
										@lang('Online') <i class="uil uil-comment-check size-20 ml-2 color-primary"></i>
									</p>

                                    <a  class="tag sale mr-1" href="#" role="button" aria-haspopup="true" aria-expanded="false" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        @lang('Logout')
                                        <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                                    </a>  
								</div>
								<div class="col-12 mt-3">
									<h5 class="mb-1">
										{{ $logged_in_user->name }}
									</h5>
									<p class="mb-0 color-primary">
										@include('backend.auth.user.includes.type', ['user' => $logged_in_user])
									</p>
								</div>
								<div class="w-100 mt-4"></div>
								<div class="col-auto">
									<p class="mb-0 color-dark font-weight-500 size-16">
										@lang('E-mail Address'):
									</p>
								</div>
								<div class="col text-right">
									<p class="mb-0 size-16">
										{{ $logged_in_user->email }}
									</p>
								</div>

							    @if ($logged_in_user->isSocial())
								<div class="w-100 mt-4"></div>
								<div class="col-auto">
									<p class="mb-0 color-dark font-weight-500 size-16">
										@lang('Social Provider'):
									</p>
								</div>
								<div class="col text-right">
									<p class="mb-0 size-16">
										{{ ucfirst($logged_in_user->provider) }}
									</p>
								</div>
								@endif

								<div class="w-100 mt-1"></div>
								<div class="col-auto">
									<p class="mb-0 color-dark font-weight-500 size-16">
										Phone:
									</p>
								</div>
								<div class="col text-right">
									<p class="mb-0 size-16">
										--
									</p>
								</div>
								<div class="w-100 mt-1"></div>
								<div class="col-auto">
									<p class="mb-0 color-dark font-weight-500 size-16">
										@lang('Timezone'):
									</p>
								</div>
								<div class="col text-right">
									<p class="mb-0 size-16">
										{{ $logged_in_user->timezone ? str_replace('_', ' ', $logged_in_user->timezone) : __('N/A') }}
									</p>
								</div>
								<div class="w-100 mt-1"></div>
								<div class="col-auto">
									<p class="mb-0 color-dark font-weight-500 size-16">
										@lang('Account Created'):
									</p>
								</div>
								<div class="col text-right">
									<p class="mb-0 size-16">
										@displayDate($logged_in_user->created_at) ({{ $logged_in_user->created_at->diffForHumans() }})
									</p>
								</div>
								<div class="w-100 mt-1"></div>
								<div class="col-auto">
									<p class="mb-0 color-dark font-weight-500 size-16">
										@lang('Last Updated'):
									</p>
								</div>
								<div class="col text-right">
									<p class="mb-0 size-16">
										@displayDate($logged_in_user->updated_at) ({{ $logged_in_user->updated_at->diffForHumans() }})
									</p>
								</div>
								<div class="col-12 mt-4">
									<a href="#page-top" data-gal='m_PageScroll2id' class="btn btn-user-profile btn-fluid justify-content-start mt-1" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
										<i class="uil uil-info-circle size-22 mr-2 pr-1"></i>
										@lang('Edit Information')
									</a>
		                            @if (! $logged_in_user->isSocial())
									<a href="#page-top" data-gal='m_PageScroll2id' class="btn btn-user-profile btn-fluid justify-content-start mt-1" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
										<i class="uil uil-key-skeleton size-22 mr-2 pr-1"></i>
										@lang('Change Password')
									</a>
									@endif
									<a href="#page-top" data-gal='m_PageScroll2id' class="btn btn-user-profile btn-fluid justify-content-start mt-1" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
										<i class="uil uil-at size-22 mr-2 pr-1"></i>
										@lang('Two Factor Authentication')
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-8 mt-4 mt-lg-0">
						<div class="section border-4 bg-white landing-shadow-4 p-3 p-xl-4">   
						
							<div id="collapseThree" class="collapse p-0 show" data-parent="#accordionExample">
								<div class="card-body p-0">
									<div class="row">
										<div class="col-12">
											<h5 class="mb-1">
												@lang('Edit Information')
											</h5>
											<p class="mb-3">
												@lang('Change your account settings').
											</p>
										</div>
										<div class="col-12 pb-4">
											<div class="section divider divider-gray"></div>
										</div>
										@include('frontend.user.account.tabs.information')						
									</div>
								</div>
							</div>
							
                            @if (! $logged_in_user->isSocial())
							<div id="collapseFour" class="collapse p-0" data-parent="#accordionExample">
								<div class="card-body p-0">
									<div class="row">
										<div class="col-12">
											<h5 class="mb-1">
												@lang('Change Password')
											</h5>
											<p class="mb-3">
												@lang('Change or reset your account password').
											</p>
										</div>
										<div class="col-12">
											<div class="section divider divider-gray"></div>
										</div>
										<div class="col-12">
											@include('frontend.user.account.tabs.password')
										</div>
									</div>
								</div>
							</div>
							@endif
							
							<div id="collapseFive" class="collapse p-0" data-parent="#accordionExample">
								<div class="card-body p-0">
									<div class="row">
										<div class="col-12">
											<h5 class="mb-1">
												@lang('Two Factor Authentication')
											</h5>
											<p class="mb-3">
												@lang('Control 2FA').
											</p>
										</div>
										<div class="col-12">
											<div class="section divider divider-gray"></div>
										</div>
										<div class="w-100 pt-4"></div>
										<div class="col-12">
											@include('frontend.user.account.tabs.two-factor-authentication')
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection