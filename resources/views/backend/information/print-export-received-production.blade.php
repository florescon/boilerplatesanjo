<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Producción Recibida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-size: 12px;
        }
        .header-report {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .table-sm td, .table-sm th {
            padding: 0.3rem;
        }
        .page-break {
            page-break-after: always;
        }
        .signature-section {
            margin-top: 50px;
        }
        .bg-subtotal {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Encabezado del reporte -->
        <div class="row header-report">
            <div class="col-md-6">
                <h4 class="mb-0">Reporte de Producción Recibida</h4>
                <p class="mb-0"><strong>Estado:</strong> {{ $status->name }}</p>
                <p class="mb-0"><strong>Fecha:</strong> {{ $dateInput }} al {{ $dateOutput }}</p>
            </div>
            <div class="col-md-6 text-center">
                <p class="mb-0"><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                @if($getPersonal)
                    <p class="mb-0"><strong>Personal:</strong> <br>{{ $getPersonal->name }}</p>
                @endif
            </div>
        </div>

        <!-- Datos del reporte -->
        @if($grouped)
            <!-- Vista agrupada por producto -->
            <table class="table table-bordered table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th width="35%">Producto</th>
                        <th width="15%">Cantidad Total</th>
                        <th width="15%">Unidad</th>
                        <th width="30%">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $productId => $group)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $group->product->full_name_clear ?? 'Producto no encontrado' }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($group->total_quantity, 2) }}</td>
                            <td>{{ $group->product->unit ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" 
                                    data-target="#details-{{ $productId }}" aria-expanded="false">
                                    Ver detalles
                                </button>
                            </td>
                        </tr>
                        <!-- Detalles desplegables -->
                        <tr class="collapse" id="details-{{ $productId }}">
                            <td colspan="5" class="p-0">
                                <table class="table table-sm table-bordered mb-0 bg-light">
                                    <thead>
                                        <tr class="bg-subtotal">
                                            <th width="10%">Lote</th>
                                            <th width="15%">Fecha</th>
                                            <th width="15%">Cantidad</th>
                                            <th width="30%">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->items as $item)
                                            <tr>
                                                <td>{{ $item->batch->code ?? '' }}</td>
                                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-right">{{ number_format($item->input_quantity, 2) }}</td>
                                                <td>{{ $item->observations ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    <!-- Total general -->
                    <tr class="table-active font-weight-bold">
                        <td colspan="2" class="text-right">TOTAL GENERAL:</td>
                        <td class="text-right">{{ number_format($result->sum('total_quantity'), 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        @else
            <!-- Vista no agrupada (igual que antes) -->
            <!-- ... mantén el código original para la vista no agrupada ... -->
        @endif

        <!-- Resumen y firmas -->
        <div class="row signature-section">
            <div class="col-md-6">
                <div class="border-top pt-3">
                    <p><strong>Rango de fechas:</strong></p>
                    {{-- <p>Desde: {{ $oldestDate->format('d/m/Y H:i') }}</p> --}}
                    {{-- <p>Hasta: {{ $newestDate->format('d/m/Y H:i') }}</p> --}}
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="border-top pt-3">
                    <p>_________________________</p>
                    <p>Firma Responsable</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap para funcionalidad del colapso -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>