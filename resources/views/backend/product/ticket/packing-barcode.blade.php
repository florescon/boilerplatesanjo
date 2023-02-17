<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{!! $product->full_name ?? '' !!}</title>

<style type="text/css">

  @page {
    margin:10;padding:0.9; // you can set margin and padding 0 
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
  h3 {
    font-size: 50px;
  }
  .rotate {
/*    background-color: transparent;*/
    transform: rotate(-90deg);
/*    padding-right: 100px;*/
/*    margin-top:110px;*/
  }
</style>

</head>
  <body>
    <table width="780">

      <tr>
        <td  style=" width: 10px; ">
          <h3 class="rotate" style="display: inline-block;  margin-right: -50px; margin-left: -50px;">
              <img src="{{ public_path('img/bacaloni.png') }}" alt="" width="230px"/>
          </h3>

        </td>
        <td align="center" colspan="2" style="">
          <div style="padding-right: 5px; padding-left: 0px; padding-top: 15px;">
            <img src="data:image/png;base64,{{   DNS1D::getBarcodePNG($product->code_label, 'C39',1,33,array(1,1,1), false)  }}"  style="        
                  /*position: relative;*/
              margin-top: 20px;
              height:170px;
              /*padding-bottom: 0;*/
              width: 65%;
              /*overflow: hidden;*/
              /*border: 1px solid;*/
              " 
              alt="barcode"
            />
            <br>
            <i style="font-size: 40px;"><strong>{{ $product->code_label }}</strong></i>
          </div>
        </td>
      </tr>    

      <tr>
        <td align="center" colspan="2" width="85%" style="">
            <h2 style="">
              {{ $product->parent->name }}
              <br>
               {{ $product->color_id ? $product->color->name : '' }}
            </h2>
        </td>
        <td align="center" width="15%" style="">
              <strong style="padding-left: 0; font-size: 65px">
               {!! $product->size_id ? $product->size->name : '' !!}&nbsp;
             </strong>
        </td>
      </tr>

    </table> 
  </body>
</html