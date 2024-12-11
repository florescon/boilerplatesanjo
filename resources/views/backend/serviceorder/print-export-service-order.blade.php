<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@lang('History') - {{ $getPersonal ? ucwords(strtolower($getPersonal->name)) : '' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 0px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;  
            font-weight: bold;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <h6>Reporte Órdenes de Servicio</h6>
                            </td>
                            <td >
                              <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
                                <br><br>
                                @lang('Date'): {{ now() }}            
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="4" style="text-align: center;">{{ $getPersonal ? ucwords(strtolower($getPersonal->name)) : '' }}</td>
            </tr>

            @if($dateInput)
                <tr class="information">
                    <td colspan="4">
                        <table>
                            <tr>
                                <td>
                                    <strong>Rango Inicial: </strong><br>
                                    {{ changeFormatStringDate($dateInput)  }}<br>
                                </td>
                                <td>
                                    <strong>Rango Final: </strong><br>
                                    {{ $dateOutput ? changeFormatStringDate($dateOutput) : now()->format('d-m-Y') }}<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
            <tr class="heading">
                <td style="text-align: center;">@lang('Service Type')</td>
                <td style="text-align: left;">@lang('Customer')</td>
                <td style="text-align: center;">@lang('Created at')</td>
                <td style="text-align: center;">@lang('Quantity')</td>
            </tr>

            @php($total = 0)
            @foreach($result as $r)
                <tr class="item">
                    <td width="25%" style="text-align: center;">{!! $r['service_type'] !!}</td>
                    <td width="25%" style="text-align: left;">{!! $r['customer'] !!}</td>
                    <td width="25%" style="text-align: center;">{!! $r['created_at'] !!}</td>
                    <td width="25%" style="text-align: center;">{{ $r['total'] }}</td>
                </tr>
                @php($total += $r['total'])
            @endforeach
            <tr class="total" style="text-align: center;">
                <td colspan="2">
                </td>
                <td style="text-align: right;">Total:</td>
                <td style="text-align: center;"><strong>{{ $total }}</strong></td>
            </tr>
        </table>
        <br>
    </div>
</body>
</html>
