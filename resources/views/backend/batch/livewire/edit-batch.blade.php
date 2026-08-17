<div>
    <div class="container-fluid">
      <div class="row" >
        <main class="col-md-9" style="margin-right: 320px;">
        </main>

        <aside class="position-fixed bg-light border-left" style="right: 0%; top: 19%; width: 18%; height: calc({{ $floatButton ? '73vh' : '10vh' }}); overflow-y: auto; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">


          <div class="p-3">

              {{-- <button wire:click="$toggle('floatButton')" class="btn btn-outline-primary btn-sm btn-lg btn-block mb-3">
                @if(!$floatButton)
                  <i class="cil-fullscreen"></i>
                @else
                  <i class="cil-fullscreen-exit"></i>
                @endif
              </button> --}}

                <div class="alert text-center" role="alert">
                    

                </div>

                <a class="text-center btn btn-block btn-primary mb-2" target="_blank" href="{{ route('admin.order.edit_chart', $batchOne->order_id) }}"> Orden {{ $batchOne->order_id }} </a>


                <a class="form-control text-center" target="_blank"  href="{{ route('admin.batch.report', $batchOne->id) }}">
                  Reporte del Lote
                </a>


                {{-- @json($selectedStatus) --}}
                {{-- <br> --}}
                {{-- @json($activeOperation) --}}

                <select id="redirectSelect" class="form-control mb-4 shadow-sm">
                    <option value="NoLink">Seleccionar Lote</option>
                    @foreach(\App\Models\Batch::orderBy('id')->where('order_id', $batchOne->order_id)->get() as $s)
                      <option style="color:#0071c5;" value="{{ route('admin.batch.edit', $s->id)}}">
                        <strong>
                          #{{ $s->id }} 
                          [{{ $s->total_batch }}]
                          @if($batchOne->id == $s->id)
                            <span class="badge badge-primary text-danger">Actual</span>
                          @endif
                        </strong>
                      </option>
                    @endforeach
                </select>


        <div
            id="batchChart"
            wire:ignore
        ></div>
 


          </div>
        </aside>

      </div>
    </div>
    <div class="row" >
      <div class="col-sm-10 " style="margin-left: -30px;">
    

<!-- NAV TABS -->
<ul class="nav nav-tabs" id="operationsTab" role="tablist">

    @foreach($batchOne->getUniqueOperation() as $key => $op)

        @php
            $isActive = $activeOperation == $op->operation_id
                || ($activeOperation === null && $key === 0);
        @endphp

        <li class="nav-item" role="presentation">

            <a
                class="nav-link {{ $isActive ? 'active' : '' }}"
                id="operation-{{ $op->operation_id }}-tab"
                data-toggle="tab"
                href="#operation-{{ $op->operation_id }}"
                role="tab"
                aria-controls="operation-{{ $op->operation_id }}"
                aria-selected="{{ $isActive ? 'true' : 'false' }}"
            >
                {{ ucfirst($op->operation_name) }}
            </a>

        </li>

    @endforeach

</ul>

<!-- CONTENIDO DE LAS TABS -->
<div class="tab-content" id="operationsTabContent">

    @foreach($batchOne->getUniqueOperation() as $key => $op)

        @php
            $isActive = $activeOperation == $op->operation_id
                || ($activeOperation === null && $key === 0);
        @endphp

        <div
            class="tab-pane fade {{ $isActive ? 'show active' : '' }}"
            id="operation-{{ $op->operation_id }}"
            role="tabpanel"
            aria-labelledby="operation-{{ $op->operation_id }}-tab"
        >
            <div class="card-body">
                <div class="card bd-callout bd-callout-primary">

                    <!-- HEADER -->
                    <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3"
                        style="color: #29ED22;"
                    >

                        <h2 class="mb-0 font-weight-bolder">
                            {{ ucfirst($op->operation_name) }}
                        </h2>

                        <div class="d-flex flex-wrap gap-2">

<div class="btn-group mb-2 mt-2 btn-sm" role="group" aria-label="Estados">

    <button
        wire:click="selectStatus('processed', '{{ $op->operation_id }}')"
        class="btn {{
            ($selectedStatus[$op->operation_id] ?? 'processed') === 'processed'
                ? 'btn-primary'
                : 'btn-outline-primary'
        }} btn-sm"
    >
        Producción
    </button>

    <button
        wire:click="selectStatus('received', '{{ $op->operation_id }}')"
        class="btn {{
            ($selectedStatus[$op->operation_id] ?? 'processed') === 'received'
                ? 'btn-primary'
                : 'btn-outline-primary'
        }} btn-sm"
    >
        Recibido
    </button>

    <button
        wire:click="selectStatus('delivered', '{{ $op->operation_id }}')"
        class="btn {{
            ($selectedStatus[$op->operation_id] ?? 'processed') === 'delivered'
                ? 'btn-primary'
                : 'btn-outline-primary'
        }} btn-sm"
    >
        Terminado
    </button>

</div>

                            <a href="#" class="btn btn-outline-info btn-sm ml-4 mt-2 mb-2">
                                Do something
                            </a>

                        </div>
                    </div>


                    <!-- PARENTS -->
                    @foreach($parents as $parentId => $parentName)

                        <div class="card-body">

                            <h5 class="card-title font-weight-bolder">
                                {!! $parentName !!}
                            </h5>

                            <div class="table-responsive">

                                <table class="table table-sm table-hover">

                                    <thead>
                                        <tr>

                                            <td class="border-top-0"></td>

                                            @foreach($sizesByParent[$parentId] ?? [] as $sizeT)
                                                <td
                                                    style="border: 3px solid; border-style: dotted;"
                                                    class="text-center"
                                                >
                                                    {{ $sizeT->name }}
                                                </td>
                                            @endforeach

                                            <td class="text-center table-dark">
                                                Total
                                            </td>

                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($colors as $colorId => $items)

                                            @if(!isset($matrix[$op->operation_id][$parentId][$items->id]))
                                                @continue
                                            @endif

                                            <tr>

                                                <!-- COLOR -->
                                                <td>
                                                    {{ $items->name }}
                                                </td>


                                                <!-- TALLAS -->
                                                @foreach($sizesByParent[$parentId] ?? [] as $sizeM)

                                                    <td
                                                        class="text-center"
                                                        style="border: 1px solid;"
                                                    >

                                                        @if(isset($matrix[$op->operation_id][$parentId][$items->id][$sizeM->id]))

                                                            <div class="d-inline-block position-relative">

                                                                @php
                                                                    $batchItem = $matrix[$op->operation_id][$parentId][$items->id][$sizeM->id];
                                                                @endphp

                                                                {{ $batchItem->qty }}

                                                                <input
                                                                    type="number"

                                                                    {{-- @if($batchItem->{$selectedStatus} == $batchItem->qty)
                                                                        disabled
                                                                    @endif --}}

                                                                    @php
                                                                        $currentStatus = $selectedStatus[$op->operation_id] ?? 'processed';
                                                                    @endphp

                                                                    @if($batchItem->{$currentStatus} == $batchItem->qty)
                                                                        disabled
                                                                    @endif                                                                    

                                                                    style="width: 70px; color: blue;"

                                                                    placeholder="{{ $batchItem->{$currentStatus} }}"                                                                    
                                                                    {{-- placeholder="{{ $batchItem->{$selectedStatus} }}" --}}

                                                                    class="text-center form-control-sm form-control
                                                                    @error('quantities.' . $op->operation_id . '.' . $batchItem->id)
                                                                        is-invalid
                                                                    @enderror"

                                                                    min="1"

                                                                    max="{{ $batchItem->qty ?? 0 }}"

                                                                    wire:model.lazy="quantities.{{ $op->operation_id }}.{{ $batchItem->id }}"

                                                                    name="quantities[{{ $batchItem->id }}]"
                                                                >

                                                            </div>

                                                        @endif

                                                    </td>

                                                @endforeach


                                                <!-- TOTAL COLOR -->
                                                <td class="text-center font-weight-bolder table-dark">
                                                    {{ $totals[$op->operation_id][$parentId]['colors'][$items->id] ?? 0 }}
                                                </td>

                                            </tr>

                                        @endforeach


                                        <!-- TOTAL -->
                                        <tr class="table-dark">

                                            <td></td>

                                            @foreach($sizesByParent[$parentId] ?? [] as $sizeB)

                                                <td class="text-center font-weight-bolder">
                                                    {{ $totals[$op->operation_id][$parentId]['sizes'][$sizeB->id] ?? 0 }}
                                                </td>

                                            @endforeach

                                            <td class="text-center text-danger font-weight-bolder">
                                                {{ $totals[$op->operation_id][$parentId]['product'] ?? 0 }}
                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                            <small class="text-muted">
                                Last updated 3 mins ago
                            </small>

                        </div>

                    @endforeach


                    <!-- GUARDAR -->
                    <div class="text-right col" wire:ignore>

                        <button
                            wire:click="messageAlert('save', '{{ $op->operation_id }}')"
                            wire:loading.attr="disabled"
                            class="btn btn-sm btn-primary btn-hover"
                        >

                            <span
                                wire:loading.remove
                                wire:target="messageAlert('save', '{{ $op->operation_id }}')"
                            >
                                <i class="fas fa-save"></i>
                                Guardar captura

                                <h5 class="d-inline">
                                    <span
                                        class="badge badge-light text-dark"
                                        id="total-{{ $op->operation_id }}"
                                    >
                                        0
                                    </span>
                                </h5>
                            </span>


                            <span
                                wire:loading
                                wire:target="messageAlert('save', '{{ $op->operation_id }}')"
                            >
                                <span
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                ></span>

                                Guardando...
                            </span>

                        </button>

                    </div>

                </div>
            </div>

        </div>

    @endforeach

</div>
      </div>
    </div>
</div>


@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {

    let operationId = $(e.target)
        .attr('href')
        .replace('#operation-', '');

    @this.set('activeOperation', operationId);
});
</script>
<script>
document.addEventListener('livewire:load', function () {

    let batch = new ApexCharts(
        document.querySelector("#batchChart"),
        {
            chart: {
                type: 'bar',
                height: 300,
                stacked: true,
            },

            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: 20
                },
            },

            series: [
                {
                    name: 'Avance',
                    data: @json($chartData['avance'])
                },
            ],

            xaxis: {
                categories: @json($chartData['categories']),
                min: 0,
                max: 100,
                tickAmount: 2,
                labels: {
                    formatter: function (value) {
                        return value + '%';
                    }
                }
            },

            dataLabels: {
                enabled: false
            },

            stroke: {
                width: 1
            },

            colors: ['#008FFB', '#E5E7EB'],

            tooltip: {
                y: {
                    formatter: function (value) {
                        return value + '%';
                    }
                }
            }
        }
    );

    batch.render();

    Livewire.on('batchChart', function(data) {

        batch.updateSeries([
            {
                name: 'Avance',
                data: data.avance
            },
        ]);

        batch.updateOptions({
            xaxis: {
                categories: data.categories,
                min: 0,
                max: 100,
                tickAmount: 10
            }
        });

    });

});
</script>
@endpush