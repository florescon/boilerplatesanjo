<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Flores')">
    @yield('meta')

    @stack('before-styles')
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" type="text/css" href="{{ url('css/start.css') }}"> --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('css_custom/app/pikaday.css') }}">
    <link rel="stylesheet" href="{{ asset('css_custom/app/bootstrap-table.min.css') }}">

    {{-- <link href="{{ asset('css_custom/app/select2.min.css') }}" rel="stylesheet" /> --}}

    <link href="{{ asset('css_custom/app/select2-4.1.0-rc.0.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css_custom/app/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css_custom/app/trix.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    {{-- Strawberry --}}
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/strawberry.css') }}"> --}}

    {{-- <link rel="stylesheet" href="{{ asset('css_custom/app/all.min.css') }}" /> --}}
    <link href="{{ asset('css_custom/app/filepond.css') }}" rel="stylesheet">
    <link href="{{ asset('css_custom/app/filepond-plugin-image-preview.css') }}" rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    
    <style type="text/css">

        body {
          font-family: 'Karla', sans-serif !important;
        }

        .text-decoration-line-through{text-decoration:line-through!important}
 
        .btn-outline-primary:hover {
          color: white !important;
        }
        .btn-primary:hover {
          color: white !important;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css_custom/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css_custom/custom.css') }}">

    <livewire:styles />
    @stack('after-styles')
</head>
<body class="c-app">
    @include('backend.includes.sidebar')

    <div class="c-wrapper c-fixed-components">
        @include('backend.includes.header')
        @include('includes.partials.read-only')
        @include('includes.partials.logged-in-as')
        @include('includes.partials.announcements')

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        @include('includes.partials.messages')
                        @yield('content')
                    </div><!--fade-in-->
                </div><!--container-fluid-->
            </main>
        </div><!--c-body-->

        @include('backend.includes.footer')
    </div><!--c-wrapper-->

    @stack('before-scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/backend.js') }}"></script>

    @include('backend.layouts.sweet')

    <script src="{{ asset('js_custom/app/moment.js') }}"></script>
    <script src="{{ asset('js_custom/app/pikaday.js') }}"></script>

    <script src="{{ asset('js_custom/app/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('js_custom/app/vanilla-picker.min.js') }}"></script>
    <script src="{{ asset('js_custom/app/trix.js') }}"></script>

    @stack('middle-scripts')

    {{-- <script src="{{ asset('js_custom/app/select2.min.js') }}"></script> --}}
    <script src="{{ asset('js_custom/app/select2-4.1.0-rc.0.min.js') }}"></script>

    <script>
        $.fn.select2.defaults.set('language', '@lang('labels.general.language')');
    </script>

    <script src="{{ asset('js_custom/app/es.js') }}"></script>

    <script src="{{ asset('js_custom/app/alpine.min.js') }}" defer></script>

    <script src="{{ asset('js_custom/app/party.min.js') }}"></script>

    <script src="{{ asset('js_custom/app/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('js_custom/app/filepond-plugin-file-validate-type.js') }}"></script>

    <script src="{{ asset('js_custom/app/filepond-plugin-file-validate-size.js') }}"></script>

    <script src="{{ asset('js_custom/app/filepond.js') }}"></script>
    
    <livewire:scripts />

    @stack('after-scripts')

</body>
</html>