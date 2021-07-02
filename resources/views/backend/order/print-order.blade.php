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
      <p class="mb-25">Office 149, 450 South Brand Brooklyn</p>
      <p class="mb-25">San Diego County, CA 91905, USA</p>
      <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>
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
      <p class="mb-25">{{ optional($order->user)->name }}</p>
      <p class="mb-25">Shelby Company Limited</p>
      <p class="mb-25">Small Heath, B10 0HF, UK</p>
      <p class="mb-25">718-986-6062</p>
      <p class="mb-0">peakyFBlinders@gmail.com</p>
    </div>
    <div class="col-sm-6 mt-sm-0 mt-2">
      <h6 class="mb-1">Payment Details:</h6>
      <table>
        <tbody>
          <tr>
            <td class="pr-1">Total Due:</td>
            <td><strong>$12,110.55</strong></td>
          </tr>
          <tr>
            <td class="pr-1">Bank name:</td>
            <td>American Bank</td>
          </tr>
          <tr>
            <td class="pr-1">Country:</td>
            <td>United States</td>
          </tr>
          <tr>
            <td class="pr-1">IBAN:</td>
            <td>ETD95476213874685</td>
          </tr>
          <tr>
            <td class="pr-1">SWIFT code:</td>
            <td>BR91905</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="table-responsive mt-2">
    <table class="table m-0">
      <thead>
        <tr>
          <th class="py-1 pl-4">Task description</th>
          <th class="py-1">Rate</th>
          <th class="py-1">Hours</th>
          <th class="py-1">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->product_suborder as $product)
        <tr>
          <td class="py-1 pl-4">
            <p class="font-weight-semibold mb-25">{{ $product->parent_order->product->parent->name}}</p>
            <p class="text-muted text-nowrap">
              Developed a full stack native app using React Native, Bootstrap & Python
            </p>
          </td>
          <td class="py-1">
            <strong>$60.00</strong>
          </td>
          <td class="py-1">
            <strong>30</strong>
          </td>
          <td class="py-1">
            <strong>$1,800.00</strong>
          </td>
        </tr>
        @endforeach
        <tr class="border-bottom">
          <td class="py-1 pl-4">
            <p class="font-weight-semibold mb-25">Ui Kit Design</p>
            <p class="text-muted text-nowrap">Designed a UI kit for native app using Sketch, Figma & Adobe XD</p>
          </td>
          <td class="py-1">
            <strong>$60.00</strong>
          </td>
          <td class="py-1">
            <strong>20</strong>
          </td>
          <td class="py-1">
            <strong>$1200.00</strong>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="row invoice-sales-total-wrapper mt-3">
    <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
      <p class="card-text mb-0">
        <span class="font-weight-bold">Salesperson:</span> <span class="ml-75">Alfie Solomons</span>
      </p>
      <br>
      <br>
      <p class="card-text mb-0">
        &nbsp;
        {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(Request::url()); !!}
      </p>
      <p>
        &nbsp;
        <em>
            Scan this code to track.

            <br>
        &nbsp;
            (Available 1 month)
        </em>
      </p>

    </div>
    <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
      <div class="invoice-total-wrapper">
        <div class="invoice-total-item">
          <p class="invoice-total-title">Total:</p>
          <p class="invoice-total-amount">$1690</p>
        </div>
        <hr class="my-50" />
      </div>
    </div>
  </div>

  <hr class="my-2" />

  <div class="row">
    <div class="col-12">
      <span class="font-weight-bold">Note:</span>
      <span
        >It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
        projects. Thank You!</span
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
