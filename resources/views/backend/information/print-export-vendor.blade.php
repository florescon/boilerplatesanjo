<!DOCTYPE html>
<html>
<head>
    <title>Global Report - {{ appName() }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0px;
        }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
        }
        .header p {
            font-size: 12px;
        }
        .details-table, .batch-table, .qa-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td, .batch-table th, .batch-table td, .qa-table th, .qa-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 14px;
            text-align: left;
        }

        .details-table>tbody>tr:nth-child(odd)>td, 
        .details-table>tbody>tr:nth-child(odd)>th {
           background-color: #eaeaea; // Choose your own color here
         }

        .batch-table th, .qa-table th {
            background-color: #f2f2f2;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .signature div {
            width: 32%;
            text-align: center;
        }
        .signature img {
            width: 100%;
            height: auto;
        }

        .page_break { page-break-before: always; }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">

            <table width="100%">
              <tr>
                <td width="30%" style="text-align: center;">
                  <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
                </td>
                <td width="70%">
                    <h1>@lang('Purchase Order') - Global </h1>
                </td>
              </tr>
            </table>

            <p>Report generated on {{ now() }} </p>
            <!-- <p>Report generated on 07:57:36 Wed 16 Feb 2022</p> -->
        </div>

        <table class="details-table">
            <tr>
                <td colspan="2">Órdenes: 
                    @foreach($ordercollectionn as $key => $material)
                        #{{ $key }}
                    @endforeach
                </td>
                <td colspan="2">Lotes: 
                    @foreach($ordercollectionn as $key => $material)
                        @foreach($material as $m)
                            #{{ $m[0]['station_id'] }}
                        @endforeach
                    @endforeach
                </td>
            </tr>
        </table>
        <table class="details-table">

            <tr>
                <td  style="text-align: center;">Tot.</td>
                <td  style="text-align: center;">@lang('Qty')</td>
                <td  style="text-align: center;">@lang('Folio')</td>
                <td  style="text-align: center;">@lang('Order')</td>
                <td  style="text-align: center;">@lang('Concept')</td>
            </tr>

            @foreach ($groupedProducts as $parentId => $products)
                @php
                    $rowspan = $products->count();
                @endphp
                @foreach ($products->sortBy([['productColorName', 'asc'], ['productSizeSort', 'asc'] ]) as $key => $product)
                    <tr>
                        @if ($key === 0)
                            <td rowspan="{{ $rowspan }}" style="text-align: center;">
                                @foreach($parentQuantities as $key => $q)
                                    @if($key === $parentId)
                                        {{ $q }}
                                    @endif
                                @endforeach

                            </td>
                        @endif
                        <td style="text-align: center;" align="center">{{ $product['productQuantity'] }}</td>
                        <td style="text-align: center;" align="center">#{{ $product['stationId'] }}</td>
                        <td style="text-align: center;" align="center">#{{ $product['orderId'] }}</td>
                        <td scope="row">
                            <strong>{{ $product['productName'] }}</strong>
                            {{ $product['productSizeName'] }}, {{ $product['productColorName'] }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
     
        </table>

    </div>
</body>
</html>
