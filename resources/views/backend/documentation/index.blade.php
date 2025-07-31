<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>{{ appName() }} | @lang('Documentation')</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ appName() }}">
    <meta name="author" content="Anthony">
    <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('docs/assets/plugins/elegant_font/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css_custom/custom.css') }}">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('docs/assets/css/styles.css') }}">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="landing-page">

    <div class="page-wrapper">

        <!-- ******Header****** -->
        <header class="header text-center">
            <div class="container">
                <div class="branding">
                    <h1 class="logo">
                        <img src="{{ asset('img/logo2.svg') }}" width="180" style="margin-right: 10px;" alt="Logo">
                        <img src="{{ asset('img/bacapro.png') }}" width="180" style="margin-left: 10px;" alt="Logo">
                    </h1>
                </div><!--//branding-->
            </div><!--//container-->
        </header><!--//header-->

        <section class="cards-section text-center">
            <div class="container">
                <h2 class="title">@lang('Documentation')</h2>
                <div class="intro">

                    <div class="cta-container no-print">
                        <a class="btn btn-primary btn-cta" style="margin-bottom: 5px;" href="{{ url('/') }}" target="_blank"><i class="fa fa-star"></i> Ver pantalla de inicio</a>

                        <a class="btn btn-info btn-cta" style="margin-bottom: 5px;" href="{{ route('admin.dashboard') }}" target="_blank"><i class="fa fa-dashboard"></i> Panel administración</a>

                    </div><!--//cta-container-->
                </div><!--//intro-->

                <div id="cards-wrapper" class="cards-wrapper row">
                  <div class="item item-green col-sm-6">
                      <div class="item-inner">
                          <div class="icon-holder">
                              <i class="icon fa fa-paper-plane"></i>
                          </div><!--//icon-holder-->
                          <h3 class="title">@lang('Quick Start')</h3>
                          <p class="intro">Características y panorama general de la aplicación.</p>
                          <a class="link" href="{{ route('admin.documentation.start') }}"><span></span></a>
                      </div><!--//item-inner-->
                  </div><!--//item-->

                  <div class="item item-pink item-2 col-sm-6">
                      <div class="item-inner">
                          <div class="icon-holder">
                              <span aria-hidden="true" class="icon icon_puzzle_alt"></span>
                          </div><!--//icon-holder-->
                          <h3 class="title">@lang('Documentation')</h3>
                          <p class="intro">Todo el funcionamiento interno que le orientará.</p>
                          <a class="link" href="{{ route('admin.documentation.documentation') }}"><span></span></a>
                      </div><!--//item-inner-->
                  </div><!--//item-->

                    <div class="item item-purple col-sm-6">
                        <div class="item-inner">
                            <div class="icon-holder">
                                <span aria-hidden="true" class="icon icon_lifesaver"></span>
                            </div><!--//icon-holder-->
                            <h3 class="title">Manual</h3>
                            <p class="intro">Sabemos que ibas a consultar algo de esto.</p>
                            <a class="link" href="{{ route('admin.documentation.faqs') }}"><span></span></a>
                        </div><!--//item-inner-->
                    </div><!--//item-->

                    <div class="item item-orange col-sm-6">
                        <div class="item-inner">
                            <div class="icon-holder">
                                <span aria-hidden="true" class="icon icon_gift"></span>
                            </div><!--//icon-holder-->
                            <h3 class="title">Licencia &amp; Créditos</h3>
                            <p class="intro">Obligatorio.</p>
                            <a class="link" href="{{ route('admin.documentation.license') }}"><span></span></a>
                        </div><!--//item-inner-->
                    </div><!--//item-->
                </div><!--//cards-->
            </div><!--//container-->
        </section><!--//cards-section-->
    </div><!--//page-wrapper-->

    <footer class="footer text-center">
        <div class="container">
            <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can check out other license options via our website: themes.3rdwavemedia.com */-->
            <small class="copyright">Designed with <i class="fa fa-heart"></i></small>

        </div><!--//container-->
    </footer><!--//footer-->

    <!-- Main Javascript -->
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/jquery-1.12.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/plugins/jquery-match-height/jquery.matchHeight-min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('docs/assets/js/main.js') }}"></script>
</body>
</html>
