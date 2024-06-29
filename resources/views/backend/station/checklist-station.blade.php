<!DOCTYPE html>
<html>
<head>
    <title>Checklist Materia Prima</title>
    <style>
        /* Aquí puedes agregar tu CSS para estilizar el PDF */
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }

        tr. th { background: #c6d9f0; }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            font-size: 22px;
        }
        .header p {
            font-size: 12px;
        }
        .checkbox { display: inline-block; width: 15px; height: 15px; border: 1px solid black; text-align: center; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHECKLIST MATERIA PRIMA</h1>
        <p>@lang('Report generated on') {{ now() }}</p>
    </div>

    <table>
        <tr>
            <td><strong>@lang('Request n.º'):</strong> {{ $station->order->request }} </td>
            <td><strong>@lang('Purchase Order'):</strong> {{ $station->order->purchase }} </td>
            <td rowspan="2" style="text-align: center;"> 
                <strong>Lot.</strong> #{{ $station->id }}
            </td>
        </tr>   
        <tr>
            <td><strong>@lang('Date Consumption'):</strong> {{ $groupedMaterials->first() ? $groupedMaterials->first()['created_at'] : "" }}</td>
            <td><strong>@lang('Order'):</strong> #{{ $station->order->folio_or_id_clear }} </td>
        </tr>
        <tr>
            <td colspan="3"><strong>@lang('Customer'):</strong> {{ $station->order->user_name_clear }}</td>
        </tr>
    </table>

    <table style="margin-top: 20px;">
        <tr>
            <th style="text-align: center;" rowspan="2">@lang('Concept')</th>
            <th style="text-align: center;" rowspan="2">@lang('Quantity')</th>
            <th style="text-align: center;" colspan="3">@lang('Inspection')</th>
        </tr>
        <tr style="font-size: 11px;">
            <th>
                V.º B.º
            </th>
            <th>
                En Esp.
            </th>
            <th>
                Rech.
            </th>
        </tr>
        @foreach($groupedMaterials as $key => $material)
            <tr>
                <td>{!! $material['material'] !!}</td>
                <td>{{ $material['sum'] }}</td>
                <td  style="text-align: center;">
                    <span class="checkbox"></span>
                </td>
                <td  style="text-align: center;">
                    <span class="checkbox"></span>
                </td>
                <td  style="text-align: center;">
                    <span class="checkbox"></span>
                </td>
            </tr>
        @endforeach
        <!-- Agrega más filas según sea necesario -->
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <th>@lang('Observations')</th>
        </tr>
        <tr>
            <td  style="padding-bottom: 70px;">
                
            </td>
        </tr>
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <th>@lang('Checked By')</th>
            <th>@lang('Approved By')</th>
        </tr>
        <tr>
            <td  style="padding-bottom: 50px;">
            </td>
            <td  style="padding-bottom: 50px;">
            </td>
        </tr>
    </table>

</body>
</html>
