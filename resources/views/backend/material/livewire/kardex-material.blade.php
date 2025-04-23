<x-backend.card>
  <x-slot name="header">

   <div class="card-header-actions">
  </div>

    <h3 class="mb-5">
      <strong class="text-primary"> @lang('Kardex')</strong>
      &nbsp;&nbsp;
      <i class="cil-double-quote-sans-left"></i>
      <strong> {{ $material->name }} </strong> 
      <i class="cil-double-quote-sans-right"></i>
    </h3>

    <div class="row input-daterange justify-content-center">
      <div class="col-6">
        <table class="table table-sm table-bordered">
          <thead>
          </thead>
          <tbody class="text-center">
            <tr>
              <th scope="col" class="p-3">{{ optional($material->color)->name }}</th>
              <th scope="col" class="p-3">{{ optional($material->unit)->name }}</th>
            </tr>
            <tr>
              <th scope="col" class="p-3">{{ $material->part_number }}</th>
              <th scope="col" class="p-3 text-primary">${{ $material->price }}</th>
            </tr>
            @if($material->vendor_id)
              <tr>
                <th scope="col" class="p-3" colspan="2">{{ optional($material->vendor)->name }}</th>
              </tr>
            @endif
            <tr>
              <th scope="col" class="p-3" colspan="2">{{ $material->description }}</th>
            </tr>
            </tr>
          </tbody>
        </table>      
      </div>
      <div class="col-6  align-self-center">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th scope="col" class="p-3">@lang('Total')</th>
              <th scope="col" class="p-3 text-primary">{{ $material->stock .' '. $material->unit_name_label  }}</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <th scope="col" class="p-3">@lang('Total cost')</th>
              <th scope="col" class="p-3 text-primary">${{ number_format($material->stock * $material->price, 2, '.', '') }}</th>
            </tr>
            <tr>
              <th scope="row" class="w-50">
                @lang('Inputs'):
                <small class="font-weight-bold text-dark"><i class="cil-plus" style="color:green;"></i>
                  {{ $material->getTotalInput($dateInput, $dateOutput) }}
                </small>
              </th>
              <th scope="row" class="w-50">
                @lang('Outputs'):
                <small class="font-weight-bold text-dark"><i class="cil-minus" style="color:red;"></i> 
                  {{ $material->getTotalOutput($dateInput, $dateOutput) }}
                </small>
              </th>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-md-3 mr-2 mb-2 mt-3 pr-5=">
        <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
      </div>

      <div class="col-md-3 mr-2 mt-3 mb-2">
        <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
      </div>
      &nbsp;

      <div class="col-md-3 mt-3 mb-2">
        <div class="btn-group mr-2" role="group" aria-label="First group">
          <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
        </div>
      </div>
      &nbsp;

    </div>
    <div class="page-header-subtitle mt-2 mb-2 text-center">
      {{-- <em>
        @lang('Filter by updated date range')
      </em> --}}
      <div class="mt-3">

        @if($dateInput && $dateOutput)
          <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            <a href="#" class="link-underline-success" wire:click="exportMaatwebsiteCustom('xlsx')">Exportar a EXCEL</a>
          </div>
        @else
          <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            Para exportar a EXCEL, seleccione el rango de fecha.
          </div>
        @endif

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Filtrado por</strong>
            @if(!$dateInput)
              mes de {{ now()->isoFormat('MMMM') }}
            @else
              {{ $dateInput }} {{ $dateOutput ? '- '.$dateOutput : __('to this day') }}
            @endif
        </div>

      </div>
    </div>

  </x-slot>
  <x-slot name="body">
      <div class="table-responsive">
      <table class="table  table-sm align-items-center table-flush table-bordered table-hover">
        <thead>
            <tr class="text-center" style="background-color: #000000; color:white;">
              <th scope="col">@lang('Date')</th>
              <th scope="col">@lang('Details')</th>
              <th scope="col"></th>
              <th scope="col">@lang('Movement')</th>
              <th scope="col">@lang('Cost')</th>
              <th scope="col">@lang('Input')</th>
              <th scope="col">@lang('Output')</th>
              <th scope="col">@lang('Balance')</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($kardex as $day)
            <tr class="table-info">
              <th class="text-center" colspan="8">{{ $day['date'] ? \Carbon\Carbon::parse($day['date'])->format('d/m/Y') : '' }}  — {{ $day['records_count'] }} registros</th>
            </tr>
            @foreach ($day['items'] as $item)
                <tr class="text-center">
                    <td>{{ $item['date'] ? \Carbon\Carbon::parse($item['date'])->format('d/m/Y') : '' }}</td>
                    <td>{{ $item['details'] }}</td>
                    <td>
                      {{ $item['user'] }}
                    </td>
                    <td>
                      @if($item['instanceof'])
                        <span class="badge badge-primary">Manual</span>
                      @else
                        <span class="badge badge-success">Consumo</span>
                      @endif
                    </td>
                    <td>{{ $item['cost'] ? number_format($item['cost'], 2, '.', '') : '' }}</td>
                    <td class="text-primary">{{ $item['input'] != 0 ? $item['input'] : '' }}</td>
                    <td class="text-danger">{{ $item['output'] != 0 ? $item['output'] : '' }}</td>
                    <td>{{ $item['balance'] }}</td>
                </tr>
            @endforeach
          @endforeach
        </tbody>
    </table>
    </div>


  </x-slot>
</x-backend.card>
