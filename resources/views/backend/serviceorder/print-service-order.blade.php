<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@lang('Service Order') #{{ $service->id }} {{ optional($service->order)->user_name }}</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }

        .striped tr:nth-child(even) {
          background-color: #fff7f3;
        }

        .gray {
            background-color: lightgray
        }

        .watermark {
            color: lightgray;
            opacity: 0.15;
            font-size: 3em;
            width: 75%;
            top: -2%;
            left: 38%;
            position: absolute;   
            text-align: center;
            z-index: 0;
        }
        .watermark2 {
            color: lightgray;
            opacity: 0.15;
            font-size: 3em;
            width: 100%;
            top: 52%;
            left: 38%;
            position: absolute;   
            text-align: center;
            z-index: 0;
        }
    </style>
</head>
<body style="">

    <div style=" ">
        <table width="100%">
        <tr>
            <td align="top" style="">
                <img style="max-height: 250px !important; max-width: 500px; " src="{{ public_path('/storage/' . $service->image->image) }}" alt="Card image cap">
            </td>
            <td align="right" style=" max-width: 260px; max-height: 200px; ">
                <div>
                <img style="  position: absolute; margin-top: 0px; margin-right: 10px;" src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(100)->generate('https://sjuniformes.com')) }} "/>
                    <img height="50" src="{{ public_path('img/logo22.png') }}"/>
                </div>

                <h3>@lang('Service Order') #{{ $service->id }}</h3>
                <pre>
                    <strong>@lang('Date'):</strong> {{ $service->created_at }}
                    <strong>@lang('Order'):</strong> #{!! $order->folio_or_id !!}
                    <strong>@lang('Service'): </strong> <u>{{ optional($service->service_type)->name }}</u>
                    <strong>@lang('Customer'):</strong> {{ optional($service->order)->user_name }}
                    <strong>@lang('Created by'): </strong> {{ optional($service->createdby)->name }}
                </pre>
            </td>
        </tr>

      </table>

      <table width="100%">
        <tr>
            <td><strong>Realizó:</strong> {{ optional($service->personal)->name }} </td>
            <td><strong>Autorizó:</strong> {{ optional($service->authorized)->name }} </td>
        </tr>

        <tr>
            <td><strong>Archivo:</strong> {{ $service->file_text }}</td>
            @if($service->approved)
                <td><strong>Autorizado:</strong> {{ $service->approved }}</td>
            @endif
        </tr>
        <tr>
            <td><strong>Comentario:</strong> {{ $service->comment }} </td>
            <td><strong>Dimensiones:</strong> {{ $service->dimensions }}</td>
        </tr>

      </table>

      <br/>

      <table width="100%"
      {{-- style="page-break-after: always;" --}}
      class="striped" 
      >
        <thead style="background-color: lightgray;">
          <tr>
            <th>#</th>
            <th>@lang('Description')</th>
            <th>@lang('Comment')</th>
            <th>@lang('Quantity')</th>
          </tr>
        </thead>
        <tbody>
            @foreach($service->product_service_orders as $key => $product)
              <tr>
                <th scope="row">{{ $key+1 }}</th>
                <td>{!! $product->product->full_name !!}</td>
                <td>{{ $product->comment }}</td>
                <td align="right">{{ $product->quantity }}</td>
              </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td align="right">Total</td>
                <td align="right" class="gray">{{ $service->total_products }}</td>
            </tr>
        </tfoot>
      </table>

    </div>

    <br>

    <div 
      style="
/*        page-break-after: always;*/
        page-break-inside:avoid;
      "
    >
        <table width="100%">
            <tr>
                <td align="top" style="">
                    <img style="max-height: 250px !important; max-width: 500px; " src="{{ public_path('/storage/' . $service->image->image) }}" alt="Card image cap">
                </td>
                <td align="right" style=" max-width: 260px; max-height: 200px; ">
                    <div>
                        <img style="  position: absolute; margin-top: 0px; margin-right: 10px;" src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(100)->generate('https://sjuniformes.com')) }} "/>
                        <img height="50" src="{{ public_path('img/logo22.png') }}"/>
                    </div>

                    <h3>@lang('Service Order') #{{ $service->id }}</h3>
                    <pre>
                        <strong>@lang('Date'):</strong> {{ $service->created_at }}
                        <strong>@lang('Order'):</strong> #{!! $order->folio_or_id !!}
                        <strong>@lang('Service'): </strong> <u>{{ optional($service->service_type)->name }}</u>
                        <strong>@lang('Customer'):</strong> {{ optional($service->order)->user_name }}
                        <strong>@lang('Created by'): </strong> {{ optional($service->createdby)->name }}
                    </pre>
                </td>
            </tr>

        </table>

      <table width="100%">
        <tr>
            <td><strong>Realizó:</strong> {{ optional($service->personal)->name }} </td>
            <td><strong>Autorizó:</strong> {{ optional($service->authorized)->name }} </td>
        </tr>

        <tr>
            <td><strong>Archivo:</strong> {{ $service->file_text }}</td>
            @if($service->approved)
                <td><strong>Autorizado:</strong> {{ $service->approved }}</td>
            @endif
        </tr>
        <tr>
            <td><strong>Comentario:</strong> {{ $service->comment }} </td>
            <td><strong>Dimensiones:</strong> {{ $service->dimensions }}</td>
        </tr>

      </table>
      
      <br/>

      <table width="100%" 
      {{-- style="page-break-after: always;" --}}
      class="striped" 
      >
        <thead style="background-color: lightgray;">
          <tr>
            <th>#</th>
            <th>@lang('Description')</th>
            <th>@lang('Comment')</th>
            <th>@lang('Quantity')</th>
          </tr>
        </thead>
        <tbody>
            @foreach($service->product_service_orders as $key => $product)
              <tr>
                <th scope="row">{{ $key+1 }}</th>
                <td>{!! $product->product->full_name !!}</td>
                <td>{{ $product->comment }}</td>
                <td align="right">{{ $product->quantity }}</td>
              </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td align="right">Total</td>
                <td align="right" class="gray">{{ $service->total_products }}</td>
            </tr>
        </tfoot>
      </table>

    </div>

</body>
</html>