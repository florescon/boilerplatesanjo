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
              <img src="{{ public_path('img/logo22.png') }}" alt="" width="100"/>
            </td>
          </tr>
            <tr>
                <td align="center">
                    <h3>San Jose Uniformes</h3>
                    <pre>
sjuniformes.com
Margarito Gonzalez Rubio #857
Col. El Refugio, Lagos de Moreno Jal.
ventas@sj-uniformes.com
47 47 42 30 00
                    </pre>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
              <td align="left"><strong>Fecha generado:</strong> {{ $order->created_at }}</td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                @if($order->payment)
                <td><strong>Método pago:</strong> </td>
                @endif
                <td><strong>Folio:</strong> #{{ $order->id }}</td>
            </tr>
        </table>

        <table style="margin-bottom: 10px;" width="100%">
            <tr>
                @if($order->user || $order->departament)
                    <td><strong>A:</strong> {{ $order->user_name }}</td>
                @endif
                <td><strong>Expedido por:</strong> {{ optional($order->audi)->name }} </td>
            </tr>
        </table>

        @if(count($order->product_order))
            <table width="100%">
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->product_order as $product)
                  <tr>
                    <td scope="row">{!! $product->product->full_name !!}</tf>
                    <td align="center">{{ $product->quantity }}</td>
                    <td align="right">${{ $product->price }}</td>
                    <td align="right">${{ number_format((float)$product->total_by_product, 2) }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right"></td>
                        <td align="center" class="gray"><strong>{{ $order->total_products }}</strong></td>
                        <td align="right">Total </td>
                        <td align="right" class="gray">${{ number_format((float)$order->total_order, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endif

        @if(count($order->product_sale))
            <table width="100%">
                <thead style="background-color: red;">
                  <tr align="center">
                    <th colspan="4">@lang('Sale')</th>
                  </tr>
                </thead>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->product_sale as $product)
                  <tr>
                    <td scope="row">{!! $product->product->full_name !!}</tf>
                    <td align="center">{{ $product->quantity }}</td>
                    <td align="right">${{ $product->price }}</td>
                    <td align="right">${{ number_format((float)$product->total_by_product, 2) }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right"></td>
                        <td align="center" class="gray"><strong>{{ $order->total_products_sale }}</strong></td>
                        <td align="right">Total </td>
                        <td align="right" class="gray">${{ number_format((float)$order->total_sale, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endif

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

    </body>
</html>