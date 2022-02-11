<div class="card shadow-lg p-3 mb-5 bg-white rounded " style="border-color: red; border:3px solid red">

  <div class="card-header">
    <strong style="color: #0061f2;"> @lang('Activity panel') </strong>
    <div class="card-header-actions">
       <em> @lang('Last request'): {{ now()->format('h:i:s') }} </em>
    </div>

    <div class="row input-daterange mt-4">
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
        <option>100</option>
      </select>
    </div><!--col-->

    <div class="col">
      <div class="input-group">
        <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search') }}..." />
        @if($searchTerm !== '')
        <div class="input-group-append">
          <button type="button" wire:click="clear" class="close" aria-label="Close">
            <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
          </button>

        </div>
        @endif
      </div>
    </div>

    @if($selected && $activities->count())
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
    <span>Tienes seleccionado <strong>{{ $activities->count() }}</strong> activities, ¿quieres seleccionar  <strong>{{ $activities->total() }} </strong> activities?</span>
      <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
    @else
      <span>Actualmente seleccionaste <strong>{{ $activities->total() }}</strong> activities.</span>
    @endif
  
    <em>-- @lang('Sorted by date created descending') --</em>

  </x-utils.alert>
@endif

  <div class="row mt-4">
    <div class="col">
      <div class="table-responsive text-center">
        <table class="table table-sm align-items-center table-striped table-flush table-bordered table-hover">
          <thead style="background-color:blue; color:white;">
            <tr>
              <th style="width:30px; max-width: 30px;">
                <label class="form-checkbox">
                  <input type="checkbox" wire:model="selectPage">
                  <i class="form-icon"></i>
                </label>
              </th>
              <th scope="col">
                <a style="color:white;" wire:click.prevent="sortBy('log_name')" role="button" href="#">
                  @lang('Name')
                  @include('backend.includes._sort-icon', ['field' => 'log_name'])
                </a>
              </th>
              <th>
                @lang('Description')
              </th>
              @if($filters)
                <th>
                  @lang('Properties')
                </th>
              @endif
              <th scope="col">@lang('Created at')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($activities as $activity)
            <tr>
              <td>
                <label class="form-checkbox">
                    <input type="checkbox" wire:model="selected" value="{{ $activity->id }}">
                  <i class="form-icon"></i>
                  </label>
              </td>
              <th scope="row">
                  <div> 
                    <button type="button" class="btn {{ in_array($activity->log_name, $filters) ? 'btn-primary text-white' : 'btn-outline-primary' }} btn-sm" wire:click="$emit('filterByLogName', {{ $activity->id }})"> {{ __($activity->log_name) }} </button>
                  </div>
              </th>
              <td>
                {{ $activity->description }}
                <div class="small text-muted">@lang('Registered'): {{ $activity->date_diff_for_humans }}</div>
              </td>
              @if($filters)
                <td>
                  @foreach($activity->getExtraProperty($filters[0]) as $key => $avr)
                    @if($avr !== null)
                      {{ __($key) }}: <mark>{{ $avr }}</mark>
                    @endif
                  @endforeach
                </td>
              @endif
              <td>
                {{ $activity->updated_at }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        @if($activities->count())
        <div class="row">
          <div class="col">
            <nav>
              {{ $activities->links() }}
            </nav>
          </div>
              <div class="col-sm-3 text-muted text-right">
                Mostrando {{ $activities->firstItem() }} - {{ $activities->lastItem() }} de {{ $activities->total() }} resultados
              </div>
        </div>
        @else
          @lang('No search results') 
          @if($searchTerm)
            "{{ $searchTerm }}" 
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