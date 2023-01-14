<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{!! $material->full_name ?? '' !!}</title>

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
  h1 {
    font-size: 95px;
  }
  h2 {
    font-size: 35px;
  }
  h3 {
    font-size: 30px;
  }

  table, th, td {
    border: 1px solid white;
    border-radius: 10px;
  }
</style>

</head>
  <body  style="margin-top: 10px">

    <table style="width:100%">
      <tr>
        <th rowspan="2" align="center">
          @if($material->part_number)
              <img src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(100)->generate($material->part_number)) }}"style="        
                    /*position: relative;*/
                margin-top: 69px;
                height:490px;
                /*padding-bottom: 0;*/
                /*overflow: hidden;*/
                /*border: 1px solid;*/
                " 
                alt="barcode"
              />
              <br>
              <i style="font-size: 33px;">{{ $material->part_number }}</i>
          @endif
        </th>
        <td align="center">
          <h2>
            {{ $material->name }}
          </h2>
          <h3>
            {{ $material->color_id ? $material->color->name : '' }} {!! $material->size_id ? $material->size->name : '' !!}
          </h3>
          <h3 style="margin-top:100px;">
            {{ $quantity ?: '' }}
            {{ $quantity > 0 ? $material->unit->name : '' }}
          </h3>
        </td>
      </tr>
      <tr>
        <th colspan="2">
          <h3>
            {{ now()->format('d-m-Y H:i:s') }}
          </h3>
        </th>
      </tr>
      {{-- <tr>
        <th colspan="2">
          @lang('Labels')
        </th>
      </tr> --}}
    </table>
  </body>
</html>