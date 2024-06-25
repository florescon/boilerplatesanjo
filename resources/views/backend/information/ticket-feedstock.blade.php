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
                @foreach($ordercollectionn as $key => $material)
                    <strong>Orden: #{{ $key }}</strong>,
                    Estaciones:
                    @foreach($material as $m)
                        #{{ $m[0]['station_id'] }}
                    @endforeach
                   <br>
                @endforeach
            </td>

          </tr>

        </table>
        <br>
        <table width="100%">
            <thead style="background-color: gray; color:white;" >
              <tr align="center">
                <th>@lang('Quantity')</th>
                <th>@lang('Product')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($productsCollection as $key => $product)
              <tr>
                <td align="center">{{ $product['productQuantity'] }}</td>
                <td scope="row">
                    <strong>{{ $product['productParentName'] }}</strong><br>
                    {{ $product['productSizeName'].', '.$product['productColorName'] }}
                </td>
              </tr>
              @endforeach
            </tbody>
        </table>
        <br>


        <table width="100%">
            <thead style="background-color: gray; color:white;" >
              <tr align="center">
                <th>@lang('Quantity')</th>
                <th>@lang('Feedstock')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($allMaterials as $key => $material)
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



        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                <tr align="right">
                    <td> <em style="font-size: 80%;">{{ printed() }}</em></td>
                </tr>
            </thead>
        </table>

    </body>
</html>