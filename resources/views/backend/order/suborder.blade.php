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
              <p class="card-text mb-0">Margarito Gonzalez Rubio #822</p>
              <p class="card-text mb-0">Col. El Refugio, Lagos de Moreno Jal.</p>
              <p class="card-text mb-0">ventas@sj-uniformes.com </p>
              <p class="card-text mb-0">47 47 42 30 00 </p>
            </div>
            <div class="mt-md-0 mt-2">
              <h4 class="invoice-title">
                <p class="text-uppercase">
                  @lang('Order')
                  <span class="invoice-number">#{{ $model->characters_type_order }}{{ $model->id }}</span>
                </p>
              </h4>
              <div class="invoice-date-wrapper">
                <p class="invoice-date-title">@lang('Date'):</p>

                <x-input.input-alpine nameData="isDate" :inputText="$isDate" :originalInput="$isDate" wireSubmit="savedate" modelName="date_entered" inputType="date" className=""/>

              </div>
              <div class="invoice-date-wrapper">
                <p class="invoice-date-title">@lang('Date Issued'):</p>
                <p class="invoice-date">{{ $model->date_for_humans }}</p>
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
              <h6 class="mb-25">{{ optional($model->departament)->name }}</h6>
            </div>

          </div>
        </div>
        <!-- Address and Contact ends -->

        <!-- Invoice Description starts -->
        @php($total = 0)
        @if($model->product_suborder->count())
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="py-1">@lang('Product')</th>
                  <th class="py-1">@lang('Quantity')</th>
                  <th class="py-1">@lang('Price')</th>
                  <th class="py-1">Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($model->product_suborder as $product)
                  <tr class="{{ $loop->last ? 'border-bottom' : '' }}">
                    {{-- @json($product) --}}
                    <td class="py-1">
                      <p class="card-text font-weight-bold mb-25">{{ $product->product->only_name}}</p>
                      <p class="card-text text-nowrap">
                        {!! $product->product->only_parameters !!}
                      </p>
                    </td>
                    <td class="py-1">
                      <span class="font-weight-bold">{{ $product->quantity}}</span>
                    </td>
                    <td class="py-1">
                      <span class="font-weight-bold">${{ priceWithoutIvaIncluded($product->price) }}</span>
                    </td>
                    <td class="py-1">
                      <span class="font-weight-bold">${{ priceWithoutIvaIncluded($totalprod = $product->price * $product->quantity) }}</span>
                    </td>
                  </tr>
                  @php($total += $totalprod)
                @endforeach
              </tbody>
              <tbody>
                <tr>
                  <td></td>
                  <th class="table-warning">{{ $model->total_products_suborder }}</th>
                  <th class="table-warning">Subtotal</th>
                  <td class="table-warning">${{ $total ? priceWithoutIvaIncluded($total) : '' }}</td>
                </tr>
                @if($model->id >= 530)
                  <tr>
                    <td></td>
                    <td></td>
                    <th class="table-warning">IVA</th>
                    <td class="table-warning">${{ ivaPrice($total) }}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <th class="table-warning">Total</th>
                    <td class="table-warning">${{ number_format($total, 2) }}</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        @endif

        <div class="card-body invoice-padding pb-0">
          <div class="row invoice-sales-total-wrapper">
            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
              @if($model->audi_id)
              <p class="card-text mb-0">
                <span class="font-weight-bold">Expedido por:</span> <span class="ml-75">{{ optional($model->audi)->name }}</span>
              </p>
              @endif
            </div>
            {{-- <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
              <div class="invoice-total-wrapper">
                <div class="invoice-total-item">
                  <p class="invoice-total-title">Total:</p>
                  <p class="invoice-total-amount">${{ $total ? number_format((float)$total, 2) : '' }}</p>
                </div>
                <hr class="my-50" />
              </div>
            </div> --}}
          </div>
        </div>
        <!-- Invoice Description ends -->

        <hr class="invoice-spacing" />

        <!-- Invoice Note starts -->
        <div class="card-body invoice-padding pt-0">
          <div class="row">
            <div class="col-3">
              <x-utils.delete-button :text="__('Delete suborder')" :href="route('admin.order.destroy', $order_id)" />
            </div>
            <div class="col-9">
              <span class="font-weight-bold">Nota:</span>
              <span
                >Fue un placer atenderte</span
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
          {{-- <a href="{{ route('admin.order.sub', $model->parent_order_id) }}" style="background-color:purple;" class="btn btn-block mb-75 text-white">
           @lang('Go to suborders')
          </a> --}}
          <a class="btn btn-secondary btn-block mb-75" href="{{ route('admin.order.print', $model->id) }}" target="_blank">
            <i class="cil-print"></i>
            @lang('Print')
          </a>
          <a class="btn btn-success btn-block" href="{{ route('admin.order.ticket', $model->id) }}" target="_blank">
            Ticket
          </a>
        </div>
      </div>

      @if($model->slug)
        <div class="card">
          <div class="card-body text-center">
            <h5> @lang('Tracking number'): </h5>
            <h5 class="text-primary"> {{ $model->slug }} </h5>
              <a href="{{ route('frontend.track.show', $model->slug) }}" target=”_blank”>
                <span class="badge badge-primary"> 
                  @lang('Go to track')
                  <i class="cil-external-link"></i>
                </span>
              </a>
          </div>
        </div>
      @endif

      <div class="card">
        <div class="card-body text-center">
          <h5> @lang('Request number'): </h5>
          <livewire:backend.components.edit-field :model="'\App\Models\Order'" :entity="$model" :field="'request'" :key="'requests'.$model->id"/>
          <br>
          <h5> @lang('Purchase order'): </h5>
          <livewire:backend.components.edit-field :model="'\App\Models\Order'" :entity="$model" :field="'purchase'" :key="'purchases'.$model->id"/>
          <br>
          <br>
          <h5> @lang('Internal comment'): </h5>
          <livewire:backend.components.edit-field :model="'\App\Models\Order'" :entity="$model" :field="'comment'" :key="'comments'.$model->id"/>
        </div>
      </div>

      <div class="card">
        @if($model->user_id || $model->departament_id)
          <div class="card-footer text-center">
            <div class="row">
              <div class="col-6 col-lg-6">
                <p><strong>Total: </strong> ${{ number_format((float)$model->total_sale_and_order, 2) }}</p>
                <p><strong>@lang('Payment'):</strong> {!! $model->payment_label !!} ${{  number_format((float)$model->total_payments, 2) }}</p>
                @if($model->total_payments_remaining > 0)
                  <p><strong>@lang('Remaining'):</strong> ${{ number_format((float)$model->total_payments_remaining, 2)  }}</p>
                  <h5 class="mt-2"><a href="#!" data-toggle="modal" wire:click="$emitTo('backend.order.create-payment', 'createmodal', {{ $order_id }})" data-target="#createPayment" style="color: #ee2e31;">Crear pago</a></h5>
                @endif
                <br>
                <a href="{{ route('admin.order.records_payment', $order_id) }}" class="card-link">@lang('View payment records')</a>
              </div>
              <div class="col-6 col-lg-6">
                <strong>@lang('Delivery'):</strong> {{ $last_order_delivery_formatted ?? __('Pending') }}
                <select class="form-control text-center mt-2" style="border: 1px solid #fe8a71" wire:model.debounce.800ms="order_status_delivery">
                  <option value="" hidden>@lang('Select order delivery status')</option>
                  @foreach($OrderStatusDelivery as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <br>
                <a href="{{ route('admin.order.records_delivery', $order_id) }}" class="card-link">@lang('View delivery records')</a>
              </div>
            </div>
          </div>
        @endif
      </div>

      <div class="card">
        <div class="card-body text-center">
          {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(route('frontend.track.show', $model->slug)); !!}
          <p class="mt-4">@lang('Scan me for go track')</p>
        </div>
      </div>

    </div>
    <!-- /Invoice Actions -->

  </div>
</section>
<livewire:backend.order.create-payment />
<livewire:backend.order.add-service />

@push('after-scripts')
    <script type="text/javascript">
      $(document).ready((function(){$(".btn-print").click((function(){window.print()}))}));
    </script>

    {{-- <script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/vendors.min.js"></script>
    <script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/vendors/js/ui/prism.min.js"></script>

    <script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/core/app-menu.js"></script>
    <script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/core/app.js"></script>
    <script src="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template/demo-1/js/scripts/customizer.js"></script> --}}


    <script type="text/javascript">
      Livewire.on("paymentStore", () => {
          $("#createPayment").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("serviceStore", () => {
          $("#addService").modal("hide");
      });
    </script>
@endpush