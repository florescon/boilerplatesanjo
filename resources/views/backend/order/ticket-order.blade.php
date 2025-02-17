<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($order->user)->name }}</title>

        <style type="text/css">
            * {
                font-family: Verdana, Arial, sans-serif;
            }
            table{
                font-size: medium;
            }
            tfoot tr td{
                font-weight: bold;
                font-size: medium;
            }
            .gray {
                background-color: lightgray
            }
        </style>
    </head>
    <body>
      <table width="100%">
          <tr>
            <td style="text-align: center;">
              <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
            </td>
          </tr>
            <tr>
                <td align="center">
                    <h3>San Jose Uniformes</h3>
                    <pre>
{{ Request::server ("SERVER_NAME") }}
{{ setting('site_address') }}
{{ setting('site_email') }}
{{ setting('site_whatsapp') }}
                    </pre>
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top: -30px;">
            <tr>
              <td align="left"><strong>F. Generado:</strong> {{ $order->created_at->isoFormat('D, MMM YY - h:mm a') }}</td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                @if($order->payment)
                <td><strong>Método pago:</strong> </td>
                @endif
                <td>
                    <strong>
                    @if(!$order->isOutputProducts())        
                        @lang('Order'):
                    @else
                        @lang('Output'):
                    @endif
                    </strong> #{!! $order->folio_or_id !!}
                </td>
            </tr>
        </table>

        @if($order->comment)
            <table style="margin-bottom: 10px; margin-top: 20px;" width="100%">
                <tr align="center">
                    <th>
                        @if($order->comment)
                            {{ $order->info_customer ?? '' }}
                            <br>
                        @endif
                        {{ $order->comment ?? '' }}
                    </th>
                </tr>
            </table>
        @endif

        @if($order->observation)
            <table style="margin-bottom: 10px;" width="100%">
                <tr align="center">
                    <th>
                        {{ $order->observation ?? '' }}
                    </th>
                </tr>
            </table>
        @endif


        <table style="margin-bottom: 10px; text-align: center;" width="100%">
            <tr>
                @if($order->user || $order->departament)
                    <td><strong>Asignado a:</strong> {{ $order->user_name }}</td>
                @endif
            </tr>
            <tr>
                <td><strong>Expedido por:</strong> {{ optional($order->audi)->name }} </td>
            </tr>
        </table>

        @if(count($order->products) && !count($order->product_output))
            <table width="100%">
                <thead style="background-color: brown; color: white;">
                  <tr align="center">
                    <th colspan="{{ $emptyPrices ? '2' : '4' }}">{{ $order->type_order_clear }}</th>
                  </tr>
                </thead>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    @if(!$emptyPrices)
                        <th>Precio</th>
                        <th>Total</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                  <tr>
                    <td scope="row">{!! $product->product->full_name !!}</td>
                    <td align="center">{{ $product->quantity }}</td>
                    @if(!$emptyPrices)
                        <td align="right">${{ !$breakdown ? priceWithoutIvaIncluded($product->price) : $product->price }}</td>
                        <td align="right">${{ !$breakdown ? priceWithoutIvaIncluded($product->total_by_product) : $product->total_by_product }}</td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    @if(!$breakdown)
                        <tr>
                            <td align="right" colspan="3">Subtotal </td>
                            <td align="right" class="gray">${{ count($order->product_suborder) ? $total_by_all : number_format($order->subtotal_by_all, 2)  }}</td>
                        </tr>
                    @endif
                    @if($order->discount && !$emptyPrices)
                        <tr>
                            <td align="right" colspan="3">@lang('Disc.') </td>
                            <td align="right" class="gray">
                                @if(!$breakdown)
                                    ${{ number_format($order->calculate_discount_all, 2)}}
                                @else
                                    {{ $order->discount }}%
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if(!$breakdown)
                        <tr>
                            <td align="right" colspan="3">IVA </td>
                            <td align="right" class="gray">${{ calculateIva($order->subtotal_less_discount) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td align="right"></td>
                        <td align="center" class="gray"><strong>{{ $order->total_articles }}</strong></td>
                        @if(!$emptyPrices)
                            <td align="right">Total </td>
                            <td align="right" class="gray">${{ number_format(count($order->product_suborder) ? $total : $order->total_by_all_with_discount, 2) }}</td>
                        @endif
                    </tr>
                </tfoot>
            </table>
            <br>
        @endif

       @if(count($order->product_output))
            <table width="100%">
                <thead style="background-color: coral;">
                  <tr align="center">
                    <th colspan="2">@lang('Output')</th>
                  </tr>
                </thead>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->product_output->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $product)
                  <tr>
                    <td scope="row">{!! $product->product->full_name !!}</td>
                    <td align="center">{{ $product->quantity }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right"> </td>
                        <td align="center" class="gray">{{ $order->total_products_output }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endif

        @if(!$order->isOutputProducts() && !$emptyPrices)
            <table width="100%">
                <tr>
                    <td align="center">
                        <img src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(100)->generate(route('frontend.track.show', $order->slug))) }} "/>
                    </td>
                    <td align="center">
                        <p>
                            <em>
                                @lang('Scan this code to track').
                                (@lang('Available') {{ setting('days_orders') }} @lang('days'))
                            </em>
                        </p>
                    </td>
                </tr>
            </table>
        @endif

      @if(!$emptyPrices)
          <table style="margin-top: 10px" width="100%">
              <tr>
                <td style="text-align: center;">
                    <em><strong>@lang('Note'):</strong> {{ setting('footer') ?? '--' }}</em>
                </td>
              </tr>
          </table>
      @endif
    </body>
</html>
