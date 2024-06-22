<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ !$visibleOrder ? __('Tackle and more') : __('Feedstock') .' '. optional($order->user)->name }}</title>

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
            <h3>{{ appName() }}</h3>
            <h4>@lang('Feedstock')</h4>
        </td>
    </tr>

  </table>

  <table width="100%">
    <tr>
      <td align="left"><strong>Fecha generado:</strong> {{ $order->created_at->isoFormat('D, MMM h:mm:ss a') }}</td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        @if($order->payment)
        <td><strong>@lang('Payment method'):</strong> </td>
        @endif
        <td><strong>@lang('Order'):</strong> <u style="font-size: 140%;">#{{ $order->id }}</u></td>
    </tr>
  </table>

  <table style="margin-bottom: 10px;" width="100%">
    <tr>
        @if($order->user)
        <td><strong>A:</strong> {{ optional($order->user)->name }}</td>
        @endif
        <td><strong>Expedido por:</strong> {{ optional($order->audi)->name }}</td>
    </tr>
  </table>


  @if($order->comment)
      <table style="margin-bottom: 10px;" width="100%">
          <tr align="center">
              <th>{{ $order->comment ?? '' }}
                  <br><br>
              </th>
          </tr>
      </table>
  @endif


  @if($visibleOrder)
    <table width="100%">
      <thead style="background-color: gray;">
        <tr align="center">
          <th>@lang('Concept')</th>
          <th>@lang('Quantity')</th>
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
  @endif

  <br>

  <table width="100%">
    <thead style="background-color: gray; color:white;" >
      <tr align="center">
        <th>@lang('Feedstock')</th>
        <th>@lang('Quantity')</th>
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