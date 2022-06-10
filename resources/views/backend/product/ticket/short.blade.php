<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{!! $product->full_name ?? '' !!}</title>

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
    font-size: 105px;
  }
  h2 {
    font-size: 35px;
  }
  h3 {
    font-size: 30px;
  }

  table, th, td {
    border: 1px solid black;
    border-radius: 10px;
  }
</style>

</head>
  <body  style="margin-top: 10px">

    <table style="width:100%">
      <tr>
        <th rowspan="2" align="center">
          @if($product->code)
              <img src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(100)->generate($product->code)) }}"style="        
                    /*position: relative;*/
                margin-top: 49px;
                height:390px;
                /*padding-bottom: 0;*/
                /*overflow: hidden;*/
                /*border: 1px solid;*/
                " 
                alt="barcode"
              />
              <br>
              <i style="font-size: 33px;">{{ $product->code }}</i>
          @endif
        </th>
        <td align="center">
          <h2>
            {{ $product->name }}
          </h2>
          <h1>
            {{ $product->color_id ? $product->color->name : '' }}
          </h1>
        </td>
      </tr>
      <tr align="center">
        <td>
          <h1>
            {!! $product->size_id ? $product->size->name : '' !!}
          </h1>
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