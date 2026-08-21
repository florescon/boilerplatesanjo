@push('after-styles')
    <style>
    	.compact-table {
            font-size: 12px;
            margin-bottom: 2px !important;
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

<x-backend.card>
<x-slot name="body">
    <div class="main-content">
      <div class="container">

        <h4>@lang('Bom of Materials') por Cotizaciones</h4>

    <div class="row">

        {{-- Columna del gráfico --}}
        <div class="col-md-6 mb-4">
            {{-- <div id="materials-chart"></div> --}}


        @foreach($tablesData as $parentId => $tableData)

            @php
                $hasNoSize = !empty($tableData['rows'])
                    && !empty($tableData['rows'][0]['no_size']);
            @endphp

            <div class="table-responsive compact-table">

                <div class="product-group mb-1">

                    <h5 class="mb-3">
                        <strong class="text-primary">
                            {{ $tableData['parent_code'] }}
                        </strong>
                        {{ $tableData['parent_name'] }}
                    </h5>

                    <table class="table table-bordered table-sm">

                        <thead>
                            <tr>

                                @if($hasNoSize)
                                    <th>Código</th>
                                @endif

                                <th style="width: 250px !important;">
                                    Color
                                </th>

                                @foreach($tableData['headers'] as $header)
                                    <th class="text-center">
                                        {{ $header['name'] }}
                                    </th>
                                @endforeach

                                @if($hasNoSize)
                                    <th class="text-center"></th>
                                @endif

                                <th class="text-center">
                                    Total
                                </th>

                            </tr>
                        </thead>


                        <tbody>

                            {{-- FILAS DE PRODUCTOS --}}
                            @foreach($tableData['rows'] as $row)

                                <tr>

                                    @if($hasNoSize)
                                        <td style="width: 250px !important;">
                                            {{ $row['general_code'] ?? '' }}
                                        </td>
                                    @endif


                                    <td>
                                        {{ $row['color_product'] ?: 'N/A' }}
                                    </td>


                                    {{-- TALLAS --}}
                                    @foreach($tableData['headers'] as $header)

                                        <td class="text-center">

                                            @if(isset($row['sizes'][$header['id']]))

                                                {!! $row['sizes'][$header['id']]['display'] !!}

                                            @endif

                                        </td>

                                    @endforeach


                                    {{-- PRODUCTO SIN TALLA --}}
                                    @if($hasNoSize)

                                        <td class="text-center">

                                            @if(isset($row['no_size']['display']))
                                                {!! $row['no_size']['display'] !!}
                                            @endif

                                        </td>
    `
                                    @endif


                                    {{-- TOTAL DE LA FILA --}}
                                    <td class="text-center font-weight-bold">

                                        {{ $row['row_quantity'] }}

                                        &nbsp;

                                        <small class="font-italic text-primary">
                                            {{-- {{ $row['row_total_display'] }} --}}
                                        </small>

                                    </td>

                                </tr>

                            @endforeach


                            {{-- FILA DE TOTALES --}}
                            <tr class="table-active">

                                @if($hasNoSize)
                                    <td class="font-weight-bold"></td>
                                @endif


                                <td class="font-weight-bold"></td>


                                {{-- TOTALES POR TALLA --}}
                                @foreach($tableData['headers'] as $header)

                                    <td class="text-center font-weight-bold">

                                        @if(isset($tableData['totals']['size_totals'][$header['id']]))

                                            {{ $tableData['totals']['size_totals'][$header['id']]['quantity'] }}

                                        @endif

                                    </td>

                                @endforeach


                                {{-- TOTAL SIN TALLA --}}
                                @if($hasNoSize)

                                    <td class="text-center font-weight-bold">

                                        @if(
                                            isset($tableData['totals']['no_size_total']['quantity'])
                                            && $tableData['totals']['no_size_total']['quantity'] > 0
                                        )

                                            {{ $tableData['totals']['no_size_total']['quantity'] }}

                                        @endif

                                    </td>

                                @endif


                                {{-- TOTAL GENERAL --}}
                                <td class="text-center font-weight-bold text-danger">

                                    {{ $tableData['totals']['row_quantity'] }}

                                    &nbsp;

                                    <small class="font-italic">
                                        {{-- {{ $tableData['totals']['grand_total'] }} --}}
                                    </small>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        @endforeach
        </div>

        {{-- Columna de la tabla --}}
        <div class="col-md-6 mb-4">

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="row no-gutters">
                        <div class="col-auto pr-4 ">
                            <h2 class="mb-0">{{ $totalProducts }}</h2>
                            <strong>Productos</strong>
                        </div>
                        <div class="col-auto pl-4 mr-5 border-left">
                            <h2 class="mb-0">{{ $totalServices }}</h2>
                            <strong>Servicios</strong>
                        </div>
                        <div class="col-auto pl-4 border-left text-primary">
                            <h2 class="mb-0 text-primary">{{ $quotations->count() }}</h2>
                            <strong>Cotizaciones</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="row no-gutters">
                        <div class="col-auto pr-4 mr-5">
                            <h2 class="mb-0">${{ number_format(priceWithoutIvaIncluded($totalProductsAmount ?? 0), 0) }}</h2>
                            <strong>Importe Total S/IVA</strong>
                        </div>
                        <div class="col-auto pl-2  border-left">
                            <h2 class="mb-0">${{ number_format($totalAcquisitionCost ?? 0, 0) }}</h2>
                            <strong>Costo MP</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <div class="rounded">
                    <div class="table-responsive compact-table">
                        <table class="table table-bordered table-sm table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th class="font-weight-bold text-center" style="color: #2ad19d !important;">
                                        @lang('Family')
                                    </th>

                                    <th class="font-weight-bold text-center" style="color: #2ad19d !important;">
                                        @lang('Code')
                                    </th>

                                    @if($actualStock)
                                        <th class="font-weight-bold text-center" style="color: #2ad19d !important;">
                                            Existencia
                                        </th>
                                    @endif

                                    <th class="font-weight-bold text-center" style="color: #2ad19d !important;">
                                        Requerido
                                    </th>

                                    <th class="font-weight-bold text-center" style="color: #2ad19d !important;">
                                        Faltante
                                    </th>

                                    <th class="font-weight-bold text-center" style="color: #2ad19d !important;">
                                        Costo
                                    </th>

                                    <th class="font-weight-bold" style="color: #2ad19d !important;">
                                        Materia Prima
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($allMaterials as $key => $material)
                                    <tr style="{{ $material['quantity'] == 0 ? 'text-decoration: line-through;' : '' }}">

                                        <td class="text-center">
                                            {{ $material['family'] }}
                                        </td>

                                        <td class="text-center">
                                            {{ $material['part_number'] }}
                                        </td>

                                        @if($actualStock)
                                            <td class="text-center">
                                                {{ $material['stock'] . ' ' . $material['unit_measurement'] }}

                                                @if($material['stock'] < $material['quantity'])
                                                    <span class="badge badge-warning">
                                                        No cumple
                                                    </span>
                                                @endif
                                            </td>
                                        @endif

                                        <td class="text-center">
                                            {{ $material['quantity'] . ' ' . $material['unit_measurement'] }}
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $faltante = max(0, $material['quantity'] - $material['stock']);
                                            @endphp

                                            @if($faltante > 0)
                                                <span class="badge badge-warning">Faltan</span>
                                                {{ $faltante . ' ' . $material['unit_measurement'] }}
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ number_format($material['acquisition_cost_total'], 2)}}
                                        </td>

                                        <td class="text-primary">
                                            {{ $material['material_name'] }}

                                            @if($material['quantity'] == 0)
                                                &nbsp;
                                                <span class="badge badge-danger">
                                                    No considerar
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>
    </div>
</x-slot>
</x-backend.card>


@push('after-scripts')


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/dumbbell.js"></script>

<script>
document.addEventListener('livewire:load', function () {

    // ==========================================
    // CREAR GRÁFICA
    // ==========================================

    const createChart = (materialsData) => {

        Highcharts.chart('materials-chart', {

            chart: {
                type: 'dumbbell',
                inverted: true,

                height: Math.max(
                    500,
                    materialsData.length * 45
                )
            },

            title: {
                text: 'Stock vs Quantity'
            },

            subtitle: {
                text: 'Stock disponible vs cantidad requerida'
            },

            credits: {
                enabled: false
            },

            tooltip: {
                useHTML: true,

                formatter: function () {

                    const point = this.point;

                    const difference =
                        point.custom.quantity -
                        point.custom.stock;

                    const status = difference > 0
                        ? `<span style="color:#dc2626;">
                            ⚠ Faltan ${Highcharts.numberFormat(
                                difference,
                                0,
                                '.',
                                ','
                            )}
                           </span>`
                        : `<span style="color:#16a34a;">
                            ✓ Stock suficiente
                           </span>`;

                    return `
                        <div style="padding:8px">

                            <strong>
                                ${point.name}
                            </strong>

                            <br>

                            <span style="color:#777">
                                ${point.custom.part_number ?? ''}
                            </span>

                            <hr style="margin:6px 0">

                            <div>
                                <strong>Stock:</strong>
                                ${Highcharts.numberFormat(
                                    point.custom.stock,
                                    0,
                                    '.',
                                    ','
                                )}
                            </div>

                            <div>
                                <strong>Quantity:</strong>
                                ${Highcharts.numberFormat(
                                    point.custom.quantity,
                                    0,
                                    '.',
                                    ','
                                )}
                            </div>

                            <hr style="margin:6px 0">

                            ${status}

                        </div>
                    `;
                }
            },

            // ==========================================
            // CATEGORÍAS
            // ==========================================

            xAxis: {
                type: 'category',

                // IMPORTANTE:
                // false = nombres a la IZQUIERDA
                opposite: false,

                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },

            // ==========================================
            // EJE DE VALORES
            // ==========================================

            yAxis: {
                title: {
                    text: 'Cantidad'
                },

                labels: {
                    formatter: function () {
                        return Highcharts.numberFormat(
                            this.value,
                            0,
                            '.',
                            ','
                        );
                    }
                }
            },

            legend: {
                enabled: false
            },

            // ==========================================
            // DUMBBELL
            // ==========================================

            plotOptions: {

                dumbbell: {

                    connectorWidth: 4,

                    marker: {
                        radius: 6,

                        states: {
                            hover: {
                                lineWidth: 0
                            }
                        }
                    },

                    dataLabels: {
                        enabled: true,

                        color: 'contrast',

                        crop: false,

                        overflow: 'allow',

                        formatter: function () {

                            return Highcharts.numberFormat(
                                this.point.high,
                                0,
                                '.',
                                ','
                            );
                        }
                    }
                }
            },

            // ==========================================
            // SERIES
            // ==========================================

            series: [{
                type: 'dumbbell',

                name: 'Stock vs Quantity',

                data: materialsData,

                color: '#3B82F6',

                lowColor: '#10B981',

                marker: {
                    enabled: true,
                    radius: 6
                },

                lowMarker: {
                    enabled: true,
                    radius: 6
                }
            }]
        });
    };


    // ==========================================
    // DATOS DE LIVEWIRE
    // ==========================================

    const materials = @json($allMaterials->values());


    // ==========================================
    // TRANSFORMAR DATOS
    // ==========================================

    const data = materials.map(function (material) {

        const stock = Number(material.stock) || 0;

        const quantity = Number(material.quantity) || 0;

        return {

            name: material.material_name,

            // Dumbbell necesita low y high
            low: Math.min(stock, quantity),

            high: Math.max(stock, quantity),

            custom: {

                stock: stock,

                quantity: quantity,

                part_number: material.part_number,

                unit: material.unit_measurement

            }
        };
    });


    // ==========================================
    // ASIGNAR ÍNDICE X
    // ==========================================

    const chartData = data.map(function (dataPoint, index) {

        return {
            ...dataPoint,
            x: index
        };

    });


    // ==========================================
    // CREAR
    // ==========================================

    createChart(chartData);

});
</script>
@endpush