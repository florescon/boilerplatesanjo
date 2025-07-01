<div class="animated fadeIn">

  <!-- /.row-->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">

            <div class="card p-3 border-0">
              <div class="mt-3">
                <h5 class="heading">
                  <kbd>
                    <i class="cil-short-text"></i> 
                    @lang('Warehouse relaese form')
                    @if($status == 'deleted')
                      <span class="badge badge-danger">@lang('Deletions')</span>
                    @endif
                  </kbd>
                </h5>
                @if($status == 'deleted')
                  <a href="{{ route('admin.store.box.history') }}">
                    <i class="fa fa-hand-o-left" aria-hidden="true"></i>
                   @lang('to return')
                 </a>
                @endif
              </div>
            </div>

            <div class="page-header-subtitle mt-5 mb-2">
              <em>
                @lang('Filter by update date range')
              </em>
            </div>

            <div class="row input-daterange">
                <div class="col-md-2">
                  <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
                </div>
                &nbsp;

                <div class="col-md-2">
                  <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
                </div>
                &nbsp;

                <div class="col-md-3">
                  <div class="btn-group" role="group" aria-label="Range date">
                    <button type="button" class="btn {{ $currentMonth ? 'btn-primary' : 'btn-secondary' }}" wire:click="isCurrentMonth">@lang('Current month')</button>
                    <button type="button" class="btn {{ $currentWeek ? 'btn-primary' : 'btn-secondary' }}" wire:click="isCurrentWeek">@lang('Current week')</button>
                    <button type="button" class="btn {{ $today ? 'btn-primary' : 'btn-secondary' }}" wire:click="isToday">@lang('Today')</button>
                  </div>
                </div>
                &nbsp;

                <div class="col-md-3">
                  <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
                    <button type="button" class="btn btn-outline-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
                  </div>
                </div>
                &nbsp;
                
            </div>
        </div>
        <div class="card-body">

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
                <input wire:model.debounce.350ms="searchTerm" class="form-control input-search-green" type="text" placeholder="{{ __('Search') }}..." />
                @if($searchTerm !== '')
                <div class="input-group-append">
                  <button type="button" wire:click="clear" class="close" aria-label="Close">
                    <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
                  </button>

                </div>
                @endif
              </div>
            </div>

            @if($selected && $tickets->count() && !$deleted)
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


          <table class="table table-responsive-sm table-hover table-outline mb-0 shadow">
            <thead class="" style="background-color: #FDFD96;">
              <tr>
                <th>
                  <a style="color:white;" wire:click.prevent="sortBy('id')" role="button" href="#">
                    f.º
                    @include('backend.includes._sort-icon', ['field' => 'id'])
                  </a>
                </th>
                <th class="text-center">@lang('Description')</th>
                <th class="text-center">@lang('User')</th>
                <th class="text-center">@lang('Details')</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($tickets as $ticket)
              <tr>
                <td>
                  <strong>#{{ $ticket->folio }}</strong>
                  <div class="small text-muted">@lang('Warehouse relaese form')</div>
                </td>
                <td class="text-center">
                  <strong>{{ $ticket->description ?: '--' }}</strong>
                </td>
                <td class="text-center">
                  <div> <a href="#" >{{ optional($ticket->customer)->name ?? __('undefined') }}</a> </div>
                  <div class="small text-muted">@lang('Registered'): {{ $ticket->date_for_humans }}</div>
                </td>
                <td class="text-center">
                  @lang('Created') {{ $ticket->date_diff_for_humans_created }}
                </td>

                <td class="text-center">
                  <a href="{{ route('admin.material.ticket_out', $ticket->id) }}" class="card-link" target="_blank"><i class="cil-print"></i> Vale </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>          
          <nav class="mt-4">
            @if($tickets->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $tickets->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} de {{ $tickets->total() }} resultados
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
          </nav>
        </div>
      </div>
    </div>
    <!-- /.col-->
  </div>
  <!-- /.row-->
</div>
