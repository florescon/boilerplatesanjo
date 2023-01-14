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
    font-size: 90px;
  }
  h2 {
    font-size: 30px;
  }
</style>

</head>
  <body style="margin-top: 185px;">
      <table width="100%">
        {{-- @if($product->code) --}}
          <tr>
            <td align="center">
              <img src="data:image/png;base64,{{   DNS1D::getBarcodePNG($product->code_label, 'C128',2,33,array(1,1,1), false)  }}"  style="        
                    /*position: relative;*/
                /*margin-top: -10px;*/
                height:150px;
                /*padding-bottom: 0;*/
                width: 100%;
                /*overflow: hidden;*/
                /*border: 1px solid;*/
                
                " 
                alt="barcode"
              />
            <i style="font-size: 19px;">{{ $product->code_label }}</i>


{{--                   <img src="data:image/png;base64, {{ base64_encode(\QrCode::format('svg')->size(140)->generate(route('frontend.track.show', $product->code_label))) }} "/>
 --}}              
            </td>
          </tr>
       {{-- @endif --}}
        <tr>
          <td align="center">
            <h2>
{{ $product->parent->name }}
{{ optional($product->parent->model_product)->name }}
            </h2>
          </td>
        </tr>
        <tr>
          <td align="center">
            <h2>
{{ $product->color_id ? $product->color->name : '' }}
            </h2>
          </td>
        </tr>
        <tr>
          <td align="center">
            <h2>
{!! $product->size_id ? $product->size->name : '' !!}
            </h2>
          </td>
        </tr>

      </table>

    @for ($i = 0; $i < 4; $i++)
      <table width="100%">
        <tr>
          <td align="center">
            <strong>
              <h1>{{ optional($product->size)->name }}</h1>
            </strong>
          </td>
        </tr>
      </table>
    @endfor 
  </body>
</html>