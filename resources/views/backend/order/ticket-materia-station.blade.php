<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Ticket</title>

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
            <h4>@lang('Bom of Materials')</h4>
        </td>
    </tr>
  </table>

  @if($station->order_id)
      <table width="100%">
          <tr>
              <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{!! optional($station->order)->folio_or_id !!}</u></td>
              <td><strong>@lang('Batch'):</strong><u style="font-size: 140%;"> #{{ $station->id }}</u></td>
          </tr>
      </table>
  @endif

    <table width="100%">
      <thead style="background-color: gray;">
        <tr align="center">
          <th>@lang('Concept')</th>
          <th>@lang('Quantity')</th>
        </tr>
      </thead>
      <tbody>
        @foreach($station->product_station as $product)
        <tr>
          <td scope="row">{!! $product->product->full_name !!}</tf>
          <td align="center">{{ $product->quantity }}</td>
        </tr>
        @endforeach
      </tbody>

      <tfoot>
          <tr>
              <td align="right"></td>
              <td align="center" class="gray">
                <strong>
                  {{ $station->total_products_station }}
                </strong>
              </td>
          </tr>
      </tfoot>
    </table>

  <br>

  <table width="100%">
    <thead style="background-color: gray; color:white;" >
      <tr align="center">
        <th>@lang('Quantity')</th>
        <th>@lang('Feedstock')</th>
      </tr>
    </thead>
    <tbody>
      @foreach($allMaterials as $key => $material)
      <tr>
        <td align="center">{{ $material['quantity'] .' - '.$material['unit_measurement'] }}</td>
        <td scope="row">
            {{ $material['part_number'] }}
            <br>
            <strong>{{  $material['material_name'] }} </strong>
        </td>
      </tr>
      @endforeach
    </tbody>
    </table>

    <table width="100%" style="margin-top:30px;">
        <thead style="background-color: white;">

            <tr align="right">
                <td> <em style="font-size: 80%;">{{ printed() }}</em></td>
            </tr>
        </thead>
    </table>

</body>
</html>