<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($ticket->user)->name }}</title>

        <style type="text/css">
            * {
                font-family: Verdana, Arial, sans-serif;
            }
            body {
              font-family: "Times New Roman", serif;
              margin: -4mm -7mm 0mm -7mm;
            }
            table {
                font-size: medium;
            }
            tfoot tr td {
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
            <td align="center">
                <h3>{{ optional($ticket->status)->name }}</h3>
            </td>

          </tr>
        </table>

        @if($ticket->date_entered)
            <table width="100%">
                <tr>
                    <td align="left">
                        <strong>Fecha:</strong> 
                        {{ $ticket->date_entered->format('d-m-Y') }}
                    </td>
                </tr>
            </table>
        @endif

        <table width="100%">
            <tr>
                <td align="left">
                    <strong>Fecha generado:</strong> 
                    {{ $ticket->created_at->isoFormat('D, MMM h:mm:ss a') }}
                </td>
            </tr>
        </table>

        @if($ticket->order_id)
            <table width="100%">
                <tr>
                    <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{{ $ticket->order_id }}</u></td>
                    <td><strong>Ticket:</strong><u style="font-size: 140%;"> #{{ $ticket->id }}</u></td>
                </tr>
            </table>
        @endif

        <table style="margin-bottom: 10px;" width="100%">
            <tr>
                @if($ticket->user)
                    <td><strong>A:</strong> {{ optional($ticket->user)->name }}</td>
                @endif
                @if($ticket->audi)
                    <td><strong>Expedido por:</strong> {{ optional($ticket->audi)->name }} </td>
                @endif
            </tr>
        </table>

        @if($order->comment)
            <table style="margin-bottom: 10px;" width="100%">
                <tr align="center">
                    <th>{{ $order->comment ?? '' }}
                        <br><br>
                    </th>
                </tr>
            </table>
        @endif

        @if(count($ticket->assignments_direct))
            <table width="100%" style='font-family:"Courier New", Courier, monospace; font-size:97%'>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Asignado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ticket->assignments_direct as $assign)
                  <tr>
                    <td scope="row">{!! $assign->assignmentable->product->full_name !!} {!! '<em>['.$assign->assignmentable->product->code_subproduct_clear.']</em>' !!}</tf>
                    <td align="center">{{ $assign->quantity }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right">Total</td>
                        <td align="center" style="background-color: dark; color: white;"><strong>{{ $ticket->total_products_assignment_ticket }}</strong></td>
                    </tr>
                </tfoot>            
            </table>
            <br>
        @endif

        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                @if($ticket->comment)
                <tr align="center">
                    <th>{{ $ticket->comment ?? '' }}
                        <br><br><br>
                    </th>
                </tr>
                @endif

                <tr align="center">
                    <th>__________________________________</th>
                </tr>

            </thead>
        </table>


        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">

                <tr align="right">
                    <td> <em style="font-size: 80%;">{{ printed() }}</em></td>
                </tr>
            </thead>
        </table>

    </body>
</html>