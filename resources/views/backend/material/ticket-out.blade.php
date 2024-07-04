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
        <table width="100%" style="margin-top: -40px">
          <tr>
            <td style="text-align: center;">
              <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
            </td>
          </tr>
        </table>

        <table width="100%">
            <tr>
                <td style="text-align: center;">
                    <h3 style="margin-top: 5px;">
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
              <td align="center"><strong>Fecha:</strong> {{ $out->created_at->isoFormat('D, MMM YY') }}</td>
            </tr>
        </table>

        <table style="margin-bottom: 10px; text-align: center;" width="100%">
            <tr>
                @if($out->customer)
                    <td><strong>Asignado a:</strong> {{ optional($out->customer)->name }}</td>
                @endif
            </tr>
            <tr>
                @if($out->user)
                    <td><strong>Expedido por:</strong> {{ optional($out->user)->name }} </td>
                @endif
            </tr>
        </table>

       @if(count($out->feedstocks))
            <table width="100%">
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Cant.</th>
                    <th>Concepto</th>
                    <th>Precio</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @php($total = 0)
                  @foreach($out->feedstocks as $product)
                  <tr>
                    <td align="center">{{ $product->quantity }} &nbsp;&nbsp;</td>
                    <td scope="row">{!! optional($product->material)->full_name !!}</td>
                    <td scope="row">${{ $product->price }}</td>
                    <td scope="row">${{ number_format($product->total_by_product, 2) }}</td>
                  </tr>
                  @php($total += $product->total_by_product)
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right" colspan="3" style="text-align: right;"> Total </td>
                        <td align="center" class="gray">${{ number_format($total, 2) }}</td>
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

        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                <tr align="right">
                    <td> <em style="font-size: 80%;">{{ printed() }}</em></td>
                </tr>
            </thead>
        </table>

    </body>
</html>
