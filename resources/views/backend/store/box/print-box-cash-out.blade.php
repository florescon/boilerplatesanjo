<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($box->user)->name }}</title>

        <style type="text/css">
            * {
                font-family: Verdana, Arial, sans-serif;
            }
            table{
                font-size: medium;
            }
            tfoot tr td{
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
              <img src="{{ public_path('img/logo22.png') }}" alt="" width="100"/>
            </td>
          </tr>
            <tr>
                <td align="center">
                    <h3>San Jose Uniformes</h3>
                </td>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid; border-style: dashed solid dashed solid;">
                    <h3>@lang('Daily cash closing')</h3>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
              <td align="left"><strong>Fecha generado:</strong> {{ $box->created_at->isoFormat('D, MMM h:mm:ss a') }}</td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                @if($box->payment)
                <td><strong>Método pago:</strong> </td>
                @endif
                <td><strong>Folio:</strong> #{{ $box->id }}</td>
            </tr>
        </table>

        <table style="margin-bottom: 10px;" width="100%">
            <tr>
                <td><strong>Expedido por:</strong> {{ optional($box->audi)->name }} </td>
            </tr>
        </table>

        @if(count($box->finances))
            <table width="100%">
                <thead style="background-color: gray;">
                  <tr align="center">
                      <th scope="col">#</th>
                      <th scope="col">@lang('Name')</th>
                      <th scope="col">@lang('Amount')</th>
                      <th scope="col">@lang('Comment')</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($box->finances as $finance)
                  <tr>
                      <th scope="row">{{ $finance->id }}</th>
                      <td>{{ $finance->name }}</td>
                      <td class="{{ $finance->finance_text }}">
                        {{ $finance->amount }}
                        <p>
                            <span class="badge badge-secondary">{{ $finance->payment_method }}</span>
                        </p>
                      </td>
                      <td>
                        {{ $finance->comment ?: '--' }}
                        <p>
                            {!! $finance->user_name !!}
                            {!! $finance->order_track !!}
                        </p>
                      </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
            <br>
        @endif

        @if(count($box->orders))
            <table width="100%">
                <thead style="background-color: gray;">
                  <tr align="center">
                      <th scope="col">#</th>
                      <th scope="col">@lang('User')</th>
                      <th scope="col">@lang('Comment')</th>
                      <th scope="col">@lang('Type')</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($box->orders as $order)
                  <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td>{!! $order->user_name !!}</td>
                    <td>{{ $order->comment ?: '--' }}</td>
                    <td>{!! $order->type_order !!}</td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
            <br>
        @endif

    </body>
</html>