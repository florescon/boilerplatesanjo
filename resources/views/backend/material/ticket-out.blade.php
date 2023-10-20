<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($out->user)->name }}</title>

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
                    <h3>San Jose Uniformes</h3>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td style="text-align: center;">
                    <h3 style="margin-top: -5px;">
                        <strong>
                            Vale de Salida de Almacén
                        </strong>
                    </h3>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <h2 style="margin-top: -20px;">
                        <strong>
                        </strong> #{!! $out->id !!}
                    </h2>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
              <td align="left"><strong>F. Generado:</strong> {{ $out->created_at->isoFormat('D, MMM YY - h:mm a') }}</td>
            </tr>
        </table>

        <table style="margin-bottom: 10px; text-align: center;" width="100%">
            <tr>
                @if($out->user || $out->departament)
                    <td><strong>Asignado a:</strong> {{ $out->user_name }}</td>
                @endif
                <td><strong>Expedido por:</strong> {{ optional($out->audi)->name }} </td>
            </tr>
            <tr>
                <td> {{ optional($out->customer)->name }} </td>
                <td> {{ optional($out->user)->name }} </td>
            </tr>
        </table>

       @if(count($out->feedstocks))
            <table width="100%">
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Cant.</th>
                    <th>Concepto</th>
                    <th>Precio</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($out->feedstocks as $product)
                  <tr>
                    <td align="center">{{ $product->quantity }} &nbsp;&nbsp;</td>
                    <td scope="row">{!! optional($product->material)->full_name !!}</td>
                    <td scope="row">${{ $product->price }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right" colspan="2" style="text-align: right;"> Total </td>
                        <td align="center" class="gray">${{ number_format($out->total_out, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endif

      @if($out->description)
          <table style="margin-top: 10px" width="100%">
              <tr>
                <td style="text-align: center;">
                    <em><strong>@lang('Description'):</strong> {{ $out->description }}</em>
                </td>
              </tr>
          </table>
      @endif

      <table style="margin-top: 10px;" width="100%">
        <tr>
            <td style="text-align: center;">
                _________________________
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <em><strong>Recibió</strong></em>
            </td>
        </tr>
      </table>

    </body>
</html>
