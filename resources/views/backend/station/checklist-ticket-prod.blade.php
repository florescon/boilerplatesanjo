<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> {{ optional($station->personal)->name }}</title>

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
              <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
            </td>
          </tr>
        </table>

        <table width="100%">
          <tr>
            <td align="center">
                <h3>Materia Prima <u>Consumida</u></h3>
            </td>
          </tr>
        </table>

        @if($station->date_entered)
            <table width="100%">
                <tr>
                    <td align="left">
                        <strong>Fecha:</strong> 
                        {{ $station->date_entered->format('d-m-Y') }}
                    </td>
                </tr>
            </table>
        @endif

        @if($station->order_id)
            <table width="100%">
                <tr>
                    <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{!! optional($station->order)->folio_or_id !!}</u></td>
                    <td><strong>@lang('Batch'):</strong><u style="font-size: 140%;"> #{{ $station->folio }}</u></td>
                </tr>
            </table>
        @endif

        @if($station->status_id !== 6)
            <table style="margin-bottom: -30px; margin-top: -20px;" width="100%">
                <tr>
                    @if($station->order)
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
        @endif

        @if(isset($groupedMaterials))
            <table width="100%" style='margin-top: 10px; font-family:"Courier New", Courier, monospace; font-size:97%'>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Asignado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($groupedMaterials->sortBy('material')  as $assign)
                  <tr>
                    <td scope="row">
                        {!! $assign['material'] !!}
                        <br>
                        {{-- <strong>{{ $assign->comment }}</strong> --}}
                    </td>
                    <td align="center">{{ $assign['sum'] }}</td>
                  </tr>
                  @endforeach
                </tbody>

            </table>
            <br>
        @endif

        @if($station->comment)
        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                <tr align="center">
                    <th>{{ $station->comment ?? '' }}
                        <br><br><br>
                    </th>
                </tr>

            </thead>
        </table>
        @endif

        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                <tr align="right">
                    <td> <em style="font-size: 80%;">{{ printed() }}</em></td>
                </tr>
            </thead>
        </table>

    </body>
</html>