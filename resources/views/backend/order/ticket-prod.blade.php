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
      <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
    </td>
  </tr>
    <tr>
        <td align="center">
            <h3>{{ appName() }}</h3>
            <h3>{{ ucfirst(optional($station->status)->name) }}</h3>
        </td>
    </tr>
  </table>

  @if($station->order_id)
      <table width="100%">
          <tr>
              <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{!! optional($station->order)->folio_or_id !!}</u></td>
              <td><strong>@lang('Batch'):</strong><u style="font-size: 140%;"> #{{ $station->folio ?? $station->id }}</u></td>
          </tr>
      </table>
  @endif

  <table style="margin-bottom: 25px; text-align: center;" width="100%">
      <tr>
          @if($station->date_entered)
              <td><strong>Fecha:</strong> {{ $station->date_entered->format('d-m-Y') }}</td>
          @endif
      </tr>
      <tr>
          @if($station->personal)
              <td><strong>Asignado a:</strong> {{ ucwords(strtolower(optional($station->personal)->name)) }}</td>
          @endif
      </tr>
      <tr>
          @if($station->audi)
              <td><strong>Expedido por:</strong> {{ optional($station->audi)->name }} </td>
          @endif
      </tr>
  </table>

    <table width="100%">
      <thead style="background-color: gray;">
        <tr align="center">
          <th>@lang('Concept')</th>
          <th>@lang('Quantity')</th>
        </tr>
      </thead>
      <tbody>
        @foreach($station->items->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
        <tr>
          <td scope="row">{!! $product->product->full_name !!}</tf>
          <td align="center">{{ $product->input_quantity }}</td>
        </tr>
        @endforeach
      </tbody>

      <tfoot>
          <tr>
              <td align="right"></td>
              <td align="center" class="gray">
                <strong>
                  {{ $station->total_products_prod }}
                </strong>
              </td>
          </tr>
      </tfoot>
    </table>

  <br>

    <table width="100%" style="margin-top:30px;">
        <thead style="background-color: white;">

            <tr align="right">
                <td> <em style="font-size: 80%;">{{ printed() }}</em></td>
            </tr>
        </thead>
    </table>

</body>
</html>