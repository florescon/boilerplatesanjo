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
sanjoseuniformes.com
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

  <table width="100%">
    <tr>
        @if($order->departament)
            <td>
                <strong>A:</strong> {{ optional($order->departament)->name }}
            </td>
        @endif
        <td><strong>Expedido por:</strong> {{ optional($order->audi)->name }} </td>
    </tr>
  </table>


  <table width="100%">
    <tr>
        <td>Ticket text</td>
    </tr>
  </table>

  <br/>



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
      @foreach($order->product_suborder as $product)
      <tr>
        <td scope="row">{!! $product->parent_order->product->full_name !!}</tf>
        <td align="center">{{ $product->quantity }}</td>
        <td align="right">${{ $product->parent_order->price }}</td>
        <td align="right">${{ $product->parent_order->price * $product->quantity }}</td>
      </tr>
      @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td align="right"></td>
            <td align="center" class="gray"><strong>{{ $order->total_products_suborder }}</strong></td>
            <td align="right">Total </td>
            <td align="right" class="gray">${{ $order->total_suborder }}</td>
        </tr>
    </tfoot>
  </table>

    <br>
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