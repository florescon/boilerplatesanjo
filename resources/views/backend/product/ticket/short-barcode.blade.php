<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
{{-- <title>{{ optional($order->user)->name }}</title> --}}

<style type="text/css">

  @page {
    margin:10;padding:0.9; // you can set margin and padding 0 
  }

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
    font-size: 45px;
  }
  h2 {
    font-size: 40px;
  }

  .rotate {
    background-color: transparent;
    /*transform: rotate(-90deg);*/
    /*padding-right: 100px;*/
    margin-top:110px;
  }
</style>

</head>
  <body>
    <table  width="550">
      <tr>
        <td rowspan="2" class="td-width">
            <img src="{{ public_path('img/logo22-rotate.png') }}" class="rotate" width="140" style="
              /*border: 1px solid;*/
              margin-top: 40px;
            " 
          />
        </td>
        <td align="center">
          <div style="padding-right: 5px; padding-top: 20px;">
            <img src="data:image/png;base64,{{   DNS1D::getBarcodePNG($product->code_label, 'C128',1,33,array(1,1,1), false)  }}"  style="        
                  /*position: relative;*/
              margin-top: -10px;
              height:150px;
              /*padding-bottom: 0;*/
              width: 115%;
              /*overflow: hidden;*/
              /*border: 1px solid;*/
              
              " 
              alt="barcode"
            />
            <i style="font-size: 35px;">{{ $product->code_label }}</i>
          </div>
        </td>
        <td rowspan="2" style="padding-left: 6px;">
          <img src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(170)->generate($product->code_label)) }}" style=" margin-top: 40px;" />

          <h2>
            {{ '$'.$product->price_subproduct_label }}
          </h2>

        </td>
      </tr>
      <tr>
        <td align="center">
            <h2>
              {{ $product->parent->name }}
              {{ optional($product->parent->model_product)->name }}
              &nbsp;
              <em>{{ $product->color_id ? $product->color->name : '' }} | {!! $product->size_id ? $product->size->name : '' !!}</em>
            </h2>
        </td>
      </tr>
    </table> 
  </body>
</html