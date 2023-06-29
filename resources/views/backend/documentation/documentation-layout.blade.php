<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>{{ appName() }} | @yield('title')</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">    
    <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/prism/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/lightbox/dist/ekko-lightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/elegant_font/css/style.css') }}">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('docs/assets/css/styles.css') }}">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head> 

<body class="body-orange">
    <div class="page-wrapper">
        <!-- ******Header****** -->
        <header id="header" class="header">
            <div class="container">
                <div class="branding">
                    <h1 class="logo">
                        <a href="{{ route('admin.documentation.index') }}">
                            <span aria-hidden="true" class="icon_documents_alt icon"></span>
                            <span class="text-highlight">@lang('Documentation')</span> <span class="text-bold">{{ appName() }}</span>
                        </a>
                    </h1>
                </div><!--//branding-->
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.documentation.index') }}">Home</a></li>
                    <li class="active"> @yield('title')</li>
                </ol>
            </div><!--//container-->
        </header><!--//header-->
        <div class="doc-wrapper">
            <div class="container">
                <div id="doc-header" class="doc-header text-center">
                    <h1 class="doc-title"><span aria-hidden="true" class="icon icon_gift"></span> @yield('title') </h1>
                    <div class="meta"><i class="fa fa-clock-o"></i> Last updated: June 26th, 2023</div>
                </div><!--//doc-header-->

                @yield('content')

            </div><!--//container-->
        </div><!--//doc-wrapper-->
        
    </div><!--//page-wrapper-->
    
    <footer class="footer text-center">
        <div class="container">
            <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can check out other license options via our website: themes.3rdwavemedia.com */-->
            <small class="copyright">Designed with <i class="fa fa-heart"></i>
            
        </div><!--//container-->
    </footer><!--//footer-->
    
    <!-- Main Javascript -->          

    <script type="text/javascript" src="{{ asset('docs/assets/plugins/jquery-1.12.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/prism/prism.js') }}"></script>    
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/lightbox/dist/ekko-lightbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/jquery-match-height/jquery.matchHeight-min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/js/main.js') }}"></script>

</body>
</html> 

