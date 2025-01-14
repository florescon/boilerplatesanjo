<!DOCTYPE html>
<html class="no-js" lang="es">
<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>@lang('Export orders by products') {{ now() }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">

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

  <livewire:backend.order.print-export-by-date :dateInput="$dateInput" :dateOutput="$dateOutput" :summary="$summary" :isProduct="$isProduct" :isService="$isService"/>

  <livewire:scripts />

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

</body>
</html>
