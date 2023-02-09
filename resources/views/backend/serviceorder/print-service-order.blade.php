<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>


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
                <div><img height="50" src="{{ public_path('img/logo22.png') }}"/></div>

                <h3>@lang('Service Order')</h3>
                <pre>
                    <strong>@lang('Date'):</strong> {{ $service->created_at }}
                </pre>
                <pre>
                    <strong>@lang('Order'):</strong> #{{ $order->id }}
                    <strong>@lang('Folio'):</strong> #{{ $service->id }}
                    <strong>Servicio: </strong> {{ optional($service->service_type)->name }}
                </pre>
            </td>
        </tr>

      </table>

      <table width="100%">
        <tr>
            <td><strong>Realizó:</strong> </td>
            <td><strong>Autorizó:</strong></td>
        </tr>

        <tr>
            <td><strong>Archivo:</strong> </td>
            <td><strong>Dimensiones:</strong></td>
        </tr>
        <tr>
            <td><strong>Comentario:</strong> </td>
        </tr>

      </table>

      <br/>

      <table width="100%" 
      {{-- style="page-break-after: always;" --}}
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
                <td align="right">{{ $product->comment }}</td>
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

    <div style=" ">
        <table width="100%">
        <tr>
            <td align="top" style="">
                <img style="max-height: 250px !important; max-width: 500px; " src="{{ public_path('/storage/' . $service->image->image) }}" alt="Card image cap">
            </td>
            <td align="right" style=" max-width: 260px; max-height: 200px; ">
                <div><img height="50" src="{{ public_path('img/logo22.png') }}"/></div>

                <h3>@lang('Service Order')</h3>
                <pre>
                    <strong>@lang('Date'):</strong> {{ $service->created_at }}
                </pre>
                <pre>
                    <strong>@lang('Order'):</strong> #{{ $order->id }}
                    <strong>@lang('Folio'):</strong> #{{ $service->id }}
                    <strong>Servicio: </strong> Bordado
                </pre>
            </td>
        </tr>

      </table>

      <table width="100%">
        <tr>
            <td><strong>Realizó:</strong> </td>
            <td><strong>Autorizó:</strong></td>
        </tr>

        <tr>
            <td><strong>Archivo:</strong> </td>
            <td><strong>Dimensiones:</strong></td>
        </tr>
        <tr>
            <td><strong>Comentario:</strong> </td>
        </tr>

      </table>

      <br/>

      <table width="100%" 
      {{-- style="page-break-after: always;" --}}
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
                <td align="right">{{ $product->comment }}</td>
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