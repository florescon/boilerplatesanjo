<!DOCTYPE html>
<html class="no-js" lang="es">
<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>@lang('Bom of Materials by Station') {{ now() }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  {{-- <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> --}}
  <style type="text/css">
    body {
      font-family: 'Karla', sans-serif !important;
    }
  </style>

  <livewire:styles />

</head>

<body>
  <livewire:backend.order.print-letter-order :order="$order" :station="$station"/>

  <livewire:scripts />


  <script src="{{ mix('js/manifest.js') }}"></script>
  <script src="{{ mix('js/vendor.js') }}"></script>
  <script src="{{ mix('js/backend.js') }}"></script>
  <script src="{{ asset('/js_custom/vendor.min.js') }}"></script>

  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14
          , height: 14
        });
      }
    })
  </script>

  @include('backend.layouts.sweet')

  <script src="{{ asset('js_custom/app/alpine.min.js') }}" defer></script>


    <script src="{{ asset('js_custom/app/moment.js') }}"></script>
    <script src="{{ asset('js_custom/app/pikaday.js') }}"></script>

</body>
</html>
