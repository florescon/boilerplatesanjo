<x-backend.card>
  <x-slot name="header">

   <div class="card-header-actions">
    <x-utils.link class="card-header-action" icon="fa fa-chevron-left" :href="route('admin.product.edit', $product_id)" :text="__('Back to product')" />
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
            <th scope="col" class="p-3">@lang('Total')</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr>
            <th scope="row" class="p-4 text-primary">{{ $product->total_stock }}</th>
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
      <em>
        @lang('Filter by updated date range')
      </em>
      <div class="mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <strong>Filtrado por</strong>
            @if(!$dateInput)
              mes de {{ now()->isoFormat('MMMM') }}
            @else
              {{ $dateInput }} {{ $dateOutput ? '- '.$dateOutput : __('to this day') }}
            @endif

            <button type="button" class="btn btn-primary btn-sm disabled">@lang('Export') (@lang('Disabled'))</button>

        </div>
      </div>
    </div>

  </x-slot>

  <x-slot name="body">
    <div class="row mb-4 justify-content-center">
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
    </div>

    <div class="row mt-4 justify-content-center">
     <div class="col-sm-9 col-md-9 col-lg-9">
        <div class="card shadow-lg">
          <div class="list-group">
            @forelse ($history as $producte)
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
          </div>
        </div>
      </div>
    </div>
  </x-slot>

</x-backend.card>
