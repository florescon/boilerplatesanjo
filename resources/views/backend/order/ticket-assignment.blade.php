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
              <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
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

        @if($ticket->order_id)
            <table width="100%">
                <tr>
                    <td><strong>@lang('Order'):</strong><u style="font-size: 140%;"> #{!! optional($ticket->order)->folio_or_id !!}</u></td>
                    <td><strong>Ticket:</strong><u style="font-size: 140%;"> #{{ $ticket->id }}</u></td>
                </tr>
            </table>
        @endif

        <table style="margin-bottom: 10px; text-align: center;" width="100%">
            <tr>
                @if($ticket->user)
                    <td><strong>Asignado a:</strong> {{ optional($ticket->user)->name }}</td>
                @endif
            </tr>
            <tr>
                @if($ticket->audi)
                    <td><strong>Expedido por:</strong> {{ optional($ticket->audi)->name }} </td>
                @endif
            </tr>
        </table>


        @if($ticket->status_id !== 6)
            <table style="margin-bottom: -30px; margin-top: -20px;" width="100%">
                <tr>
                    @if($order->user)
                        <th class="text-center"> <h2>{{ optional($order->user)->real_name }}</h2></th>
                    @endif
                </tr>
            </table>

            @if($order->comment)
                <table style="margin-bottom: 10px;" width="100%">
                    <tr align="center">
                        <th>
                            @if($order->comment)
                                {{ $order->info_customer ?? '' }}
                                <br>
                            @endif
                            {{ $order->comment ?? '' }}
                        </th>
                    </tr>
                </table>
            @endif
        @endif

        @if(count($ticket->assignments_direct))
            <table width="100%" style='margin-top: 10px; font-family:"Courier New", Courier, monospace; font-size:97%'>
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Asignado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ticket->assignments_direct->sortBy([['assignmentable.product.parent.name', 'asc'], ['assignmentable.product.size.sort', 'asc']]) as $assign)
                  <tr>
                    <td scope="row">{!! $assign->assignmentable->product->full_name !!} {!! '<em>['.$assign->assignmentable->product->code_subproduct_clear.']</em>' !!}
                        <br>
                        <strong>{{ $assign->assignmentable->comment }}</strong>
                    </td>
                    <td align="center">{{ $assign->quantity }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right">Total</td>
                        <td align="center" style="background-color: #000; color: white;"><strong>{{ $ticket->total_products_assignment_ticket }}</strong></td>
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