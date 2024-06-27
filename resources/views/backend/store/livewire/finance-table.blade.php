<div class="animated fadeIn">
  <!-- /.row-->
  <div class="row justify-content-center">
    <div class="col-sm-6 col-lg-5">
      <div class="card text-white bg-primary text-monospace" 
      		@if($expenses == TRUE)
            style="-webkit-filter: blur(3px);"
          @endif
  		>
        <a class="card-block stretched-link text-decoration-none text-white" href="#" 
          @if(!$status == 'deleted')
            wire:click="$emit('filter', 'incomes')"
          @endif
        >
          <div class="card-body">
            {{-- <div class="text-value">89.9%</div> --}}
            <div class="font-weight-bold text-uppercase">@lang('Incomes')...</div>
            <div class="progress progress-white progress-xs my-2">
              {{-- <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div> --}}
            </div>

            @if($incomes == TRUE)
              <ul class="list-group list-group-flush text-center">
                <li class="list-group-item">@lang('Records'): {{ $finances->total() }} </li>
                {{-- <li class="list-group-item">@lang('Total') @lang('in the page'): $ {{ rtrim(rtrim(sprintf('%.8F', $finances->sum('amount')), '0'), ".") }} </li> --}}
                <li class="list-group-item">@lang('Total'): $ {{ rtrim(rtrim(sprintf('%.8F', $querySum), '0'), ".") }} </li>
                <li class="list-group-item">
                  <h4>
                    <span class="badge badge-light">
                      @if($dateInput)
                        {{ $dateInput }}
                        @if($dateOutput)
                          a {{ $dateOutput }}
                        @endif
                      @else 
                        @if($currentMonth)
                          @lang('Current month')
                        @elseif($currentWeek)
                          @lang('Current week')
                        @elseif($today)
                          @lang('Today')
                        @else
                          @lang('Current month')
                        @endif
                      @endif
                    </span>
                  </h4>
                </li>
              </ul>
              {{-- <small class="text-muted"> {{ $finances->total() }} </small> --}}
            @endif

          </div>
          <div class="bg-primary card-footer text-right">
            <i class="cil-touch-app"></i>
          </div>
        </a>
      </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-5">
      <div class="card text-white bg-danger text-monospace" 
          @if($incomes == TRUE)
            style="-webkit-filter: blur(3px);"
          @endif
      	>
        <a class="card-block stretched-link text-decoration-none text-white" href="#" 
          @if(!$status == 'deleted')
            wire:click="$emit('filter', 'expenses')"
          @endif
        >
  
          <div class="card-body">
            {{-- <div class="text-value">$98.111,00</div> --}}
            <div class="font-weight-bold text-uppercase">@lang('Expenses')...</div>
            <div class="progress progress-white progress-xs my-2">
              {{-- <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div> --}}
            </div>
            @if($expenses == TRUE)
              <ul class="list-group list-group-flush text-center">
                <li class="list-group-item">@lang('Quantity'): {{ $finances->total() }}</li>
                {{-- <li class="list-group-item">@lang('Total') @lang('in the page'): $ {{ rtrim(rtrim(sprintf('%.8F', $finances->sum('amount')), '0'), ".") }} </li> --}}
                <li class="list-group-item">@lang('Total'): $ {{ rtrim(rtrim(sprintf('%.8F', $querySum), '0'), ".") }} </li>
                <li class="list-group-item">
                  <h4>
                    <span class="badge badge-light">
                      @if($dateInput)
                        {{ $dateInput }}
                        @if($dateOutput)
                          - {{ $dateOutput }}
                        @endif
                      @else 
                        @if($currentMonth)
                          @lang('Current month')
                        @elseif($currentWeek)
                          @lang('Current week')
                        @elseif($today)
                          @lang('Today')
                        @else
                          @lang('Current month')
                        @endif
                      @endif
                    </span>
                  </h4>
                </li>
              </ul>
              {{-- <small class="text-muted"> {{ $finances->total() }} </small> --}}
            @endif
          </div>
          <div class="bg-danger card-footer text-right">
            <i class="cil-touch-app"></i>
          </div>
        </a>
      </div>
    </div>
    <!-- /.col-->
  </div>
  <!-- /.row-->

  <!-- /.row-->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-store"></i>

          @if($status == 'deleted')
            <strong style="color: red;"> @lang('Incomes and expenses deleted') </strong>
          @else
            <strong style="color: #0061f2;"> @lang('Incomes and expenses') </strong>
          @endif
          <div class="card-header-actions">
            <em> @lang('Last request'): {{ now()->format('h:i:s') }} </em>
            @if(!$status == 'deleted')
              <a href="#" class="card-header-action" style="color: green;"  data-toggle="modal" wire:click="$emitTo('backend.store.finance.create-finance', 'createmodal')" data-target="#createFinance"><i class="c-icon cil-plus"></i> @lang('Create income or expense') </a>
            @else
              <a href="{{ route('admin.store.finances.index') }}" class="card-header-action"><i class="fa fa-chevron-left"></i> @lang('Back') </a>
            @endif
          </div>

            <div class="page-header-subtitle mt-5 mb-2">
              <em>
                @lang('Filter by created date range')
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

                <div class="col-md-4">
                  <div class="btn-group" role="group" aria-label="Range date">
                    <button type="button" class="btn {{ $currentMonth ? 'btn-primary' : 'btn-secondary' }}" wire:click="isCurrentMonth">@lang('Current month')</button>
                    <button type="button" class="btn {{ $currentWeek ? 'btn-primary' : 'btn-secondary' }}" wire:click="isCurrentWeek">@lang('Current week')</button>
                    <button type="button" class="btn {{ $today ? 'btn-primary' : 'btn-secondary' }}" wire:click="isToday">@lang('Today')</button>
                  </div>

                  <button type="button" class="btn {{ $invoice ? 'btn-primary' : 'btn-secondary' }}" wire:click="isInvoice"><img src="{{ $invoice ? asset('/img/invoice-white.png') : asset('/img/invoice.png')}}" width="20" alt="Inv."></button>

                  <button type="button" class="m-1 btn {{ $history ? 'btn-warning text-white' : 'btn-secondary' }}" wire:click="isHistory">@lang('History')</button>
                </div>
                &nbsp;

                <div class="col-md-3">
                  <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
                    <button type="button" class="btn btn-outline-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
                  </div>
                </div>
                &nbsp;

                {{-- <div class="col-md-1">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" wire:model="deleted" class="custom-control-input" id="deletedSwitch">
                    <label class="custom-control-label" for="deletedSwitch"> <p class="{{ $deleted ? 'text-primary' : 'text-dark' }}"> @lang('Deletions')</p></label>
                  </div>
                </div> --}}

            </div>
        </div>
        <div class="card-body">

          @if($history)
            <div class="alert alert-warning text-center" role="alert">
              El historial me muestra todos los movimientos con o sin corte de caja asignado. 
              <img src="{{ asset('/img/dog.gif')}}" width="50" alt="Dog">
            </div>
          @endif

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
                <input wire:model.debounce.350ms="searchTerm" class="form-control input-search-blue" type="text" placeholder="{{ __('Search') }}..." />
                @if($searchTerm !== '')
                <div class="input-group-append">
                  <button type="button" wire:click="clear" class="close" aria-label="Close">
                    <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
                  </button>

                </div>
                @endif
              </div>
            </div>

            @if($selected && $finances->count() && !$deleted)
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

          @if($selectPage)
            <x-utils.alert type="primary">
              @unless($selectAll)
              <span>Tienes seleccionado <strong>{{ $finances->count() }}</strong> movimientos, ¿quieres seleccionar  <strong>{{ $finances->total() }} </strong> movimientos?</span>
                <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
              @else
                <span>Actualmente seleccionaste <strong>{{ $finances->total() }}</strong> movimientos.</span>
              @endif

              <em>-- @lang('Order by name') --</em>

            </x-utils.alert>
          @endif

          <div class="table-responsive">
          	<table class="table table-hover table-outline mb-0 shadow">
          		<thead class="thead-dark">
          			<tr>
                  <th style="width:30px; max-width: 30px;">
                    <label class="form-checkbox">
                      <input type="checkbox" wire:model="selectPage">
                      <i class="form-icon"></i>
                    </label>
                  </th>
          				<th>f.º</th>
                  <th>@lang('Name')</th>
          				<th class="text-center">@lang('Amount')</th>
                  <th class="text-center">@lang('User')/@lang('Associated request')/@lang('Daily cash closing')</th>
          				<th class="text-center">@lang('Comment')</th>
                  <th class="text-center">@lang('Type')</th>
          				<th>@lang('Activity')</th>
                  <th>@lang('Created')</th>
                  <th>@lang('Bill')</th>
                  <th>@lang('Actions')</th>
          			</tr>
          		</thead>
          		<tbody>
                @foreach($finances as $finance)
          			<tr>
                  <td>
                    <label class="form-checkbox">
                        <input type="checkbox" wire:model="selected" value="{{ $finance->id }}">
                        <i class="form-icon"></i>
                      </label>
                  </td>
                  <td>
                    #{{ $finance->id }}
                  </td>
          				<td>
          					<div> {{ $finance->name }} </div>
          					@if($finance->date_entered)
                      <div class="small text-muted">@lang('Date entered'): 
                        <strong>
                          {{ $finance->date_entered->format('d-m')}}
                        </strong>
                      </div>
                    @endif
          				</td>
          				<td class="text-center {{ $finance->finance_text }}">
                    ${{ number_format($finance->amount, 2) }}
                    <p>
                      <span class="badge {{ $paymentFilter ? 'badge-primary' : 'badge-secondary'}}" wire:click="filterPayment({{ $finance->payment_method_id }})">{{ $finance->payment_method }}</span>
                    </p>
          				</td>
          				<td>
          					<div class="clearfix">
                      {!! $finance->user_name !!}
                      {!! $finance->order_folio !!}
                      {!! $finance->cash_title !!}
          					</div>
          				</td>
                  <td class="text-center">
                    
                    <x-utils.undefined :data="Str::limit($finance->comment, 60)"/>

                  </td>
          				<td class="text-center">
                    <span class="badge {{ $finance->finance_classes }} text-white">
                      <x-utils.undefined :data="$finance->formatted_type"/>
                    </span>
          				</td>
          				<td>
          					<div class="small text-muted">@lang('Updated')</div><strong>{{ $finance->date_diff_for_humans }}</strong>
          				</td>
                  <td>
                    <div class="small text-muted"></div><strong>{{ $finance->date_diff_for_humans_created }}</strong>
                  </td>
                  <td>
                    @if($finance->is_bill)
                      <button wire:loading.attr="disabled" href="#!" wire:click="bill({{ $finance->id }})" class="badge badge-primary">@lang('Yes')</button>
                    @else
                      <button wire:loading.attr="disabled" href="#!" wire:click="bill({{ $finance->id }})" class="badge badge-danger">@lang('No')</button>
                    @endif
                  </td>
                  <td>
                    @if(!$status == 'deleted')
                      <div class="btn-group" role="group" aria-label="Basic example">

                        <a type="button" href="{{ route('admin.store.finances.print', $finance->id) }}" target="_blank" class="btn btn-transparent-dark">
                          <i class="fa fa-print"></i>
                        </a>

                        <x-actions-modal.edit-icon target="editFinance" emitTo="backend.store.finance.edit-finance" function="edit" :id="$finance->id" />
                        @if(!$finance->cash_id)
                          <x-actions-modal.delete-icon function="delete" :id="$finance->id" />
                        @endif
                      </div>
                    @endif
                  </td>
          			</tr>
                @endforeach
          		</tbody>
          	</table>
            </div>     
		      <nav class="mt-4">
            @if($finances->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $finances->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $finances->firstItem() }} - {{ $finances->lastItem() }} de {{ $finances->total() }} resultados
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
