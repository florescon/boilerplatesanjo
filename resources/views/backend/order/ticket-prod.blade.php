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
            @if($station->service_type_id)
              <h3>{{ optional($station->service_type)->name }}</h3>
            @endif
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

  @if($station->status_id !== 6)
      <table style="margin-bottom: -30px; margin-top: -20px;" width="100%">
          <tr>
              @if($station->order->user)
                  <th class="text-center"> <h2>{{ optional($station->order->user)->real_name }}</h2></th>
              @endif
          </tr>
      </table>

      @if($station->order->comment)
          <table style="margin-bottom: 10px;" width="100%">
              <tr align="center">
                  <th>
                      @if($station->order->comment)
                          {{ $station->order->info_customer ?? '' }}
                          <br>
                      @endif
                      {{ $station->order->comment ?? '' }}
                  </th>
              </tr>
          </table>
      @endif

      @if($station->order->complementary)
          <table style="margin-bottom: 10px;" width="100%">
              <tr align="center">
                  <th>
                      {{ $station->order->complementary ?? '' }}
                  </th>
              </tr>
          </table>
      @endif

  @endif

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

            @if($station->notes)
            <tr align="center">
                <th>{{ $station->notes ?? '' }}
                    <br><br><br>
                </th>
            </tr>
            @endif

            <tr align="center">
                <th>__________________________________</th>
            </tr>

        </thead>
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