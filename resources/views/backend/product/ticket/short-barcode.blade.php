<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{!! $product->full_name ?? '' !!}</title>

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
    font-size: 65px;
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
    <table width="850" style="margin-left: 10px; ">
      <tr>
        <td align="center" colspan="2" style="">
          <div style="padding-right: 5px; padding-left: 0px; padding-top: 15px;">
            <img src="data:image/png;base64,{{   DNS1D::getBarcodePNG($product->code_label, 'C128',1,33,array(1,1,1), false)  }}"  style="        
                  /*position: relative;*/
              margin-top: -15px;
              height:170px;
              /*padding-bottom: 0;*/
              width: 100%;
              /*overflow: hidden;*/
              /*border: 1px solid;*/
              " 
              alt="barcode"
            />
            <i style="font-size: 30px;">{{ $product->code_label }}</i>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center" style="width: 90%;">
            <h2 style="">
              {{ $product->parent->name }}
              &nbsp;<br>
              <strong>
               {{ $product->color_id ? $product->color->name : '' }} </strong>
            </h2>
        </td>
        <td align="center" style="">
            <h1 style="">
              <strong>
               {!! $product->size_id ? $product->size->name : '' !!}&nbsp;
             </strong>
            </h1>
        </td>
      </tr>
    </table> 
  </body>
</html