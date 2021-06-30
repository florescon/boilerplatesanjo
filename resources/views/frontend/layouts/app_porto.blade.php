<!DOCTYPE html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Flores Raul')">
    @yield('meta')


	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="{{ asset('/porto/assets/images/icons/favicon.ico') }}">

    @stack('before-styles')

	
	<script type="text/javascript">
		WebFontConfig = {
			google: { families: [ 'Open+Sans:300,400,600,700','Poppins:300,400,500,600,700,800', 'Playfair+Display:900' ] }
		};
		(function(d) {
			var wf = d.createElement('script'), s = d.scripts[0];
			wf.src = '{{ asset('/porto/assets/js/webfont.js') }}';
			wf.async = true;
			s.parentNode.insertBefore(wf, s);
		})(document);
	</script>

	<!-- Plugins CSS File -->
	<link rel="stylesheet" href="{{ asset('/porto/assets/css/bootstrap.min.css')}}">

	<!-- Main CSS File -->
	<link rel="stylesheet" href="{{ asset('/porto/assets/css/style.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('/porto/assets/vendor/fontawesome-free/css/all.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('/porto/assets/vendor/simple-line-icons/css/simple-line-icons.min.css')}}">


    <!-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <!-- <link href="{{ mix('css/frontend.css') }}" rel="stylesheet"> -->

    <livewire:styles />

    @stack('after-styles')

    @include('includes.partials.ga')

		
</head>
<body>
	<div class="page-wrapper">


    @include('includes.partials.read-only')
    @include('includes.partials.logged-in-as')
    {{-- @include('includes.partials.announcements') --}}

        @include('frontend.includes_porto.top-notice')

        @include('frontend.includes_porto.header')


		<main class="main">

		@if (config('boilerplate.frontend_breadcrumbs'))
		    @include('frontend.includes.partials.breadcrumbs')
		@endif

	    <div class="container py-4">
	        @include('includes.partials.messages')
        </div>

            @yield('content')

		</main><!-- End .main -->

        @include('frontend.includes_porto.footer')

	</div><!-- End .page-wrapper -->

	<div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    @include('frontend.includes_porto.mobile')

	<!-- Add Cart Modal -->
	<div class="modal fade" id="addCartModal" tabindex="-1" role="dialog" aria-labelledby="addCartModal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-body add-cart-box text-center">
			<p>You've just added this product to the<br>cart:</p>
			<h4 id="productTitle"></h4>
			{{-- <img src="{{ asset('/porto/#')}}" id="productImage" width="100" height="100" alt="adding cart image"> --}}
			<div class="btn-actions">
				<a href="cart.html"><button class="btn-primary">Go to cart page</button></a>
				<a href="#"><button class="btn-primary" data-dismiss="modal">Continue</button></a>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

    @stack('before-scripts')
	<!-- Plugins JS File -->
	<script src="{{ asset('/porto/assets/js/jquery.min.js')}}"></script>
	<script src="{{ asset('/porto/assets/js/bootstrap.bundle.min.js')}}"></script>
	<script src="{{ asset('/porto/assets/js/plugins.min.js')}}"></script>

	<!-- Main JS File -->
	<script src="{{ asset('/porto/assets/js/main.min.js')}}"></script>

    <livewire:scripts />
    @stack('after-scripts')

</body>

</html>