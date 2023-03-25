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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css_custom/app/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css_custom/app/trix.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">

    {{-- Strawberry --}}
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/strawberry.css') }}"> --}}

    <style type="text/css">
        
        sup {color:red;}

        .pagination {
            flex-wrap: wrap;
        }

        .select2-search--inline {
            display: contents; /*this will make the container disappear, making the child the one who sets the width of the element*/
        }

        .select2-search__field:placeholder-shown {
            width: 100% !important; /*makes the placeholder to be 100% of the width while there are no options selected*/
        }

        .box-color {
          height: 13px;
          width: 13px;
          border: 1px solid black;
        }

        .box-color-discrete {
          height: 8px;
          width: 8px;
        }

        .layout-switcher{ position: fixed; bottom: 0; left: 50%; transform: translateX(-50%) translateY(73px); color: #fff; transition: all .35s ease; background: #343a40; border-radius: .25rem .25rem 0 0; padding: .75rem; z-index: 999; }
        .layout-switcher:not(:hover){ opacity: .95; }
        .layout-switcher:not(:focus){ cursor: pointer; }
        .layout-switcher-head{ font-size: .75rem; font-weight: 600; text-transform: uppercase; }
        .layout-switcher-head i{ font-size: 1.25rem; transition: all .35s ease; }
        .layout-switcher-body{ transition: all .55s ease; opacity: 0; padding-top: .75rem; transform: translateY(24px); text-align: center; }
        .layout-switcher:focus{ opacity: 1; outline: none; transform: translateX(-50%) translateY(0); }
        .layout-switcher:focus .layout-switcher-head i{ transform: rotateZ(180deg); opacity: 0; }
        .layout-switcher:focus .layout-switcher-body{ opacity: 1; transform: translateY(0); }
        .layout-switcher-option{ width: 72px; padding: .25rem; border: 2px solid rgba(255,255,255,0); display: inline-block; border-radius: 4px; transition: all .35s ease; }
        .layout-switcher-option.active{ border-color: white; text-decoration: none; }

        .layout-switcher-option.active a:hover{ border-color: white; text-decoration: none; }

        .layout-switcher-icon{ width: 100%; border-radius: 4px; }
        .layout-switcher-body:hover .layout-switcher-option:not(:hover){ opacity: .5; transform: scale(0.9); }
        @media all and (max-width: 990px){ .layout-switcher{ min-width: 250px; } }
        @media all and (max-width: 767px){ .layout-switcher{ display: none; } }

    </style>

    {{-- <link rel="stylesheet" href="{{ asset('css_custom/app/all.min.css') }}" /> --}}
    <link href="{{ asset('css_custom/app/filepond.css') }}" rel="stylesheet">
    <link href="{{ asset('css_custom/app/filepond-plugin-image-preview.css') }}" rel="stylesheet">

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
        {{-- @include('includes.partials.announcements') --}}

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>

    <script>
        $.fn.select2.defaults.set('language', '@lang('labels.general.language')');
    </script>

    <script src="{{ asset('js_custom/app/es.js') }}"></script>

    <script src="{{ asset('js_custom/app/alpine.min.js') }}" defer></script>

    <script src="{{ asset('js_custom/app/party.min.js') }}"></script>

    <script src="{{ asset('js_custom/app/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('js_custom/app/filepond-plugin-file-validate-type.js') }}"></script>

    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>

    <script src="{{ asset('js_custom/app/filepond.js') }}"></script>
    
    <livewire:scripts />

    @stack('after-scripts')

</body>
</html>