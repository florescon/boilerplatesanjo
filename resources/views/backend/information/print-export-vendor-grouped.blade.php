<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order</title>
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

        .header, .si {
            text-align: right;
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

        .header .title {
            font-size: 29px;
            font-weight: bold;
            color: #6529ff;
            border-radius: 2px;
        }

        .company-info, .vendor-info, .ship-to-info, .special-instructions {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #6529ff;
            border-radius: 2px;
            color: white;
            padding: 5px;
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        .table th {
            background-color: #6529ff;
            border-radius: 2px;
            color: white;
        }
        .total {
            font-weight: bold;
            text-align: right;
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
                  <img src="{{ public_path('img/logo22.png') }}" alt="" width="100"/>
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
                        <td style="text-align: center;" align="center">{{ $product['stationId'] ? '#'.$product['stationId'] : 'N/A' }}</td>
                        <td style="text-align: center;" align="center">{{ $product['orderId'] ? '#'.$product['orderId']  : 'N/A' }}</td>
                        <td scope="row">
                            <strong>{{ $product['productName'] }}</strong>
                            {{ $product['productSizeName'] }}, {{ $product['productColorName'] }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
     
        </table>

    </div>

    @foreach($groupedProductsSecond as $vendor => $groupedByVendor)
        <div class="page_break">
            <div class="header si">
                <img src="{{ public_path('img/logo22.png') }}" alt="" width="100"/>
                <div class="title">PURCHASE ORDER</div>
            </div>
            <div class="company-info">
                <strong>{{ appName() }}</strong><br>
                {{ setting('site_address') }}<br>
                {{ setting('site_email') }}<br>
                {{ setting('site_whatsapp') }}
            </div>

            <div class="header" style="margin-bottom: 20px;">
                <strong>Generated on: </strong>{{ now() }}<br>
            </div>
            <table class="table" style="margin-bottom: 20px;">
                <tr>
                    <td class="section-title" width="40%">VENDOR</td>
                    <td class="section-title" colspan="2" width="20%" style="background: white; border: none;"></td>
                    <td class="section-title" width="40%">ADDRESS</td>
                </tr>
                <tr>
                    <td style="border: none;">
                        <strong>{{ $vendor }}</strong><br>
                        {{ $groupedByVendor->first()[0]['vendorEmail'] }}<br>
                        {{ $groupedByVendor->first()[0]['vendorPhone'] }}<br>
                        {{ $groupedByVendor->first()[0]['vendorRfc'] }}<br>
                    </td>
                    <td colspan="2" style="border: none;">
                    </td>
                    <td style="border: none;">
                        {{ $groupedByVendor->first()[0]['vendorAddress'] }}<br>
                        {{ $groupedByVendor->first()[0]['vendorCity'] }}<br>
                    </td>
                </tr>
            </table>
            <table class="table">
                <tr>
                    <th>G. CODE</th>
                    <th>DESCRIPTION</th>
                    <th style="text-align: center;">QTY</th>
                </tr>
                @php($total = 0)
                @foreach($groupedByVendor as $productGroup)
                    @foreach($productGroup as $producte)
                        <tr>
                            <td>{{ $producte['productParentCode'] }}</td>
                            <td><strong>{{ $producte['productParentName'] }}</strong> {{ $producte['productColorName'].' '.$producte['productSizeName'] }}</td>
                            <td style="text-align: center;">{{ $producte['productQuantity'] }}</td>
                        </tr>
                        @php($total += $producte['productQuantity'])
                    @endforeach
                @endforeach
                <tr>
                    <th colspan="2" style="text-align: right;">Total</th>
                    <th style="text-align: center;">{{ $total }}</th>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
