<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>@lang('Bom of Materials')</title>

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
                <h3>@lang('Bom of Materials')</h3>
            </td>

          </tr>

          <tr>
            <td align="center">
                @foreach($ordercollection as $key => $material)
                   <p> #{!! '<strong>'. $material['folio'] .' '.$material['user'] .'</strong> - '. $material['comment'] !!}</p>
                @endforeach
            </td>

          </tr>
        </table>


        @if($materials)
            <table width="100%" style='margin-top: 10px; font-family:"Courier New", Courier, monospace; font-size:97%'>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Explosión</th>
                    <th>Concepto</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($materials as $key => $material)
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
            <br>
        @endif
        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                <tr align="right">
                    <td> <em style="font-size: 80%;">Sort by: <strong>family</strong> &nbsp;&nbsp; {{ printed() }}</em></td>
                </tr>
            </thead>
        </table>

    </body>
</html>