
<div class="cs-container" style="{{ $width ? 'max-width: 2080px;' : '' }}">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_btns cs-hide_print mb-2">   

        <a href="{{ route('admin.dashboard') }}" class="cs-invoice_btn cs-color1" style="margin-right: 10px;">
          <span>&nbsp; @lang('Home')</span>
        </a>

        <button wire:click="$toggle('width')" class="cs-invoice_btn {{ !$width ? '' : 'cs-color1' }}">
          @if(!$width)
            @lang('Fullscreen')
          @else
            @lang('Exit Fullscreen')
          @endif
        </button>

        <a href="javascript:window.print()" class="cs-invoice_btn cs-color2">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>@lang('Print')</span>
        </a>
      </div>

      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb10">

          <div class="cs-invoice_left" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/logo2.svg') }}" width="100" alt="Logo"></div>
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f18"><b class="cs-primary_color">{{ $order->type_order_clear }} No:</b> #{{ $order->characters_type_order }}{!! $order->folio_or_id !!}</p>
          </div>
          <div class="cs-invoice_right cs-text_center">
            <b class="cs-primary_color cs-f18">{{ __(appName()) }}</b>
            <p class="cs-f18">
              {{ setting('site_address') }} <br/>
              {{ setting('site_email') }} <br/>
              {{ setting('site_whatsapp') }}
            </p>
          </div>

          <div class="cs-invoice_right cs-text_right" style="padding-bottom: 25px;">
            <div class="cs-logo cs-mb5"><img src="{{ asset('img/bacapro.png') }}" width="130" alt="Logo"></div>
          </div>

        </div>

        @if(!$width)
          <div class="alert alert-primary text-center cs-hide_print" role="alert">
            Si las columnas exceden el ancho de página, seleccione 'Pantalla Completa'
          </div>
        @endif

        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="legend">
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">@lang('File generated'):</b> 
              {{ now()->isoFormat('D, MMM, YY - h:mm a') }}
          </div>
          <div class="cs-invoice_right cs-text_right cs-hide_print">


          </div>
        </div>

        <div class="cs-style1 cs-f18 cs-primary_color cs-mb10 cs-semi_bold">@lang('Customer Information')</div>
        <ul class="cs-grid_row cs-col_3 cs-f18 cs-mb5">
          <li>
            <p class="cs-mb10"><b class="cs-primary_color">@lang('Customer'):</b> <br><span class="cs-primary_color">{{ optional($order->user)->name . optional($order->departament)->name }}</span></p>
          </li>
          <li>
            @if(optional($order->user)->customer)
              @if(optional($order->user)->customer['phone'])
                <p class="cs-mb10"><b class="cs-primary_color">@lang('Phone'):</b> <br><span class="cs-primary_color">{!! optional($order->user)->customer['phone'] ?? '' !!}</span></p>
              @endif
            @endif

            @if(optional($order->user)->customer)
              @if(optional($order->user)->customer['address'])
                <p class="cs-mb10"><b class="cs-primary_color">@lang('Address'):</b> <br><span class="cs-primary_color">{!! optional($order->user)->customer['address'] ?? '' !!}</span></p>
              @endif
            @endif
          </li>
          @if(optional($order->user)->customer)
            @if(optional($order->user)->customer['rfc'])
            <li>
              <p class="cs-mb10"><b class="cs-primary_color">@lang('RFC'):</b> <br><span class="cs-primary_color">{!! optional($order->user)->customer['rfc'] ?? '' !!}</span></p>
            </li>
            @endif
          @endif

        </ul>

        <div class="cs-invoice_head cs-f18">
          <div class="cs-invoice_right">
            @if($order->info_customer)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Info customer'):</b> <br>{{ $order->info_customer }}</p>
            @endif
          </div>
        </div>

        @if($order->comment)
          <div class="cs-invoice_head cs-f18">
            <div class="cs-invoice_right">
              <b class="cs-primary_color">@lang('Comment'):</b>
              <p class="cs-mb8">{{ $order->comment ?? '--'}}</p>
            </div>
          </div>
        @endif

        <div class="cs-invoice_head cs-f18">
          <div class="cs-invoice_right cs-text_center">
            <p><b class="cs-primary_color cs-semi_bold">@lang('Date Issued'): <br>{{ $order->date_entered_or_created }}</p></b>
          </div>
          @if(!$order->isQuotation() && $order->quotation !== 0)
            <div class="cs-invoice_right cs-text_center">
                <p><b class="cs-primary_color cs-semi_bold">@lang('Quotation'): <br>{{ $order->quotation }}</p></b>
            </div>
          @endif
          <div class="cs-invoice_right cs-text_center">
            @if($order->request)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Request number'): <br>{{ $order->request ?? '' }}</p></b>
            @endif
          </div>
          <div class="cs-invoice_right cs-text_center">
            @if($order->purchase)
              <p><b class="cs-primary_color cs-semi_bold">@lang('Purchase order'): <br>{{ $order->purchase ?? '' }}</p></b>
            @endif
          </div>
          @if($order->invoice)
            <div class="cs-invoice_right cs-text_center">
                <p><b class="cs-primary_color cs-semi_bold">@lang('Invoice'):<br>{{ $order->invoice ?? '' }}</p></b>
            </div>
          @endif
        </div>


        <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold" style="margin-top: 20px;">@lang('Details')



        </div>
        <div style="page-break-inside:avoid;">

          
          <div id="sankey" wire:ignore></div>


          <div class="cs-note cs-f18">
            <div class="cs-note_right" style="margin-left: 20px;">
              <p class="cs-mb0"><b class="cs-primary_color cs-bold">@lang('Note'): 
                {!! $order->isQuotation() && ($order->branch_id == 0) ? setting('footer_quotation_production').'<br>' :'' !!}
              </b></p>
            </div>
          </div><!-- .cs-note -->
        </div>
      </div>
    </div>


</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/sankey.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script>
document.addEventListener('livewire:load', function () {

    let chart = null;

    function renderSankey(data) {

        console.log('Sankey data:', data);

        const container = document.getElementById('sankey');

        if (!container) {
            console.error('No existe #sankey');
            return;
        }

        if (!data) {
            console.error('No llegaron datos al Sankey');
            return;
        }

        if (!data.nodes || !data.edges) {
            console.error('Formato inválido:', data);
            return;
        }

        console.log('Nodes:', data.nodes);
        console.log('Edges:', data.edges);

        /*
        |--------------------------------------------------------------------------
        | Convertir edges de Laravel al formato Highcharts
        |--------------------------------------------------------------------------
        */

        const sankeyData = data.edges.map(edge => [
            edge.source,
            edge.target,
            Number(edge.value)
        ]);

        /*
        |--------------------------------------------------------------------------
        | Convertir nodes
        |--------------------------------------------------------------------------
        */

        const sankeyNodes = data.nodes.map(node => ({
            id: node.id,
            name: node.title,
            color: node.color
        }));

        /*
        |--------------------------------------------------------------------------
        | Destruir gráfico anterior
        |--------------------------------------------------------------------------
        */

        if (chart) {
            chart.destroy();
            chart = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear Sankey
        |--------------------------------------------------------------------------
        */

        chart = Highcharts.chart('sankey', {

            chart: {
                type: 'sankey',
                backgroundColor: '#fafafa',
                borderColor: '#e2e8f0',
                borderWidth: 10,
                borderRadius: 8,
                height: 1320,

		exporting: {
		    enabled: true
		},
        zooming: {
            type: 'xy'
        },
        panning: {
            enabled: true,
            type: 'xy'
        },
        panKey: 'shift',
                style: {
                    fontFamily: 'Inter, sans-serif',
                    color: 'white',
                }
            },

            title: {
                text: null
            },

            accessibility: {
                enabled: false
            },

            tooltip: {
                pointFormat:
                    '<b>{point.fromNode.name}</b> → ' +
                    '<b>{point.toNode.name}</b>: ' +
                    '<b>{point.weight}</b>'
            },

			plotOptions: {
			    sankey: {
			        nodeWidth: 45,
			        nodePadding: 30,
			        curveFactor: 0.5,

			        linkOpacity: 0.35,

			        dataLabels: {
			            enabled: true,
			            style: {
			                fontFamily: 'Inter, sans-serif',
			                fontSize: '14px',
			                textOutline: '2px contrast',
			                fontWeight: '500',
			                color: 'var(--highcharts-neutral-color-100, #000)',
			            }
			        }
			    }
			},

            series: [{
                type: 'sankey',

                name: 'Material',

                nodes: sankeyNodes,

                data: sankeyData
            }]
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Evento Livewire
    |--------------------------------------------------------------------------
    */

    Livewire.on('graphMaterial', function (data) {

        console.log('Evento graphMaterial recibido');

        renderSankey(data);

    });

    /*
    |--------------------------------------------------------------------------
    | Cargar datos iniciales
    |--------------------------------------------------------------------------
    */

    @this.call('loadSankey');

});
</script>
