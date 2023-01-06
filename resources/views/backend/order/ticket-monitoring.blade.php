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
                    <h4>Seguimiento</h4>
                    <h4><strong >@lang('Order'):</strong> <em style="font-size: 170%;"> #{{ $order->id }}</em></h4>

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
                <td><strong>Método pago:</strong> </td>
                @endif
            </tr>
        </table>

        <table style="margin-bottom: 10px;" width="100%">
            <tr>
                <td><strong>@lang('Total products'):</strong> {{ $order->total_products }} </td>
            </tr>
            <tr>
                @if($order->user || $order->departament)
                    <td><strong>A:</strong> {{ $order->user_name }}</td>
                @endif
                <td><strong>Expedido por:</strong> {{ optional($order->audi)->name }} </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td>{{ $order->comment }}</td>
            </tr>
        </table>

    </body>
</html>