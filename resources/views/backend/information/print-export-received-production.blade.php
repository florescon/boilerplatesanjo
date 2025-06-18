<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Confección Recibida</title>
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
            <div class="col-sm">
<table>
    <tbody>
        <tr>
            <td style="width: 50%;">
                <h4 class=" text-right">Reporte de Confección Recibida</h4>
            </td>
            <td style="width: 50%; padding-left: 260px;" class="text-right">
                <img src="{{ public_path('img/logo2.svg') }}" alt="" width="100"/>
            </td>
       </tr> 
    </tbody>   
</table>

<table cellpadding="5" cellspacing="0" style="width: 100%; ">
    <tbody>
        <tr>
            <td style="width: 50%;">
                <p style="margin: 0;"><strong>Estado:</strong> {{ ucfirst($status->name) }}</p>
                <p style="margin: 0;"><strong>Rango Fecha:</strong> {{ $dateInput }} al {{ $dateOutput }}</p>
            </td>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0;"><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                @if($getPersonal)
                    <p style="margin: 0;"><strong>Personal:</strong> {{ $getPersonal->name }}</p>
                @endif
            </td>
        </tr>
    </tbody>
</table>
            </div>
        </div>

        <!-- Datos del reporte -->
        @if($grouped)
            <!-- Vista agrupada por producto -->
            <table class="table table-bordered table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th >#</th>
                        <th >@lang('Code')</th>
                        <th >@lang('Color')</th>
                        <th >Talla</th>
                        <th >Producto</th>
                        <th >Precio</th>
                        <th >Cantidad</th>
                        <th> Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $productId => $group)
                        <tr>
                            <td class="text-danger bg-light"><strong>{{ $loop->iteration }}</strong></td>
                            <td> {{ $group->product->parent->code }} </td>
                            <td> {{ $group->product->color->name }} </td>
                            <td> {{ $group->product->size->name }} </td>
                            <td>
                                {{ $group->product->full_name_clear ?? 'Producto no encontrado' }}
                                @if($group->is_extra)
                                    <span class="badge badge-primary">Tallas Extra</span>
                                @endif
                            </td>
                            <td>
                                <p>$
                                    {{ 
                                        $priceMaking = isset($group->product->parent) && isset($group->product->size) && $group->product->size->is_extra 
                                            ? 
                                            $group->product->parent->price_making_extra
                                            : 
                                            (isset($group->product->parent) ? $group->product->parent->price_making : 0) 
                                    }}
                                </p>
                            </td>
                            <td class="text-center font-weight-bold">{{ $group->total_quantity }}</td>
                            <td class="text-center"> {{ number_format($group->total_price, 2) }} </td>
                        </tr>
                        <!-- Detalles desplegables -->
                        <tr class="collapse" id="details-{{ $productId }}">
                            <td colspan="8" class="p-0">
                                <table class="table table-sm table-bordered mb-0 bg-light">
                                    <thead>
                                        <tr class="bg-subtotal">
                                            <th width="10%" class="text-center">Folio</th>
                                            <th width="15%" class="text-center">Fecha</th>
                                            <th width="15%" class="text-center">Cantidad</th>
                                            <th width="30%" class="text-center">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->items as $item)
                                            <tr>
                                                <td class="text-center">{{ $item->batch_id ?? '' }}</td>
                                                <td class="text-center">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">{{ $item->output_quantity }}</td>
                                                <td class="text-center">{{ $item->observations ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    <!-- Total general -->
                    <tr class="table-active font-weight-bold">
                        <td colspan="6" class="text-right">TOTAL GENERAL</td>
                        <td class="text-center"> {{ $result->sum('total_quantity') }} </td>
                        <td class="text-right">{{ number_format($result->sum('total_price'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <table class="table table-bordered table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">#</th>
                        <th >@lang('Code')</th>
                        <th >@lang('Color')</th>
                        <th >Producto</th>
                        <th >Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $productId => $group)
                        <tr>
                            <td width="3%" class="text-center text-danger"><strong>{{ $loop->iteration }}</strong></td>
                            <td width="20">{{ $group->product->parent->code }}</td>
                            <td width="20">{{ $group->product->color->name }}</td>
                            <td width="30%">
                                {{ $group->product->only_name_clear_sort ?? 'Producto no encontrado' }}
                                @if($group->is_extra)
                                    <span class="badge badge-primary">Tallas Extra</span>
                                @endif
                            </td>
                            <td width="6%">{{ $group->price }}</td>
                            <td class="text-center" width="6%">
                                {{ $group->total_quantity }}
                            </td>
                            <td class="text-center font-weight-bold" width="10%">{{ number_format($group->total_price, 2) }}</td>
                        </tr>
                        <!-- Detalles desplegables -->
                    @endforeach
                    <!-- Total general -->
                    <tr class="table-active font-weight-bold">
                        <td colspan="3"></td>
                        <td colspan="2" class="text-right">TOTAL GENERAL</td>
                        <td class="text-center">{{ $result->sum('total_quantity') }}</td>
                        <td class="text-center">{{ number_format($result->sum('total_price'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <!-- Vista no agrupada (igual que antes) -->
            <!-- ... mantén el código original para la vista no agrupada ... -->
        @endif

        <!-- Resumen y firmas -->
        <div class="row signature-section">
            <div class="col-md-6">
                <div class="border-top pt-3">
                    <p>
                        @if($oldestDate && $newestDate)
                            <strong>Rango de fechas:</strong> {{ $oldestDate->format('d/m/Y H:i') }} - {{ $newestDate->format('d/m/Y H:i') }}
                        @endif
                        <strong class="pl-4">@lang('Sorted by'):</strong> 
                        Código del producto, 
                        color del producto
                    </p>
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