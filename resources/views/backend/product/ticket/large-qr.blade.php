<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
{{-- <title>{{ optional($order->user)->name }}</title> --}}

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
  h1 {
    font-size: 60px;
  }
</style>

</head>
<body>

  @for ($i = 0; $i < 4; $i++)
    <table width="100%">
      <tr>
        <td align="center">
          <strong>
            <h1>{{ optional($product->size)->name }}</h1>
          </strong>
        </td>
      </tr>
    </table>
  @endfor 

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
Lagos de Moreno, Jal
ventas@sj-uniformes.com
47 47 42 30 00
            </pre>
        </td>
    </tr>

  </table>

    <table width="100%" class="mt-2">
      @if($product->code)
        <tr>
            <td align="center">
                <img src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(100)->generate(route('frontend.track.show', $product->code))) }} "/>
            </td>
        </tr>
     @endif
    <tr>
        <td align="center">
            <pre>
{!! $product->full_name !!}
            </pre>
        </td>
    </tr>

    </table>

  @for ($i = 0; $i < 4; $i++)
    <table width="100%">
      <tr>
        <td align="center">
          <strong>
            <h1>{{ optional($product->size)->name }}</h1>
          </strong>
        </td>
      </tr>
    </table>
  @endfor 

</body>
</html>