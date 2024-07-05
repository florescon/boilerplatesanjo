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

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Karla', sans-serif !important;
    }
  </style>

  <style type="text/css">
    * {margin: 0; padding: 0; list-style: none;}
    ul li {
      margin-left: 15px;
      position: relative;
      padding-left: 5px;
    }
    ul li::before {
      content: " ";
      position: absolute;
      width: 1px;
      background-color: #000;
      top: 5px;
      bottom: -12px;
      left: -10px;
    }
    body > ul > li:first-child::before {top: 12px;}
    ul li:not(:first-child):last-child::before {display: none;}
    ul li:only-child::before {
      display: list-item;
      content: " ";
      position: absolute;
      width: 1px;
      background-color: #000;
      top: 5px;
      bottom: 7px;
      height: 7px;
      left: -10px;
    }
    ul li::after {
      content: " ";
      position: absolute;
      left: -10px;
      width: 10px;
      height: 1px;
      background-color: #000;
      top: 12px;
    }
  </style>
  
  <livewire:styles />

</head>

<body>

  <livewire:backend.order.report :order="$order"/>

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
