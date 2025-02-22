<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>#{{ $finances->id }}</title>
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
          <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
        </td>
      </tr>
      <tr>
          <td align="center">
              <h3>{{ appName() }}</h3>
              <pre>
  {{ Request::server ("SERVER_NAME") }}
  {{ setting('site_address') }}
  {{ setting('site_email') }}
  {{ setting('site_whatsapp') }}
              </pre>
          </td>
      </tr>
    </table>

    <table width="100%">
        <tr>
          <td align="left"><strong>@lang('Date Issued'):</strong> {{ $finances->created_at->isoFormat('D, MMM h:mm:ss a') }}</td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            @if($finances->payment)
            <td><strong>Método pago:</strong> {{ optional($finances->payment)->short_title }} </td>
            @endif
            <td><strong>Folio:</strong> #{{ $finances->id }}</td>
        </tr>
    </table>

    <table style="margin-bottom: 10px; text-align: center;" width="100%">
        <tr>
            @if($finances->user || $finances->departament)
                <td><strong>A:</strong> {{ $finances->user_name_or_departament }}</td>
            @endif
        </tr>
        <tr>
            <td><strong>Expedido por:</strong> {{ optional($finances->audi)->name }} </td>
        </tr>
    </table>

    <table style="margin-bottom: 10px;" width="100%">
        <tr>
            <td>{{ $finances->ticket_text }}</td>
        </tr>
    </table>

    <table width="100%">
        @if($finances->order_id)
          <thead style="background-color: #FCFCAA;">
            <tr align="center">
              <th colspan="2">@lang('Order')/@lang('Sale'): #{!! optional($finances->order)->folio_or_id !!}</th>
            </tr>
            <tr align="center">
              <th colspan="2">@lang('Tracking number'): {{ optional($finances->order)->slug }}</th>
            </tr>
          </thead>
        @endif
        <thead style="background-color: gray;">
          <tr align="center">
            <th>@lang('Concept')</th>
            <th>@lang('Price')</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td scope="row">{{ $finances->name }}</tf>
            <td align="center">${{ number_format($finances->amount, 2) }}</td>
          </tr>
        </tbody>
    </table>
  </body>
</html>