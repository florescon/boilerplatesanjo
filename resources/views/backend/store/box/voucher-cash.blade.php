<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($box->user)->name }}</title>

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
                </td>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid; border-style: dashed solid dashed solid;">
                    <h3>@lang('Daily cash closing')</h3>
                </td>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid; border-style: dashed solid dashed solid;">
                    <h5><em>Comprobante - @lang('Only cash')</em></h5>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
              <td align="left"><strong>Fecha generado:</strong> {{ $box->created_at->isoFormat('D, MMM h:mm:ss a') }}</td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                @if($box->payment)
                <td><strong>Método pago:</strong> </td>
                @endif
                <td><strong>Folio:</strong> #{{ $box->id }}</td>
            </tr>
        </table>

        <table style="margin-bottom: 10px;" width="100%">
            <tr>
                <td><strong>Realizó:</strong> {{ optional($box->audi)->name }} </td>
            </tr>
        </table>

        <table width="100%">
            <thead style="background-color: gray;">
              <tr align="center">
                  <th scope="col" style="text-align: center;">@lang('Amount')</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td style="text-align: center;">${{ $box->total_amount_cash_finances }}</td>
              </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table width="100%">
            <tbody>
              <tr>
                  <th scope="row">_____________________________</th>
              </tr>
            </tbody>
        </table>

    </body>
</html>