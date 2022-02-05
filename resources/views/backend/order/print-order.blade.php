<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="Xa6sgFEHGsvbkPoMtWp3EmjNN2FLvDS7GqyF27Bo">

  <title> @lang('Order') #{{ $order->id }}</title>

  {{-- <link rel="shortcut icon" type="image/x-icon" href="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/images/logo/favicon.ico"> --}}

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
        <img class="" src="{{ asset('img/logo22.png') }}" width="100" alt="CoreUI Logo">
        <h3 class="pt-md-2 text-primary font-weight-bold ml-1 ">{{ __(appName()) }}</h3>
      </div>
      <p class="card-text mb-0">Margarito Gonzalez Rubio #857</p>
      <p class="card-text mb-0">Col. El Refugio, Lagos de Moreno Jal.</p>
      <p class="card-text mb-0">ventas@sj-uniformes.com </p>
      <p class="card-text mb-0">47 47 42 30 00 </p>
    </div>
    <div class="mt-md-0 mt-2">
      <h4 class="font-weight-bold text-right mb-1">
        <p class="text-uppercase">
          @lang('Order') #{{ $order->id }}
        </p>
      </h4>
      <div class="invoice-date-wrapper mb-50">
        <span class="invoice-date-title">@lang('Date Issued'):</span>
        <span class="font-weight-bold"> {{ $order->date_for_humans }}</span>
      </div>
    </div>
  </div>

  <hr class="my-2" />

  <div class="row pb-2">
    <div class="col-sm-6">
      <h6 class="mb-1">@lang('Order To'):</h6>
      <p class="mb-25">{{ optional($order->departament)->name }}</p>
    </div>
  </div>

  <div class="table-responsive mt-2">
    <table class="table m-0">
      <thead>
        <tr>
          <th class="py-1">@lang('Product')</th>
          <th class="py-1">@lang('Price')</th>
          <th class="py-1">@lang('Quantity')</th>
          <th class="py-1">Total</th>
        </tr>
      </thead>
        <tbody>
          @php($total = 0)
          @foreach($order->product_suborder as $product)
          <tr class="{{ $loop->last ? 'border-bottom' : '' }}">
            {{-- @json($product) --}}
            <td class="py-1">
              <p class="card-text font-weight-bold mb-25">{{ $product->parent_order->product->only_name }}</p>
              <p class="card-text text-nowrap">
                {!! $product->parent_order->product->only_parameters !!}
              </p>
            </td>
            <td class="py-1">
              <span class="font-weight-bold">${{ $product->price ? $product->price : $product->parent_order->price }}</span>
            </td>
            <td class="py-1">
              <span class="font-weight-bold">{{ $product->quantity }}</span>
            </td>
            <td class="py-1">
              <span class="font-weight-bold">${{ number_format($totalprod = ($product->price ? $product->price : $product->parent_order->price) * $product->quantity, 2, ".", ",") }}</span>
            </td>
          </tr>
          @php($total += $totalprod)
          @endforeach
        </tbody>
    </table>
  </div>

  <div class="row invoice-sales-total-wrapper mt-3">
    <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
      @if($order->audi_id)
      <p class="card-text mb-0">
        <span class="font-weight-bold">Expedido por:</span> <span class="ml-75">{{ optional($order->audi)->name }}</span>
      </p>
      @endif
      <br>
      <br>
      <p class="card-text mb-0">
        &nbsp;
        {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(route('frontend.track.show', $order->slug)); !!}
      </p>
      <p>
        &nbsp;
        <em>
            Escanea para dar seguimiento.
            <br>
        &nbsp;
            (Disponible 1 mes)
        </em>
      </p>

    </div>
    <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
      <div class="invoice-total-wrapper">
        <div class="invoice-total-item">
          <p class="invoice-total-title">Total:</p>
          <p class="invoice-total-amount">${{ number_format((float)$total, 2) }}</p>
        </div>
        <hr class="my-50" />
      </div>
    </div>
  </div>

  <hr class="my-2" />

  <div class="row">
    <div class="col-12">
      <span class="font-weight-bold">Nota:</span>
      <span
        >Fue un placer atenderte</span
      >
    </div>
  </div>
</div>

      </div>
    </div>
  </div>
  <!-- End: Content-->

  
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
