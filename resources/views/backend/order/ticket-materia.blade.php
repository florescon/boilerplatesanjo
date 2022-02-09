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
            <h4>Materia prima</h4>
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
        @if($order->user)
        <td><strong>A:</strong> {{ optional($order->user)->name }}</td>
        @endif
        <td><strong>Expedido por:</strong> {{ optional($order->audi)->name }}</td>
    </tr>
  </table>


  <table width="100%">
    <tr>
        <td>{{ $order->comment }}</td>
    </tr>
  </table>

  <br/>



  <table width="100%">
    <thead style="background-color: gray;">
      <tr align="center">
        <th>Concepto</th>
        <th>Cantidad</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->product_order as $product)
      <tr>
        <td scope="row">{!! $product->product->full_name !!}</tf>
        <td align="center">{{ $product->quantity }}</td>
      </tr>
      @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td align="right"></td>
            <td align="center" class="gray"><strong>{{ $order->total_products }}</strong></td>
        </tr>
    </tfoot>
  </table>

  <br>

  <table width="100%">
    <thead style="background-color: gray; color:white;" >
      <tr align="center">
        <th>Materia Prima</th>
        <th>Cantidad</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->materials_order as $materia)
      <tr>
        <td scope="row">{!! $materia->material->full_name !!}</tf>
        <td align="center">{{ rtrim(rtrim(sprintf('%.8F', $materia->sum), '0'), ".") }}</td>

      </tr>
      @endforeach
    </tbody>
  </table>

    <br>

</body>
</html>