@extends('backend.layouts.app')

@section('title', __('Report batch'))

@push('after-styles')

<style>


.page{
    background:#fff;
    width:8.5in;
    min-height:11in;
    margin:20px auto;
    padding:25px;
    box-shadow:0 0 10px rgba(0,0,0,.15);
}

.section-title{
    border-bottom:2px solid #dee2e6;
    margin-bottom:15px;
    padding-bottom:5px;
    font-weight:bold;
}

.metric{
    border:1px solid #dee2e6;
    border-radius:5px;
    padding:15px;
    text-align:center;
    margin-bottom:15px;
    height:100%;
}

.metric h6{
    color:#6c757d;
    margin-bottom:8px;
}

.metric h4{
    font-weight:bold;
}

.chart{
    border:2px dashed #adb5bd;
    height:180px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#6c757d;
    margin-bottom:20px;
}

.footer{
    margin-top:40px;
    text-align:right;
    color:#777;
    font-size:12px;
}

@media print{

    body{
        background:white;
    }

    .page{
        margin:0;
        box-shadow:none;
        width:auto;
        min-height:auto;
    }

}

@media(max-width:768px){

    .page{
        width:100%;
        padding:15px;
    }

}

@media print {

    .container {
        width: 100% !important;
        max-width: 100% !important;
    }

    .row {
        display: flex !important;
        flex-wrap: wrap !important;
    }

    .col-md-3 {
        flex: 0 0 25% !important;
        max-width: 25% !important;
    }

    .card {
        border: 1px solid #000 !important;
        page-break-inside: avoid;
        margin-bottom: 15px;
    }

    .btn {
        display: none; /* Oculta botones al imprimir */
    }

    .card-img-top {
        max-height: 120px;
        object-fit: cover;
    }
}

/* Vista normal */
.card {
    height: 100%;
}

</style>

@endpush

@section('content')

<div class="page">

    <!-- Encabezado -->
    <div class="text-center mb-4">
        <h2>
          Reporte Lote #{{ $batch->id }}, Orden: #{{  $batch->order->folio_or_id_clear  }}
        </h2>

        <div><strong>{{ __(appName()) }}</strong></div>

        <div><strong>Creado:</strong> {{ $batch->created_at }}</div>

        <div>Reporte {{ generated() }}</div>
    </div>

    <!-- Indicadores -->
    <div class="section-title">
        Indicadores Principales
    </div>

                  @php
                      $progress = $batch->getProgressBatch();
                  @endphp

                  @if($batch->status_name == 'pending')
                  <div class="progress mb-3" >
                      <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progress->processed }}%;" aria-valuenow="20" aria-valuemin="0"
                          aria-valuemax="100">Procesado</div>
                  </div>
                  <div class="progress mb-3" >
                      <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress->delivered }}%;" aria-valuenow="10" aria-valuemin="0"
                          aria-valuemax="100">Entregado</div>
                  </div>
                  @endif

    <div class="row">

        <div class="col-6 col-md-3">
            <div class="metric">
                <h6>Total Lote</h6>
                <h4>{{ $batch->total_batch }}</h4>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric">
                <h6> Completado </h6>
                <h4>{{ $batch->total_batch_delivered }}</h4>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric">
                <h6>Pendiente Producir</h6>
                <h4>{{ $batch->total_batch_pending_processed }}</h4>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric">
                <h6>Tiempo transcurrido</h6>
                <h4> {{ $batch->average_processing_time }} </h4>
            </div>
        </div>

    </div>

    <div class="row ">


      @foreach($batch->operationTotals()->get() as $operation)
          <div class="col-6 col-md-3 pt-3">
              <div class="metric">
                  <h6>{{ ucfirst($operation->operation_name) }}</h6>
                  <em>
                      Esperado: {{ $operation->total_expected }} <br>
                      Procesado: {{ $operation->total_processed }} <br>
                      Recibido: {{ $operation->total_received }} <br>
                      Entregado: {{ $operation->total_delivered }}
                  </em>
              </div>
          </div>
      @endforeach


    </div>

      @foreach($parents as $parentId => $parentName)
        <div class="card-body">
            <h5 class="card-title font-weight-bolder">
                {!! $parentName !!}
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <td class="border-top-0">
                                
                            </td>
                            @foreach($sizesByParent[$parentId] ?? [] as $sizeT)

                                <td class="text-center"
                                    style="border:3px solid;border-style:dotted">
                                    {{ $sizeT->name }}
                                </td>

                            @endforeach
                            <td class="text-center table-dark">
                                Total
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($colors as $items)
                        @if(!isset($matrix[$parentId][$items->id]))
                            @continue
                        @endif
                        <tr>
                            <td>
                                {{ $items->name }}
                            </td>
                            {{-- CANTIDADES --}}
                            @foreach($sizesByParent[$parentId] ?? [] as $sizeM)
                                <td class="text-center"
                                    style="border:1px solid">
                                    @if(isset($matrix[$parentId][$items->id][$sizeM->id]))
                                        @php
                                            $batchItem = $matrix[$parentId][$items->id][$sizeM->id];
                                        @endphp
                                        {{ $batchItem->qty }}
                                    @endif
                                </td>
                            @endforeach

                            {{-- TOTAL COLOR --}}

                                <td class="text-center font-weight-bolder table-dark">
                                    {{ $totals[$parentId]['colors'][$items->id] ?? 0 }}
                                </td>
                        </tr>
                    @endforeach
                    <tr class="table-dark">
                        <td></td>
                        @foreach($sizesByParent[$parentId] ?? [] as $sizeB)
                            <td class="text-center font-weight-bolder">
                                {{ $totals[$parentId]['sizes'][$sizeB->id] ?? 0 }}
                            </td>
                        @endforeach
                        <td class="text-center text-danger font-weight-bolder">
                            {{ $totals[$parentId]['product'] ?? 0 }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
      @endforeach

    <!-- Graficas -->
{{-- 
    <div class="section-title pt-3">
        Indicadores Gráficos
    </div>

    <div class="chart">
        Gráfica de Ventas
    </div>

    <div class="chart">
        Gráfica de Utilidad
    </div>

    <div class="chart">
        Gráfica de Inventario
    </div>

    <!-- Información -->

    <div class="row">

        <div class="col-md-6">

            <div class="card ">

                <div class="card-header">
                    Mano de obra
                </div>

                <div class="card-body">

                    <ol class="mb-0">
                        <li>Producto A</li>
                        <li>Producto B</li>
                        <li>Producto C</li>
                    </ol>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card ">

                <div class="card-header bg-warning">
                    Maquilas
                </div>

                <div class="card-body">

                    <ul class="mb-0">
                        <li>12 productos con stock crítico.</li>
                        <li>5 facturas vencidas.</li>
                        <li>3 pedidos pendientes.</li>
                    </ul>

                </div>

            </div>

        </div>

    </div>

    <!-- Resumen -->

    <div class="card mt-4">

        <div class="card-header">
            Materia Prima
        </div>

        <div class="card-body">

            <ul class="mb-0">
                <li>Las ventas aumentaron un 8% respecto al período anterior.</li>
                <li>La utilidad creció un 5%.</li>
                <li>El inventario crítico se concentra en 12 productos.</li>
            </ul>

        </div>

    </div> --}}

    <div class="footer">
      Reporte Lote #{{ $batch->id }}, Orden: {!! $batch->order->folio_or_id !!}
    </div>

</div>


@endsection

@push('after-scripts')
@endpush
