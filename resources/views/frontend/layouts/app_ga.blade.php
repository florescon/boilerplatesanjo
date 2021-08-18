<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Basic Page Needs
        ================================================== -->
        <meta charset="utf-8">
        <title>{{ appName() }}</title>
        <meta name="description" content="@yield('meta_description', appName())">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        <meta name="keywords"  content="" />
        <meta property="og:title" content="" />
        <meta property="og:type" content="" />
        <meta property="og:url" content="" />
        <meta property="og:image" content="" />
        <meta property="og:image:width" content="470" />
        <meta property="og:image:height" content="246" />
        <meta property="og:site_name" content="" />
        <meta property="og:description" content="" />
        <meta name="twitter:card" content="" />
        <meta name="twitter:site" content="" />
        <meta name="twitter:domain" content="" />
        <meta name="twitter:title" content="" />
        <meta name="twitter:description" content="" />
        <meta name="twitter:image" content="" />

        <!-- Mobile Specific Metas
        ================================================== -->
        
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="theme-color" content="#212121"/>
        <meta name="msapplication-navbutton-color" content="#212121"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="#212121"/>
                
        @yield('meta')

        @stack('before-styles')

        <link rel="stylesheet" href="{{ asset('/css_custom/port.css')}}">
        <link rel="stylesheet" href="{{ asset('/css_custom/cart_empty.css')}}">

        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

        <!-- CSS
        ================================================== -->
        <link rel="stylesheet" href="{{ asset('/ga/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/ga/css/animsition.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/ga/css/unicons.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/ga/css/lighbox.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/ga/css/tooltip.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/ga/css/swiper.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/ga/css/style.css') }}"/>
    

    
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">  
        <link rel="stylesheet" href="{{ asset('css_custom/whatsapp.css') }}">
                
        <!-- Favicons
        ================================================== -->
        {{-- <link rel="icon" type="image/png" href="favicon.png"> --}}
        {{-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> --}}
        {{-- <link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72.png"> --}}
        {{-- <link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114.png"> --}}

        <livewire:styles />
        @stack('after-styles')

    </head>

<body>  


    <div class="animsition">
    
    <a href="https://wa.me/524747423000" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>        

        <!-- Navigation
        ================================================== -->
                
        <div class="navigation-wrap cbp-af-header {{ $headerDark ?? null }} header-transparent">

            @include('includes.partials.read-only')
            @include('includes.partials.logged-in-as')
            {{-- @include('includes.partials.announcements') --}}

            @include('frontend.includes_ga.navigation')

        </div>
        
        <!-- Modal Search -->

            @include('frontend.includes_ga.modal-search')
        
        <!-- Modal Cart -->

            @livewire('frontend.header.header-cart-porto-drop')

        <!-- Primary Page Layout
        ================================================== -->
        
        <!-- Filter button
        ================================================== -->  
        
        <div class="blog-filter-button" data-toggle="modal" data-target="#modalFilters">
            <i class="uil uil-filter size-22"></i>
        </div>
        
        <!-- Modal filters -->
        
            {{-- @include('frontend.includes_ga.modal-filters') --}}
            <livewire:frontend.index.modal-line />
        
        <!-- Partials messages -->

           @include('includes.partials.messages_ga')

        <!-- Content -->
    
            @yield('content')


        <!-- Footer
        ================================================== -->

            @include('frontend.includes_ga.footer')
        
    </div>

    @stack('before-scripts')
    
    <!-- JAVASCRIPT
    ================================================== -->
    <script src="{{ asset('/ga/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/ga/js/popper.min.js') }}"></script> 
    <script src="{{ asset('/ga/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/ga/js/plugins.js') }}"></script> 
    <script src="{{ asset('/ga/js/custom.js') }}"></script> 
    <!-- Slider Revolution
    ================================================== -->
    <script src="{{ asset('/ga/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('/ga/js/jquery.themepunch.revolution.min.js') }}"></script>
    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS -->   
    <script src="{{ asset('/ga/js/extensions/revolution.extension.all.min.js') }}"></script>
    <script>    
        var tpj=jQuery;
        var revapi14;
        tpj(document).ready(function() {
            if(tpj("#rev_slider_14_1").revolution == undefined){
                revslider_showDoubleJqueryError("#rev_slider_14_1");
            }else{
                revapi14 = tpj("#rev_slider_14_1").show().revolution({
                    sliderType:"hero",
                    jsFileLocation:"js/",
                    sliderLayout:"fullscreen",
                    dottedOverlay:"none",
                    delay:9000,
                    navigation: {
                    },
                    responsiveLevels:[1400,1024,778,480],
                    visibilityLevels:[1400,1024,778,480],
                    gridwidth:[1400,1024,778,480],
                    gridheight:[1080,768,960,720],
                    lazyType:"none",
                    shadow:0,
                    spinner:"off",
                    autoHeight:"off",
                    fullScreenAutoWidth:"off",
                    fullScreenAlignForce:"off",
                    fullScreenOffsetContainer: "",
                    fullScreenOffset: "",
                    disableProgressBar:"on",
                    hideThumbsOnMobile:"off",
                    hideSliderAtLimit:0,
                    hideCaptionAtLimit:0,
                    hideAllCaptionAtLilmit:0,
                    debugMode:false,
                    fallbacks: {
                        simplifyAll:"off",
                        disableFocusListener:false,
                    }
                });
            }
        });
    </script>
    <livewire:scripts />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $.fn.select2.defaults.set('language', '@lang('labels.general.language')');
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.js"></script>

    @stack('after-scripts')

    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

<!-- End Document
================================================== -->
</body>

</html>
