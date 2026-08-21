@push('after-styles')
<style>
    .compact-table {
    font-size: 13px;
    margin-bottom: 15px !important;
}

.compact-table th,
.compact-table td {
    padding: 2px 3px !important;
    line-height: 1.1;
    white-space: nowrap;
}

.compact-table thead th {
    border-top: 0;
}

.compact-table tbody td {
    border: 1px solid #ddd;
}

</style>
@endpush

<div>
    <div class="container-fluid">
      <div class="row" >
        <main class="col-md-9" style="margin-right: 320px;">
            {{-- @json($selectedStatus) --}}
        </main>

        <aside class="position-fixed bg-light border-left" style="right: 0%; top: 19%; width: 18%; height: calc({{ $floatButton ? '73vh' : '10vh' }}); overflow-y: auto; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">


          <div class="p-3">

            <div class="alert text-center" role="alert">
                

            </div>

            <a class="text-center btn btn-block btn-primary mb-2" target="_blank" href="{{ route('admin.order.edit_chart', $batchOne->order_id) }}"> Orden {{ $batchOne->order_id }} </a>


            <a class="form-control text-center" target="_blank"  href="{{ route('admin.batch.report', $batchOne->id) }}">
              Reporte del Lote
            </a>

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


        @if($batchOne->status_name === 'pending')

            <div
                id="unitShapes"
                wire:ignore
            ></div>

        @else

        <div class="alert alert-primary" role="alert">
          Lote completado
        </div>

        @endif

            {{-- <div
                id="batchChart"
                wire:ignore
            ></div> --}}

                 
            <div class="d-flex gap-2">
                <select class="form-control" wire:model="selectedOperation">
                    <option value="NoLink">Nueva Operación 👈</option>

                    @foreach(\App\Models\Operation::orderBy('name')->get() as $op)
                        <option value="{{ $op->id }}" style="color:#0071c5;">
                            {{ ucfirst($op->name) }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="d-flex gap-2">
                <label>Agregar después de:</label>

                <select class="form-control" wire:model="selectedAfterOperation">
                    <option value="">Selecciona</option>

                    @foreach($batchOne->getUniqueOperation() as $op)
                        @if(!$loop->last)
                            <option value="{{ $op->sequence }}">
                                {{ ucfirst($op->operation_name) }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <button
                type="button"
                class="btn btn-primary "
                wire:click="saveOperation"
                wire:loading.attr="disabled"
            >
                Guardar
            </button>

          </div>
        </aside>

      </div>
    </div>

    <div class="row" >
        <div class="col-sm-10 " style="margin-left: -30px;">

        @if($batchOne->status_name === 'pending')
            <div class="table-responsive px-4">
                <table class="table table-bordered table-striped table-sm text-center compact-table">
                    <thead class="thead-dark">
                        <tr>
                            @foreach($chartShapes['labels'] as $label)
                                <th>
                                    {{ __(ucfirst($label)) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            @foreach($chartShapes['series'] as $value)
                                <td>
                                    {{ $value }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

            <!-- NAV TABS -->
            <ul class="nav nav-tabs px-4" id="operationsTab" role="tablist">
                @foreach($batchOne->getUniqueOperation() as $op)
                    <li class="nav-item" role="presentation">
                        <button
                            type="button"
                            wire:click="selectOperation({{ $op->operation_id }})"
                            class="nav-link {{ (int) $activeOperation === (int) $op->operation_id ? 'active text-danger bg-white' : '' }}"
                        >
                            {{ ucfirst($op->operation_name) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        
        <!-- CONTENIDO DE LAS TABS -->
        <div class="tab-content" id="operationsTabContent">

            @foreach($batchOne->getUniqueOperation() as $key => $op)

                @if((int) $activeOperation === (int) $op->operation_id)

                    @php
                        $isActive = $activeOperation == $op->operation_id
                            || ($activeOperation === null && $key === 0);
                    @endphp

                    <div
                        class="tab-pane fade show active"
                        id="operation-{{ $op->operation_id }}"
                        role="tabpanel"
                    >
                    <div class="card-body ">
                            <div class="card bd-callout bd-callout-primary {{
                                $op->total_expected != 0 &&
                                $op->total_processed != 0 &&
                                $op->total_delivered != 0 &&
                                $op->total_processed == $op->total_expected &&
                                $op->total_delivered == $op->total_expected
                                    ? 'bg-secondary'
                                    : ''
                            }}">
                            <!-- HEADER -->
                            <div
                                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3"
                            >

                                <h2 class="mb-0 font-weight-bolder text-primary" >
                                    {{ ucfirst($op->operation_name) }}
                                </h2>

                                <div class="d-flex flex-wrap gap-2">

                                    <div class="btn-group mb-2 mt-2 btn-sm" role="group" aria-label="Estados">


                        <button
                            wire:click="selectStatus('processed', {{ $op->operation_id }})"
                            class="btn {{
                                ($selectedStatus[$op->operation_id] ?? 'processed') === 'processed'
                                    ? 'btn-primary'
                                    : 'btn-outline-primary'
                            }} btn-sm"
                        >
                            Producción
                        </button>

                        <button
                            wire:click="selectStatus('received', {{ $op->operation_id }})"
                            class="btn {{
                                ($selectedStatus[$op->operation_id] ?? 'processed') === 'received'
                                    ? 'btn-primary'
                                    : 'btn-outline-primary'
                            }} btn-sm"
                        >
                            Recibido
                        </button>

                        <button
                            wire:click="selectStatus('delivered', {{ $op->operation_id }})"
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
                                        {{-- @json($op->operation_id) --}}
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

        @endif

            @endforeach

        </div>
        </div>
    </div>
</div>


@push('after-scripts')

<script>
document.addEventListener('livewire:load', function () {

    const el = document.querySelector('#unitShapes');

    if (!el) {
        return;
    }

    const unit = new ApexCharts(el, {
        chart: {
            type: 'unit',
            height: 280
        },
        series: @json($chartShapes['series']),
        labels: @json($chartShapes['labels']),
        plotOptions: {
            unit: {
                layout: 'custom',
                positions: tree,
                transition: 'flow'
            }
        }
    });

    unit.render();

    Livewire.on('unitShapes', function (data) {

        unit.updateSeries(data.series);

        unit.updateOptions({
            labels: data.labels
        });

    });

});
</script>

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
                tickAmount: 2
            }
        });

    });

});
</script>
@endpush