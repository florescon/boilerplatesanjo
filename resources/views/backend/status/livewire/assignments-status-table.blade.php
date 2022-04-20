<div class="animated fadeIn">
  <!-- /.row-->
  @if($user)
    <div class="row justify-content-center">
      <!-- /.col-->
      <div class="col-sm-6 col-lg-5">
        <div class="card text-white bg-danger text-monospace">
          <a class="card-block stretched-link text-decoration-none text-white" href="#">
    
            <div class="card-body">
              {{-- <div class="text-value">$98.111,00</div> --}}
              <div class="font-weight-bold text-uppercase">@lang('Expenses')...</div>
              <div class="progress progress-white progress-xs my-2">
                {{-- <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div> --}}
              </div>
                <ul class="list-group list-group-flush text-center">
                  <li class="list-group-item">@lang('Records'): {{ $assignments->total() }}</li>
                  <li class="list-group-item">@lang('Total products'): {{ rtrim(rtrim(sprintf('%.8F', $assignments->sum('quantity')), '0'), ".") }}</li>
                  <li class="list-group-item">@lang('Total'): ${{ rtrim(rtrim(sprintf('%.8F', $assignments->sum('total_quantity')), '0'), ".") }}  </li>
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
                          @elseif($previousWeek)
                            @lang('Previous week')
                          @elseif($today)
                            @lang('Today')
                          @else
                            Seleccione fecha
                          @endif
                        @endif
                      </span>
                    </h4>
                  </li>
                </ul>
                {{-- <small class="text-muted"> {{ $finances->total() }} </small> --}}
            </div>
            <div class="bg-danger card-footer text-right">
              <i class="cil-touch-app"></i>
            </div>
          </a>
        </div>
      </div>
      <!-- /.col-->
    </div>
  @endif

  <!-- /.row-->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-store"></i>
            <strong style="color: #0061f2;"> Productos asignados al personal en estados de ordenes de produccion </strong>
          <div class="card-header-actions">
            <em> @lang('Last request'): {{ now()->format('h:i:s') }} </em>
              <a href="{{ route('admin.status.index') }}" class="card-header-action"><i class="fa fa-chevron-left"></i> @lang('Back') </a>
          </div>

            <div class="page-header-subtitle mt-5 mb-2">
              <em>
                @lang('Filter by date')
              </em>
            </div>

            <div class="row input-daterange">
                {{-- <div class="col-md-2">
                  <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
                </div>
                &nbsp;

                <div class="col-md-2">
                  <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
                </div>
                &nbsp; --}}

                <div class="col-md-3">
                  <div class="btn-group" role="group" aria-label="Range date">
                    <button type="button" class="btn {{ $currentMonth ? 'btn-primary' : 'btn-secondary' }}" wire:click="isCurrentMonth">@lang('Current month')</button>
                    <button type="button" class="btn {{ $currentWeek ? 'btn-primary' : 'btn-secondary' }}" wire:click="isCurrentWeek">@lang('Current week')</button>
                    <button type="button" class="btn {{ $previousWeek ? 'btn-primary' : 'btn-secondary' }}" wire:click="isPreviousWeek">@lang('Previous week')</button>
                    <button type="button" class="btn {{ $today ? 'btn-primary' : 'btn-secondary' }}" wire:click="isToday">@lang('Today')</button>
                  </div>
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

            @if($selected && $assignments->count())
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
        		<thead class="thead-dark">
        			<tr>
        				<th>f.º</th>
                <th>@lang('User')</th>
                <th>@lang('Product')</th>
        				<th class="text-center">@lang('Amount')</th>
                <th class="text-center">@lang('Price')</th>
                <th class="text-center">@lang('Associated order')</th>
        				<th class="text-center">@lang('Ticket')</th>
        				<th>@lang('Activity')</th>
                <th>@lang('Created')</th>
        			</tr>
        		</thead>
        		<tbody>
              @foreach($assignments as $finance)
        			<tr>
                <td>
                  #{{ $finance->id }}
                </td>
        				<td>
        					<div>
                    <a href="#" 
                        wire:click="$emit('filter', {{ $finance->user->id }})"
                    >
                      {{ $finance->user->name }}
                    </a>

                  </div>
        				</td>
                <td>
                  {!! $finance->assignmentable->product->full_name ?? null !!}
                </td>
        				<td class="text-center">
                  {{ $finance->quantity }}
        				</td>
                <td class="text-center">
                  @if($finance->assignmentable->product->parent->price_making ?? null)
                    <div>
                      ${{ $finance->total_quantity }}

                      <div class="small text-muted">@lang('Confeccion price'): 
                        <strong>
                          ${{ $finance->assignmentable->product->parent->price_making }}                          
                        </strong>
                      </div>
                    </div>
                  @else
                    @if(!empty($finance->assignmentable->product))
                      <a type="button" target="_blank" href="{{ route('admin.product.edit', $finance->assignmentable->product->parent_id) }}" class="btn btn-transparent-dark">
                        <i class='far fa-edit'></i> Editar precio confección
                      </a>
                    @endif
                  @endif
                </td>
        				<td>
        					<div class="text-center clearfix">
                    #{{ $finance->order_id }}
        					</div>
        				</td>
                <td class="text-center">
                  #{{ $finance->ticket_id }}
                </td>
        				<td>
        					<div class="small text-muted">@lang('Updated')</div><strong>{{ $finance->date_diff_for_humans }}</strong>
        				</td>
                <td>
                  <div class="small text-muted"></div><strong>{{ $finance->date_diff_for_humans_created }}</strong>
                </td>
        			</tr>
              @endforeach
        		</tbody>
        	</table>          
		      <nav class="mt-4">
            @if($assignments->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $assignments->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $assignments->firstItem() }} - {{ $assignments->lastItem() }} de {{ $assignments->total() }} resultados
                  </div>
            </div>
            @else
              @lang('No search results') 
              @if($searchTerm)
                "{{ $searchTerm }}" 
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
