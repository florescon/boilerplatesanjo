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