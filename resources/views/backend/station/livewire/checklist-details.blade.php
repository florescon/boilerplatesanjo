<div class="container">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="page-content page-container" id="page-content">
                <div class="padding">
                    <div class="row container d-flex justify-content-center">
                        <div class="col-lg-12 grid-margin stretch-card">
                                <div class="alert alert-primary bg-white mb-4 shadow-sm" role="alert">
                                    🚨 Modifique las cantidades que se entreguen <strong>mayores</strong> a las consumidas. Posterior, valide las cantidades para realizar consumo de la diferencia.

                                {{-- Este apartado es para agregar consumo de cantidades que han sido excedentes a la <strong>Cantidad Automática</strong>, siendo esta el consumo por Lote. <br>Modifique las cantidades que se entreguen <strong>mayores</strong> a las consumidas. Posterior, valide las cantidades para realizar consumo de la diferencia. Esta diferencia es la <strong>Cantidad Entregada</strong> - <strong>Cantidad Recibida</strong>, consituyendo la <strong>Cantidad Manual</strong>. --}}

                                </div>

                            <div class="text-center m-4">
                                <a type="button" href="{{ route('admin.station.checklist', $station->id) }}" target="_blank" class="btn btn-primary btn-sm">Imprimir Checklist</a>
                            </div>

                            <div class="card shadow-lg bg-white {{ $station->card_secondary }}" style="background: radial-gradient(ellipse at 40% 0%, #bf398910 0, transparent 75%), radial-gradient(ellipse at 60% 0%, #096bde10 0, transparent 75%);">
                                <div class="card-body">
                                    <h4 class="card-title">{{ ucfirst(optional($station->status)->name) }} - {{ $station->created_at_for_humans }}</h4>
                                    <p class="card-description">
                                        {{-- Basic table with card --}}
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <caption><em>@lang('Captured by'): {{ optional($station->audi)->name }}</em></caption>
                                            <thead>
                                                <tr>
                                                    <th>@lang('Qty')</th>
                                                    <th>@lang('Concept')</th>
                                                    <th>@lang('Delivery')</th>
                                                    <th>@lang('Difference')</th>
                                                    <th>@lang('Received')</th>
                                                    <th class="text-center">@lang('Total')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($groupedMaterials->sortBy('material') as $key => $material)
                                                    <tr>
                                                        <th width="10%">{{ $material['sum_quantity'] }}</th>
                                                        <td width="37%">{!! $material['material'] !!}</td>
                                                        <td width="18%">
                                                          <div class="input-group">
                                                            <div class="input-group-prepend">
                                                              <div class="input-group-text">{{ $material['unit'] }}</div>
                                                            </div>
 
                                                            <input type="number" step=".01" class="form-control text-danger text-center" 
                                                                   placeholder="{{ $material['sum_quantity'] }}"
                                                                   wire:model.defer="quantities.{{ $key }}"
                                                                   id="quantity-{{ $key }}">

                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary" type="button"
                                                                wire:click="savePreconsumption({{ $key }}, {{ $material['sum_quantity'] }})"
                                                                >&#128190;</button>
                                                            </div>
                                                          </div>                                                            
                                                        </td>
                                                        <td width="7%" class="text-center">{{ $quantities[$key] > $material['sum_quantity'] ? $quantities[$key] - $material['sum_quantity'] : ''  }}</td>
                                                        <td width="18%">

                                                          <div class="input-group">
                                                            <div class="input-group-prepend">
                                                              <div class="input-group-text">{{ $material['unit'] }}</div>
                                                            </div>
 

                                                            <input type="number" step=".01" class="form-control text-danger text-center" 
                                                                placeholder="{{ 
                                                                        (is_numeric($quantities[$key]) && is_numeric($material['sum_quantity']) && ($quantities[$key] - $material['sum_quantity'] > 0))
                                                                        ? $difference = $quantities[$key] - $material['sum_quantity'] 
                                                                        :  
                                                                        '-' 
                                                                    }}"
                                                                wire:model.defer="received.{{ $key }}"
                                                                id="received-{{ $key }}"
                                                            >


                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary" type="button"
                                                                wire:click="saveRPreconsumption({{ $key }}, {{ $quantities[$key] ?: null }}, {{ $material['sum_quantity'] }})"
                                                                >&#128190;</button>
                                                            </div>
                                                          </div>                                                            
                                                        </td>
                                                        <td width="10%" class="text-center">
                                                            @if($received[$key] && $difference)
                                                                @php
                                                                    $toCons = $difference - $received[$key];
                                                                @endphp

                                                                @if(
                                                                    $toCons > 0 
                                                                    && is_numeric($difference) 
                                                                    && is_numeric($received[$key]) 
                                                                    && $received[$key] > 0
                                                                    // && (!preg_match('/^[\d]{0,11}(\.[\d]{1,2})?$/', $difference))
                                                                    // && (!preg_match('/^[\d]{0,11}(\.[\d]{1,2})?$/', $received[$key]))
                                                                )

                                                                    @if(!$processed[$key])
                                                                        {!! '<button class="btn btn-outline-danger " type="button" wire:click="makeConsumptionManual(' . $key . ', ' . $toCons . ', ' . $material['id'] . ')"> '.$toCons .' &#128190;</button>' !!}
                                                                    @else
                                                                        <strong class="text-primary">
                                                                            <i class="cil-check"></i><i class="cil-check"></i>
                                                                        </strong>
                                                                    @endif
                                                                @else
                                                                    N/A
                                                                @endif
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
        </div>
    </div>
</div>
