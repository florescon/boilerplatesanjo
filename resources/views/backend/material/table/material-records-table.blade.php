<div class="card shadow-lg p-3 mb-5 bg-white rounded">

<div class="card-header">
  @if($deleted)
    <strong style="color: red;"> @lang('List of deleted records consumed feedstock') </strong>
  @else
    <strong style="color: #0061f2;"> @lang('List of records consumed feedstock') </strong>
  @endif
  <div class="card-header-actions">
    <x-utils.link class="card-header-action" icon="fa fa-chevron-left" :href="route('admin.material.index')" :text="__('Back to feedstock')" />
  </div>

    <div class="page-header-subtitle mt-5 mb-2">
      <em>
        @lang('Filter by update date range')
      </em>
    </div>

    <div class="row input-daterange">
        <div class="col-md-3">
          <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
        </div>
        &nbsp;

        <div class="col-md-3">
          <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
        </div>
        &nbsp;

        <div class="col-md-3">
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
            <button type="button" class="btn btn-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
          </div>
        </div>
        &nbsp;

        <div class="col-md-1">
          <div class="custom-control custom-switch">
            <input type="checkbox" wire:model="deleted" class="custom-control-input" id="deletedSwitch">
            <label class="custom-control-label" for="deletedSwitch"> <p class="{{ $deleted ? 'text-primary' : 'text-dark' }}"> @lang('Deletions')</p></label>
          </div>
        </div>

    </div>
</div>


<div class="card-body">

@include('includes.partials.messages-livewire')
<div wire:offline.class="d-block" wire:offline.class.remove="d-none" class="alert alert-danger d-none">
    @lang('You are not currently connected to the internet.')    
</div>

  <div class="row mb-4">
    <div class="col form-inline">
      @lang('Per page'): &nbsp;

      <select wire:model="perPage" class="form-control">
        <option>10</option>
        <option>25</option>
        <option>50</option>
      </select>
    </div><!--col-->

    <div class="col">
      <div class="input-group">
        <input wire:model.debounce.350ms="searchTerm" class="form-control input-search-color" type="text" placeholder="{{ __('Search') }}..." />
        @if($searchTerm !== '')
        <div class="input-group-append">
          <button type="button" wire:click="clear" class="close" aria-label="Close">
            <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
          </button>

        </div>
        @endif
      </div>
    </div>


    @if($selected && $records->count() && !$deleted)
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
  </div><!--row-->


{{-- @json($selected) --}}


@if($selectPage)
  <x-utils.alert type="primary">
    @unless($selectAll)
    <span>Tienes seleccionado <strong>{{ $records->count() }}</strong> registros, ¿quieres seleccionar  <strong>{{ $records->total() }} </strong> registros?</span>
      <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
    @else
      <span>Actualmente seleccionaste <strong>{{ $records->total() }}</strong> registros.</span>
    @endif

    <em>-- @lang('Sorted by date created descending') --</em>

  </x-utils.alert>
@endif

  <div class="row mt-4">
    <div class="col">
      <div class="table-responsive">
        <table class="table  table-sm align-items-center table-flush table-bordered table-hover">
          <thead style="
          " class="thead-dark">
            <tr>
              @if(!$deleted)
                <th style="width:30px; max-width: 30px;">
                  <label class="form-checkbox">
                    <input type="checkbox" wire:model="selectPage">
                    <i class="form-icon"></i>
                  </label>
                </th>
              @endif
              <th scope="col">
                <a style="color:white;" wire:click.prevent="sortBy('name')" role="button" href="#">
                  @lang('Feedstock')
                  @include('backend.includes._sort-icon', ['field' => 'name'])
                </a>
              </th>

              <th scope="col" class="text-center">@lang('Product')</th>

              <th scope="col" class="text-center">@lang('Consumption')</th>

              <th scope="col" class="text-center">@lang('Total consumption')</th>
              
              <th scope="col" class="text-center">@lang('Unit price')</th>

              <th scope="col" class="text-center">@lang('Price')</th>

              <th scope="col" class="text-center">@lang('Order')</th>

              <th scope="col">
                <a style="color:white;" wire:click.prevent="sortBy('created_at')" role="button" href="#">
                  @lang('Created at')
                  @include('backend.includes._sort-icon', ['field' => 'created_at'])
                </a>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($records as $record)
            <tr>
              @if(!$deleted)
                <td class="text-center">
                  <label class="form-checkbox">
                      <input type="checkbox" wire:model="selected" value="{{ $record->id }}">
                      <i class="form-icon"></i>
                    </label>
                </td>
              @endif
              <th>
                {!! $record->material->full_name !!}
              </th>
              <td>
                {!! $record->product_order->product->full_name !!}
              </td>
              <td class="align-middle text-center">
                {{ $record->unit_quantity }}
              </td>
              <td class="align-middle text-center">
                {{ $record->quantity }}
              </td>
              <td class="align-middle text-center">
                ${{ $record->price }}
              </td>
              <td class="align-middle text-center">
                ${{ rtrim(rtrim(sprintf('%.8F', $record->total_by_material), '0'), ".") }}
              </td>
              <td class="align-middle text-center">
                <a href="{{ route('admin.order.edit', $record->order_id) }}"> #{{ $record->order_id }}</a>
              </td>
              <td class="align-middle">
                <span class="badge badge-dot mr-4">
                  <i class="bg-warning"></i> {{ $record->created_at }}
                </span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        @if($records->count())
        <div class="row">
          <div class="col">
            <nav>
              {{ $records->onEachSide(1)->links() }}
            </nav>
          </div>
              <div class="col-sm-3 text-muted text-right">
                Mostrando {{ $records->firstItem() }} - {{ $records->lastItem() }} de {{ $records->total() }} resultados
              </div>
        </div>
        @else
          @lang('No search results') 
          @if($searchTerm)
            "{{ $searchTerm }}" 
          @endif

          @if($deleted)
            @lang('for deleted')
          @endif

          @if($dateInput) 
            @lang('from') {{ $dateInput }} {{ $dateOutput ? __('To') .' '.$dateOutput : __('to this day') }}
          @endif

          @if($page > 1)
            {{ __('in the page').' '.$page }}
          @endif
        @endif

      </div>

    </div>
  </div>
</div>
</div>