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
      
      </div>
      <div class="col-6  align-self-center">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th scope="col" colspan="2" class="p-3">@lang('Total')</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <th scope="row" colspan="2" class="p-2 text-primary">{{ $material->stock }}</th>
            </tr>
            <tr>
              <th scope="row" class="w-50">
                @lang('Inputs'):
                <small class="font-weight-bold text-dark"><i class="cil-plus" style="color:green;"></i> 
                </small>
              </th>
              <th scope="row" class="w-50">
                @lang('Outputs'):
                <small class="font-weight-bold text-dark"><i class="cil-minus" style="color:red;"></i> 
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
          <button type="button" class="btn btn-outline-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
        </div>
      </div>
      &nbsp;

    </div>
    <div class="page-header-subtitle mt-2 mb-2 text-center">
      {{-- <em>
        @lang('Filter by updated date range')
      </em> --}}
      <div class="mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          {{-- <strong>Filtrado por</strong>
            @if(!$dateInput)
              mes de {{ now()->isoFormat('MMMM') }}
            @else
              {{ $dateInput }} {{ $dateOutput ? '- '.$dateOutput : __('to this day') }}
            @endif --}}
          Kardex parcial de movimientos ingresados manualmente, no refleja los consumos. Se ajustará
        </div>

      </div>
    </div>

  </x-slot>
  <x-slot name="body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">@lang('Date')</th>
          <th scope="col">@lang('Details')</th>
          <th scope="col">@lang('Unit value')</th>
          <th scope="col">@lang('Input')</th>
          <th scope="col">@lang('Output')</th>
          <th scope="col">Saldos</th>
        </tr>
      </thead>
      <tbody>

        @foreach($materialHistory as $mHistory)
          <tr>
            <th>
              <p>{{ $mHistory->audi->initials }}</p>
            </th>
            <th scope="row">{{ $mHistory->created_at }}
            </th>
            <td>{{ $mHistory->comment }}</td>
            <td>
              {{ $mHistory->price }} 
              @if($mHistory->price != $mHistory->old_price)
                &nbsp;&nbsp;&nbsp;
                <small class="text-muted">{{ $mHistory->old_price }}</small>
              @endif
            </td>
            <td>{{ $mHistory->stock >= 0 ? $mHistory->stock : '' }}</td>
            <td>{{ $mHistory->stock < 0 ? $mHistory->stock : '' }}</td>
            <td>{{ $mHistory->stock + $mHistory->old_stock }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>    

  </x-slot>
</x-backend.card>
