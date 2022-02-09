<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ optional($ticket->user)->name }}</title>

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
            @if($ticket->status)
                <tr>
                    <td align="center">
                        <h3>{{ optional($ticket->status)->name }}</h3>
                    </td>
                </tr>
            @endif
        </table>

        <table width="100%">
            <tr>
              <td align="left"><strong>Fecha generado:</strong> {{ $ticket->created_at }}</td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td><strong>Folio:</strong> #{{ $ticket->id }}</td>
            </tr>
        </table>

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

        @if(count($ticket->assignments_direct))
            <table width="100%">
                <thead style="background-color: gray;">
                  <tr align="center">
                    <th>Concepto</th>
                    <th>Asignado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ticket->assignments_direct as $assign)
                  <tr>
                    <td scope="row">{!! $assign->assignmentable->product->full_name !!}</tf>
                    <td align="center">{{ $assign->quantity }}</td>
                  </tr>
                  @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td align="right">Total</td>
                        <td align="center" class="gray"><strong>{{ $ticket->total_products_assignment_ticket }}</strong></td>
                    </tr>
                </tfoot>            
            </table>
            <br>
        @endif

        <table width="100%" style="margin-top:30px;">
            <thead style="background-color: white;">
                <tr align="center">
                    <th>__________________________________</th>
                </tr>
            </thead>
        </table>

    </body>
</html>