<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Export Received</title>
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
                <td colspan="{{ $making ? '5' : '2' }}">
                    <table>
                        <tr>
                            <td class="title">
                                <h5>{{ ucfirst($status->name) }}</h5>
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
                <td colspan="{{ $making  ? '5' : '2' }}" style="text-align: center;"> {{ $getPersonal ? $getPersonal->name :'' }} </td>
            </tr>


            @if($dateInput && $dateOutput)
                <tr class="information">
                    <td colspan="{{ $making ? '5' : '2' }}">
                        <table>
                            <tr>
                                <td>
                                    <strong>Rango Inicial: </strong><br>
                                    {{ $dateInput }}<br>
                                </td>
                                <td>
                                    <strong>Rango Final: </strong><br>
                                    {{ $dateOutput }}<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
            <tr class="information">
                <td colspan="{{ $making ? '5' : '2' }}" style="padding-bottom: 50px;">
                    <table>
                        <tr>
                            <td>
                                <strong>La fecha más antigua es: </strong><br>
                                {{ $oldestDate }}<br>
                            </td>
                            <td>
                                <strong>La fecha más reciente es: </strong><br>
                                {{ $newestDate }}<br>
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>@lang('Description')</td>
                @if($making)
                    <td>@lang('Folio')</td>
                @endif    
                <td style="text-align: center;">Recibido</td>
                @if($making)
                    <td style="text-align: center;">@lang('Making')</td>
                    <td style="text-align: center;">Total</td>
                @endif
            </tr>

            @php($total = 0)
            @php($totalMaking = 0)
            @foreach($result as $r)
                <tr class="item">
                    <td >{!! $r['product_name'] !!}</td>
                    @if($making)
                    <td>
                        @foreach($r['productStationsId'] as $key => $stationId)
                            {{ $stationId }},
                        @endforeach
                    </td>
                    @endif
                    <td  style="text-align: center;">{{ $r['totalQuantity'] }}</td>
                    @if($making)
                        <td style="text-align: center;">${{ $r['priceMaking'] }}</td>
                        <td style="text-align: center;">${{ number_format($r['totalQuantity'] * $r['priceMaking'], 2, '.', '') }}</td>
                    @endif
                </tr>
                @php($total += $r['totalQuantity'])
                @if($making )
                    @php($totalMaking += $r['totalQuantity'] * $r['priceMaking'])
                @endif
            @endforeach
            <tr class="total" style="text-align: center;">
                <td style="text-align: right;" @if($making ) colspan="2" @endif>Total:</td>
                <td style="text-align: center;"><strong>{{ $total }}</strong></td>
                @if($making )
                    <td style="text-align: center;"><strong></strong></td>
                    <td style="text-align: center;"><strong>${{ number_format($totalMaking, 2, '.', '') }}</strong></td>
                @endif
            </tr>
        </table>
        <br>
        <p>
            Nota: <em></em>
        </p>
    </div>
</body>
</html>
