<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($batch->user)->name }}</title>

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
                <h3>{{ optional($batch->status)->name }}</h3>
            </td>

          </tr>
        </table>

        @if($batch->date_entered)
            <table width="100%">
                <tr>
                    <td align="left">
                        <strong>Fecha:</strong> 
                        {{ $batch->date_entered->format('d-m-Y') }}
                    </td>
                </tr>
            </table>
        @endif

        @if($batch->order_id)
            <table width="100%">
                <tr>
                    <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{!! optional($batch->order)->folio_or_id !!}</u></td>
                    <td><strong>@lang('Batch'):</strong><u style="font-size: 140%;"> #{{ $batch->parent_or_id }}</u></td>
                </tr>
            </table>
        @endif

        <table style="margin-bottom: 10px;" width="100%">
            <tr>
                @if($batch->personal)
                    <td><strong>A:</strong> {{ optional($batch->personal)->name }}</td>
                @endif
                @if($batch->audi)
                    <td><strong>Expedido por:</strong> {{ optional($batch->audi)->name }} </td>
                @endif
            </tr>
        </table>


        @if($batch->status_id !== 6)
            <table style="margin-bottom: -30px; margin-top: -20px;" width="100%">
                <tr>
                    @if($order->personal)
                        <th class="text-center"> <h2>{{ optional($order->personal)->real_name }}</h2></th>
                    @endif
                </tr>
            </table>

            @if($order->comment)
                <table style="margin-bottom: 10px;" width="100%">
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
        @endif

        @if(count($batch->batch_product))
            <table width="100%" style='margin-top: 10px; font-family:"Courier New", Courier, monospace; font-size:97%'>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Asignado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($batch->batch_product->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']])  as $assign)
                  <tr>
                    <td scope="row">
                        {!! $assign->product->full_name !!}
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
                            <strong>{{ $batch->total_batch }}</strong>
                        </td>
                    </tr>
                </tfoot>            
            </table>
            <br>
        @endif

        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                @if($batch->comment)
                <tr align="center">
                    <th>{{ $batch->comment ?? '' }}
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