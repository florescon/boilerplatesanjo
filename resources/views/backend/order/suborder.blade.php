<section class="invoice-preview-wrapper">
  <div class="row invoice-preview">
<!-- Invoice -->
    <div class="col-xl-9 col-md-8 col-12 ">
      <div class="card invoice-preview-card border-0">
        <div class="card-body invoice-padding pb-0">
          <!-- Header starts -->
          <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
            <div>
              <div class="logo-wrapper">
                <img src="{{ asset('img/logo22.png') }}" width="90" alt="CoreUI Logo">
                <h3 class="text-primary invoice-logo">{{ __(appName()) }}</h3>
              </div>
              <p class="card-text mb-25">Office 149, 450 South Brand Brooklyn</p>
              <p class="card-text mb-25">San Diego County, CA 91905, USA</p>
              <p class="card-text mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>
            </div>
            <div class="mt-md-0 mt-2">
              <h4 class="invoice-title">
                <p class="text-uppercase">
                  @lang('Order')
                  <span class="invoice-number">#{{ $order->id }}</span>
                </p>
              </h4>
              <div class="invoice-date-wrapper">
                <p class="invoice-date-title">@lang('Date Issued'):</p>
                <p class="invoice-date">{{ $order->date_for_humans }}</p>
              </div>
            </div>
          </div>
          <!-- Header ends -->
        </div>

        <hr class="invoice-spacing" />

        <!-- Address and Contact starts -->
        <div class="card-body invoice-padding pt-0">
          <div class="row invoice-spacing">
            <div class="col-xl-8 p-0">
              <h6 class="mb-2">@lang('Order To'):</h6>
              <h6 class="mb-25">{{ optional($order->user)->name }}</h6>
              <p class="card-text mb-25">Shelby Company Limited</p>
              <p class="card-text mb-25">Small Heath, B10 0HF, UK</p>
              <p class="card-text mb-25">718-986-6062</p>
              <p class="card-text mb-0">peakyFBlinders@gmail.com</p>
            </div>
            <div class="col-xl-4 p-0 mt-xl-0 mt-2">
              <h6 class="mb-2">Payment Details:</h6>
              <table>
                <tbody>
                  <tr>
                    <td class="pr-1">Total Due:</td>
                    <td><span class="font-weight-bold">$12,110.55</span></td>
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
        </div>
        <!-- Address and Contact ends -->

        <!-- Invoice Description starts -->
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="py-1">@lang('Product')</th>
                <th class="py-1">@lang('Price')</th>
                <th class="py-1">@lang('Quantity')</th>
                <th class="py-1">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->product_suborder as $product)
              <tr class="{{ $loop->last ? 'border-bottom' : '' }}">
                {{-- @json($product) --}}
                <td class="py-1">
                  <p class="card-text font-weight-bold mb-25">{{ $product->parent_order->product->parent->name}}</p>
                  <p class="card-text text-nowrap">
                    {{ $product->parent_order->product->color->name. '  '.$product->parent_order->product->size->name }}
                  </p>
                </td>
                <td class="py-1">
                  <span class="font-weight-bold">${{ $product->parent_order->price }}</span>
                </td>
                <td class="py-1">
                  <span class="font-weight-bold">{{ $product->quantity}}</span>
                </td>
                <td class="py-1">
                  <span class="font-weight-bold">${{ number_format($product->parent_order->price * $product->quantity, 2, ".", ",") }}</span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="card-body invoice-padding pb-0">
          <div class="row invoice-sales-total-wrapper">
            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
              <p class="card-text mb-0">
                <span class="font-weight-bold">Salesperson:</span> <span class="ml-75">Alfie Solomons</span>
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
        </div>
        <!-- Invoice Description ends -->

        <hr class="invoice-spacing" />

        <!-- Invoice Note starts -->
        <div class="card-body invoice-padding pt-0">
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
        <!-- Invoice Note ends -->
      </div>
    </div>
    <!-- /Invoice -->

    <!-- Invoice Actions -->
    <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
      <div class="card">
        <div class="card-body">
          <a href="{{ route('admin.order.edit', $order->parent_order_id) }}" class="btn btn-primary btn-block mb-75" >
           @lang('Go to main order')
          </a>
          <a class="btn btn-secondary btn-block mb-75" href="{{ route('admin.order.print', $order->id) }}" target="_blank">
            <i class="cil-print"></i>
            @lang('Print')
          </a>
          <a class="btn btn-success btn-block" href="{{ route('admin.order.ticket', $order->id) }}" target="_blank">
            Ticket
          </a>
        </div>
      </div>

      <div class="card">
        <div class="card-body text-center">
          {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(Request::url()); !!}
        </div>
      </div>

    </div>
    <!-- /Invoice Actions -->

  </div>
</section>



@push('after-styles')
  <script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/vendors.min.js"></script>
<script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/ui/prism.min.js"></script>

<script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/core/app-menu.js"></script>
<script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/core/app.js"></script>
<script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/scripts/customizer.js"></script>


<script type="text/javascript">
  $(document).ready((function(){$(".btn-print").click((function(){window.print()}))}));
</script>

@endpush