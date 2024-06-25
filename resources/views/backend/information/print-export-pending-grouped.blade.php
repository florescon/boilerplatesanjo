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
                    <h1>@lang('Bill of Materials') - @lang('Global Report') </h1>
                </td>
              </tr>
            </table>

            <p>Report generated on {{ now() }} </p>
            <!-- <p>Report generated on 07:57:36 Wed 16 Feb 2022</p> -->
        </div>

        <table class="details-table">
            <tr>
                <td colspan="2">@lang('Orders'): 
                    @foreach($ordercollectionn as $key => $material)
                        #{{ $key }}
                    @endforeach
                </td>
                <td colspan="2">@lang('Batches'): 
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
                <td  style="text-align: center;"></td>
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
                            <td rowspan="{{ $rowspan }}" style="width: 30px; text-align: center;">
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


        @if(count($materialsCollectionSecond))
            <table width="100%">
                <caption style="margin-top: 30px"><em><u>Lista de materia prima agregada manualmente</u></em></caption>
                <thead style="background-color: lightgray;">
                  <tr>
                    <th>#</th>
                    <th>@lang('Internal Code')</th>
                    <th>@lang('Description')</th>
                    <th>@lang('Existencia')</th>
                    <th>@lang('Added')</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($materialsCollectionSecond->sortBy([['vendor', 'asc'], ['material_name', 'asc']]) as $key => $material)
                        @if($material['additional'] === true)
                          <tr>
                            <th scope="row" style="text-align: center;"><u>{{ $key+1 }}</u></th>
                            <th>{{ $material['part_number'] }}</th>
                            <td>{{  $material['material_name'] }}</td>
                            <td>{{  $material['stock'].' '.$material['unit_measurement'] }}</td>
                            <td align="center">{{ $material['quantity'].' '.$material['unit_measurement'] }}</td>
                          </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif


        @foreach($materials as $vendor => $material)

            <div class="page_break">
              <table width="100%">
                <tr>
                    <td valign="top"><img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/></td>
                    <td align="right">
                        <h3>{{ appName() }}</h3>
                        <pre>
                            {{ setting('site_address') }}
                            {{ setting('site_email') }}
                            {{ setting('site_whatsapp') }}
                        </pre>
                    </td>
                </tr>

              </table>

              <table width="100%">

                <tr>
                    <td><strong>To:</strong> {{ $vendor }}</td>
                </tr>
                <p>Report generated on {{ now() }} </p>

              </table>

              <br/>

                <table width="100%">
                    <thead style="background-color: lightgray;">
                      <tr>
                        <th>#</th>
                        <th>@lang('Internal Code')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Quantity')</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($material->sortBy([['vendor', 'asc'], ['material_name', 'asc']]) as $key => $material)
                            @if($material['quantity_minus_stock'] > 0)
                              <tr>
                                <th scope="row"><u>{{ $key+1 }}</u></th>
                                <th>{{ $material['part_number'] }}</th>
                                <td>{{  $material['material_name'] }}</td>
                                <td align="center">{{ $material['quantity_minus_stock'].' '.$material['unit_measurement'] }}</td>
                              </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class="footer">
                    <p>Report produced using {{ request()->getHost(); }}</p>
                </div>

                <div style="text-align: center;">
                    <br>
                    _____________________
                </div>

            </div>
        @endforeach


    </div>
</body>
</html>
