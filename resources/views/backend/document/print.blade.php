<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Flores">
  <!-- Site Title -->
  <title>{{ $document->title }}</title>
  <link rel="stylesheet" href="{{ asset('/css_custom/ivonne.css') }}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/ga/san2.png')}}">
</head>

<body>
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">


        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="cs-invoice_left">
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('File') No:</b> #PCH{{ $document->id }} </p>
          </div>

          <div class="tm-align-item-center">
            <div class="tm-button-gray tm-border">
              <a href="">{{ generated() }}</a>
            </div>
          </div>

          <div class="cs-invoice_right cs-text_right">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo2.svg') }}" width="80" alt="Logo"></div>
          </div>
        </div>
        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left">
            <b class="cs-primary_color">@lang('Comment'):</b>
            <p>{{ $document->comment }}</p>
          </div>
          <div class="cs-invoice_right cs-text_right">
            {{-- <b class="cs-primary_color">@lang('Customer')</b> --}}
            <p>

            </p>
          </div>
        </div>
        <div class="cs-ticket_wrap cs-mb20">
          <div class="cs-ticket_left">
            <ul class="cs-grid_row cs-col_3 cs-mb30">
              <li>
                <span class="cs-title_1 cs-semi_bold">@lang('Width'):</span><br>
                <span class="cs-primary_color">{{ $document->width }} mm</span>
              </li>
              <li>
                <span class="cs-title_1 cs-semi_bold">@lang('Height'):</span><br>
                <span class="cs-primary_color">{{ $document->height }} mm </span>
              </li>
              <li>
                <span class="cs-title_1 cs-semi_bold">@lang('Updated at'):</span><br>
                <span class="cs-primary_color">{{ $document->date_for_humans_updated }}</span>
              </li>
            </ul>
          </div>
          <div class="cs-ticket_right">
            <div class="cs-box cs-style1 cs-text_center">
              <p class="cs-mb5">@lang('Title')</p>
              <p class="cs-accent_color cs-f16 cs-bold cs-mb0">{{ $document->title }}</p>
            </div>
          </div>
        </div>

        <div class="cs-box2_wrap cs-mb30">
          <div class=" cs-box cs-style2">
            <div class="cs-table cs-style2" style="text-align: center;">
              @if($document->image)
                <img src="{{ asset('/storage/' . $document->image) }}" width="100%">
              @endif
            </div>
          </div>
          <div class=" cs-box cs-style2">
            <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb5">@lang('Information')</p>
            <div class="cs-table cs-style2">
              <table>
                <tbody>
                  @if($document->stitches != 0)
                    <tr style="background: rgba(42, 209, 157, 0.1); text-align: center;">
                      <td><b class="cs-primary_color cs-semi_bold" style="background: rgba(42, 209, 157, 0.1);">@lang('Stitches'):</b> {{ number_format($document->stitches, 0, '', ',') }} </td>
                    </tr>
                  @endif

                  @if($document->ppm != 0)
                    <tr style="background: rgba(42, 209, 157, 0.1); text-align: center;">
                      <td><b class="cs-primary_color cs-semi_bold" style="background: rgba(42, 209, 157, 0.1);">@lang('PPM'):</b> {{ number_format($document->ppm, 0, '', ',') }} </td>
                    </tr>
                  @endif

                  @if($document->lapse)
                    <tr style="background: rgba(42, 209, 157, 0.1); text-align: center;">
                      <td><b class="cs-primary_color cs-semi_bold" style="background: rgba(42, 209, 157, 0.1);">@lang('Time'):</b> {{ $document->lapse }} </td>
                    </tr>
                  @endif

                  @foreach($document->doc_threads->sortBy(['thread.name', 'asc']) as $key => $getThread)
                  <tr>
                    <td>
                      <b class="cs-semi_bold" style="color: blue;">
                        {{ $key+1 }}.
                      </b>
                      <b class="cs-primary_color cs-semi_bold">  {{ $getThread->thread->code }} </b> {{ $getThread->thread->name }} 
                      <em class="cs-f10">{{ $getThread->thread->vendor->short_name_or_name }}</em>
                    </td>
                  </tr>
                  @endforeach
                  <tr>
                    <td><b class="cs-primary_color cs-semi_bold"> </b> </td>
                  </tr>
                  <tr>
                    <td><b class="cs-primary_color cs-semi_bold"> </b> </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="cs-invoice_btns cs-hide_print">
        <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>@lang('Print')</span>
        </a>
      </div>
    </div>
  </div>

  <script src="{{ asset('/js_custom/vendor.min.js') }}"></script>
  <script src="{{ asset('/js_custom/app-invoice-print.js') }}"></script>

  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14
          , height: 14
        });
      }
    })
  </script>

</body>
</html>