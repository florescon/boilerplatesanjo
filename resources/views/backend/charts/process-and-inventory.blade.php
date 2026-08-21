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

<div class="page">

    <!-- Encabezado -->
    <div class="text-center mb-4">
        <h2>
          Reporte de Inventario
        </h2>

        <div><strong>{{ __(appName()) }}</strong></div>

        <div>Reporte {{ generated() }}</div>
    </div>

    <!-- Indicadores -->
    <div class="section-title">
        Indicadores Principales al Costo
    </div>

    <div class="row">

<div class="col-6 col-md-3">
    <div class="metric">
        <h6>Total Inventario</h6>
        <h4>{{ number_format($totalQty, 0) }}</h4>
    </div>
</div>

<div class="col-6 col-md-3">
    <div class="metric">
        <h6>Valor del Inventario</h6>
        <h4>${{ number_format($totalValue, 0) }}</h4>
    </div>
</div>

<div class="col-6 col-md-3">
    <div class="metric">
        <h6 class="text-primary">Total Producción</h6>
        <h4 class="text-primary">
            {{ number_format($totalProduction, 0) }}
        </h4>
    </div>
</div>

<div class="col-6 col-md-3">
    <div class="metric">
        <h6 class="text-primary">Valor en Producción</h6>
        <h4 class="text-primary">
            ${{ number_format($totalProductionValue, 0) }}
        </h4>
    </div>
</div>    </div>

    <div class="row ">


    </div>

<div class="row">
@foreach($parents as $parentId => $parentName)

    @php
        $totalQty = $totals[$parentId]['product'] ?? 0;
        $cost = $totals[$parentId]['cost'] ?? 0;
        $totalValue = $totalQty * $cost;
    @endphp

<div class="col-md-6 px-1" style="page-break-inside:avoid;">

    <div class="card-body p-1">

        <h6 class="font-weight-bold mb-1">
            {{ $loop->iteration }}.- {!! $parentName !!}


                <span class="text-danger">
                    {{ number_format($totalQty, 0) }}
                    /
                    ${{ number_format($totalValue, 0) }}
                </span>
        </h6>

        <div class="table-responsive">

            <table class="table table-sm table-hover mb-2 compact-table">

                <thead>
                    <tr>
                        <th style="width: 40px;"></th>

                        @foreach($sizesByParent[$parentId] ?? [] as $size)
                            <th class="text-center">
                                {{ $size->name }}
                            </th>
                        @endforeach

                        <th class="text-center table-dark">
                            Total
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($colorsByParent[$parentId] ?? [] as $color)

                        @php
                            $totalColor =
                                $totals[$parentId]['colors'][$color->id] ?? 0;


                            $activeColor =
                                $activeTotalsByColor[$parentId][$color->id] ?? 0;
                        @endphp

                        @if($totalColor == 0)
                            @continue
                        @endif

                        <tr>

                            <td>
                                 {{ substr($color->name, 0, 8) }}

                            </td>

                            @foreach($sizesByParent[$parentId] ?? [] as $size)

                                @php
                                    $qty = $matrix[$parentId][$color->id][$size->id]->stock ?? 0;

                                    $active = $activeMatrix[$parentId][$color->id][$size->id] ?? 0;

                                @endphp

                                <td class="text-center">
                                    {{ $qty != 0 ? $qty : '' }}
                                    <p class="text-primary">
                                        {{ $active != 0 ? $active : '' }}
                                	</p>
                                </td>

                            @endforeach

                            <td class="text-center font-weight-bold table-dark">
                                {{ ($totalColor != 0) ? $totalColor : '' }}
                                <p class="text-primary">
                                    {{ $totalColor != 0 ? $totalColor : '' }}
                                </p>
                            </td>
                        </tr>

                    @endforeach

                    {{-- TOTALES --}}
                    <tr class="table-dark">

                        <td class="font-weight-bold">
                            Total
                        </td>

                        @foreach($sizesByParent[$parentId] ?? [] as $size)

                            @php
                                $totalSize =
                                    $totals[$parentId]['sizes'][$size->id] ?? 0;

                                $activeSize =
                                    $activeTotalsBySize[$parentId][$size->id] ?? 0;
                            @endphp

                            <td class="text-center font-weight-bold">

                                {{ $totalSize }}

                                <p class="text-primary mb-0">
                                    {{ $activeSize != 0 ? $activeSize : '' }}
                                </p>

                            </td>

                        @endforeach

                        @php
                            $totalProduct = $totals[$parentId]['product'] ?? 0;
                            $activeProduct = $activeTotals[$parentId] ?? 0;
                        @endphp

                        <td class="text-center font-weight-bold">

                            {{ $totalProduct }}

                            <p class="text-primary mb-0">
                                {{ $activeProduct != 0 ? $activeProduct : '' }}
                            </p>

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endforeach
</div>
    <div class="footer">
      Reporte ...
    </div>

</div>



@push('after-scripts')
@endpush
