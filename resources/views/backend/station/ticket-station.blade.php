<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($station->user)->name }}</title>

        <style type="text/css">
            * {
                font-family: Verdana, Arial, sans-serif;
            }
            body {
              font-family: "Times New Roman", serif;
              margin: -4mm -7mm 0mm -7mm;
            }
            table {
                font-size: medium;
            }
            tfoot tr td {
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
            <td align="center">
                <h3>{{ ucfirst(optional($station->status)->name) }}</h3>
            </td>

          </tr>
        </table>

        @if($station->date_entered)
            <table width="100%">
                <tr>
                    <td align="left">
                        <strong>Fecha:</strong> 
                        {{ $station->date_entered }}
                    </td>
                </tr>
            </table>
        @endif

        @if($station->order_id)
            <table width="100%">
                <tr>
                    <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{!! optional($station->order)->folio_or_id !!}</u></td>
                    @if($station->hasBatch() && $station->station_id)
                        <td><strong>@lang('Batch'):</strong><u style="font-size: 140%;"> #{{ $station->station_id }}</u></td>
                    @endif
                    @if($station->hasBatch() && !$station->station_id)
                        <td><strong>@lang('Batch'):</strong><u style="font-size: 140%;"> #{{ $station->id }}</u></td>
                    @endif
                    @if(!$station->hasBatch())
                        <td><strong>@lang('Folio'):</strong><u style="font-size: 140%;"> #{{ $station->id }}</u></td>
                    @endif
                </tr>
            </table>
        @endif

        <table style="margin-bottom: 25px; text-align: center;" width="100%">
            <tr>
                @if($station->personal)
                    <td><strong>Asignado a:</strong> {{ optional($station->personal)->name }}</td>
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
                    @if($station->user)
                        <th class="text-center"> <h2>{{ optional($station->user)->real_name }}</h2></th>
                    @endif
                </tr>
            </table>

            @if($station->comment)
                <table style="margin-bottom: 10px;" width="100%">
                    <tr align="center">
                        <th>
                            @if($station->comment)
                                {{ $station->info_customer ?? '' }}
                                <br>
                            @endif
                            {{ $station->comment ?? '' }}
                        </th>
                    </tr>
                </table>
            @endif
        @endif

        @if($station->total_products_station > 0)
            <table width="100%" style='margin-top: 10px; font-family:"Courier New", Courier, monospace; font-size:97%'>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Asignado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($station->product_station->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $assign)
                  <tr>
                    <td scope="row">
                        {!! $assign->product->full_name !!} {{ ' ['.$assign->product->code_subproduct_clear.']' }}
                        <br>
                        <strong>{{ $assign->comment }}</strong>
                    </td>
                    <td align="center">{{ $assign->quantity }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right">Total</td>
                        <td align="center" style="background-color: #000; color: white;">
                            <strong>{{ $station->total_products_station }}</strong>
                        </td>
                    </tr>
                </tfoot>            
            </table>
            <br>
        @endif

        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                @if($station->comment)
                <tr align="center">
                    <th>{{ $station->comment ?? '' }}
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