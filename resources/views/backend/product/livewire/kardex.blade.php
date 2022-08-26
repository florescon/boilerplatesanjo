<x-backend.card>
  <x-slot name="header">

   <div class="card-header-actions">
    <x-utils.link class="card-header-action" icon="fa fa-chevron-left" :href="route('admin.product.edit', $product_parent)" :text="__('Back to product')" />
  </div>

    <h3 class="mb-5">
      <strong class="text-primary"> @lang('Kardex')</strong>
      &nbsp;&nbsp;
      <i class="cil-double-quote-sans-left"></i>
      <strong> {{ $name }} </strong> 
      <i class="cil-double-quote-sans-right"></i>
    </h3>

    <div class="row input-daterange justify-content-center">

    <div class="col-6">
      <table class="table table-sm table-bordered">
        <thead>
          <tr class="text-center">
            <th scope="col">Stock</th>
            <th scope="col" class="text-center">@lang('Quantity')</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr>
            <th scope="row">@lang('Finished product')</th>
            <td>{{ $product->getTotStock() }}</td>
          </tr>
          <tr>
            <th scope="row">@lang('Intermediate Review')</th>
            <td>{{ $product->getTotStockRev() }}</td>
          </tr>
          <tr>
            <th scope="row">@lang('Store product')</th>
            <td>{{ $product->getTotStockStore() }}</td>
          </tr>
        </tbody>
      </table>
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
            <th scope="row" colspan="2" class="p-2 text-primary">{{ $product->total_stock }}</th>
          </tr>
          <tr>
            <th scope="row" class="w-50">
              @lang('Inputs'):
              <small class="font-weight-bold text-dark"><i class="cil-plus" style="color:green;"></i> {{ $product->getTotalHistory($dateInput, $dateOutput, false) }}</small>
            </th>
            <th scope="row" class="w-50">
              @lang('Outputs'):
              <small class="font-weight-bold text-dark"><i class="cil-minus" style="color:red;"></i> {{ $product->getTotalHistory($dateInput, $dateOutput, true) }}</small>
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
          <strong>Filtrado por</strong>
            @if(!$dateInput)
              mes de {{ now()->isoFormat('MMMM') }}
            @else
              {{ $dateInput }} {{ $dateOutput ? '- '.$dateOutput : __('to this day') }}
            @endif
        </div>

        @if($selectPage)
          <x-utils.alert type="primary">
            @unless($selectAll)
            <span>Tienes seleccionado <strong>{{ $history->count() }}</strong> registros, ¿quieres seleccionar  <strong>{{ $history->total() }} </strong> registros?</span>
              <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
            @else
              <span>Actualmente seleccionaste <strong>{{ $history->total() }}</strong> productos.</span>
            @endif

            <em>-- @lang('Order by name') --</em>

            @if($selected && $history->count())
              <div class="dropdown table-export">
                <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  @lang('Export')        
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" wire:click="exportMaatwebsite('csv')">CSV</a>
                  <a class="dropdown-item" wire:click="exportMaatwebsite('xlsx')">Excel</a>
                  <a class="dropdown-item" wire:click="exportMaatwebsite('xls')">Excel ('XLS')</a>
                  <a class="dropdown-item" wire:click="exportMaatwebsite('html')">HTML</a>
                  <a class="dropdown-item" wire:click="exportMaatwebsite('tsv')">TSV</a>
                  <a class="dropdown-item" wire:click="exportMaatwebsite('ods')">ODS</a>
                </div>
              </div><!--export-dropdown-->
            @endif

          </x-utils.alert>
        @else
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Seleccione para exportar
          </div>
        @endif

      </div>
    </div>

  </x-slot>

  <x-slot name="body">
    {{-- <div class="row mb-4 justify-content-center">
      <div class="col-6">
        <div class="input-group">
          <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search by folio, tracking number or comment') }}..." />
          @if($searchTerm !== '')
            <div class="input-group-append">
              <button type="button" wire:click="clear" class="close" aria-label="Close">
                <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
              </button>
            </div>
          @endif
        </div>
      </div>
    </div> --}}

    <div class="row  justify-content-center">
     <div class="col-sm-12">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr class="text-center">
              <th style="width:30px; max-width: 30px;">
                <label class="form-checkbox">
                  <input type="checkbox" wire:model="selectPage">
                  <i class="form-icon"></i>
                </label>
              </th>
              <th scope="col">@lang('Detail')</th>
              <th scope="col">@lang('Input')</th>
              <th scope="col">@lang('Output')</th>
              <th scope="col">@lang('Old stock')</th>
              <th scope="col" style="background-color: #f5f3f3;">Balance</th>
              <th scope="col">@lang('Type stock')</th>
              @if(!$product->isChildren())
                <th scope="col">@lang('Current stock')</th>
              @endif
            </tr>
          </thead>
          <tbody>
          @forelse ($history as $producte)
            <tr class="text-center">
              <td>
                <label class="form-checkbox">
                    <input type="checkbox" wire:model="selected" value="{{ $producte->id }}">
                    <i class="form-icon"></i>
                </label>
                {{-- {{ $producte->subproduct_id }} --}}
              </td>
              <th>{!! $producte->subproduct->only_attributes ?? null !!}</th>
              <td>{!! !$producte->isOutput() ? $producte->stock .'<div class="small text-muted">'.$producte->date_diff_for_humans.' - '.$producte->created_at.'</div>' : '' !!}</td>
              <td class="text-danger">{!! $producte->isOutput() ? $producte->stock .'<div class="small text-muted">'.$producte->date_diff_for_humans.' - '.$producte->created_at.'</div>' : '' !!}</td>
              <td>{{ $producte->old_stock ?? __('No results!') }}</td>
              <td style="background-color: #f5f3f3;">{{ $producte->balance }}</td>
              <td>
                {{ $producte->type_stock_label }}
                @if($producte->order_id)
                  <div class="small">@lang('Order'): 
                    <a href="{{ route('admin.order.edit', $producte->order_id) }}"> #{{ $producte->order_id }}</a>
                  </div>
                @endif
              </td>
              @if(!$product->isChildren())
                <td class="text-primary">{{ $producte->type_stock_relationship }}</td>
              @endif
            </tr>
          @empty
            <tr>
              <th scope="row" colspan="8" class="text-center">@lang('No results!')</th>
            </tr>
          @endforelse
          </tbody>
        </table>


          <div class="mt-4">
            @if($history->count())
              <div class="row">
                <div class="col">
                  <nav>
                    {{ $history->onEachSide(1)->links() }}
                  </nav>
                </div>
                    <div class="col-sm-3 mb-2 text-muted text-right">
                      Mostrando {{ $history->firstItem() }} - {{ $history->lastItem() }} de {{ $history->total() }} resultados
                    </div>
              </div>
            @endif
          </div>
          {{-- <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">{!! $producte->subproduct->full_name !!}</h5>
                  <small>{!! $producte->is_output_label !!} {{ $producte->date_diff_for_humans }}</small>
                </div>
                <p class="mb-1">{!! $producte->type_stock_label.' <strong class="text-info">'.$producte->stock.'</strong>' !!}</p>
              </a>
            @empty
              <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">@lang('No results!')</h5>
                </div>
              </a>
            @endforelse
          </div> --}}
        </div>
      </div>
    </div>
  </x-slot>

</x-backend.card>
