
<!DOCTYPE html>

<html lang="en" data-textdirection="ltr" class="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="7J5m62Fygv5OnkSlypTXoiz14KsfLfqYkiq0i0Ki">

  <title>@lang('Datasheet') {{ $shop->name }}</title>
  
  <link rel="stylesheet" href="{{ asset('/css_custom/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css_custom/vertical-menu.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css_custom/app-invoice-print.css') }}">
  </head>



<body class="vertical-layout vertical-menu-modern light"
    data-menu=" vertical-menu-modern" data-layout="" style="" data-framework="laravel" 
    {{-- data-asset-path="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/" --}}
    >

  <!-- BEGIN: Content-->
  <div class="app-content content ">
    <div class="content-wrapper ">
      <div class="content-body">


        <div class="invoice-print p-3">
          <div class="d-flex justify-content-between flex-md-row flex-column pb-2">
            <div>
                <div class="d-flex mb-1">
                  <img class="" src="{{ asset('img/logo22.png') }}" height="50" >
                  <h1 class="pt-md-1 text-primary font-weight-bold ml-1 ">
                    <strong>
                      {{ __(appName()) }}
                    </strong>
                  </h1>
                </div>
                <h2 class="mb-25">
                  <strong>
                    @lang('Product specification & technical data')
                  </strong>
                </h2>
            </div>
          </div>

        <div class="row">
            <div class="col-4">

                <p class="text-left">
                  <strong>
                    {{ $shop->code }} - {{ $shop->name }}
                  </strong>
                </p>
                <p class="text-left">
                  {{ optional($shop)->description }}            
                </p>

                <hr class="my-2" />

                <h3>
                  <strong>
                    @lang('Product information')
                  </strong>
                </h3>     
                <p class="text-left">
                  {!! optional($shop->advanced)->information !!}
                </p>

                <hr class="my-2" />

                <h3>
                  <strong>
                    @lang('Standards')
                  </strong>
                </h3>     
                <p class="text-left">
                  {!! optional($shop->advanced)->standards !!}
                </p>
            </div>
            <div class="col-4">
                <div>
                  <img src="{{ asset('/storage/' . $shop->file_name) }}" onerror="this.onerror=null;this.src='/img/ga/not0.png';" width="300" />
                </div>

                <br>
                <p class="text-left">
                  {!! optional($shop->advanced)->dimensions !!}
                </p>

                <p class="text-left">
                  {!! optional($shop->advanced)->extra !!}
                </p>

            </div>

            <div class="col-4">
                <p class="text-center">
                  {!! QrCode::size(100)->generate(route('frontend.shop.show', $shop->slug)); !!}
                </p>
                <p class="text-left">
                  {!! optional($shop->advanced)->description !!}
                </p>
            </div>
        </div>


        <hr class="my-2" />

        <div class="row">
          <div class="col-12">
            <span class="font-weight-bold">Note:</span>
            <span
            >
            PW306 - Pantalón elástico de alta visibilidad PW3 con bolsillos de pistolera
            Código de producto: 6203431100
            </span
            >
          </div>
        </div>
      </div>



    </div>
  </div>
</div>
  <!-- End: Content-->
            <footer class="text-center">
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>

  
  <script src="{{ asset('/js_custom/vendor.min.js') }}"></script>
  <script src="{{ asset('/js_custom/app-invoice-print.js') }}"></script>

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
