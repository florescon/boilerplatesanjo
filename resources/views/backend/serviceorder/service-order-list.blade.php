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
                    @lang('Service Orders')
                    @if($status == 'deleted')
                      <span class="badge badge-danger">@lang('Deletions')</span>
                    @endif
                  </kbd>
                </h5>
                @if($status == 'deleted')
                  <a href="{{ route('admin.serviceorder.index') }}">
                    <i class="fa fa-hand-o-left" aria-hidden="true"></i>
                   @lang('to return')
                 </a>
                @endif
              </div>
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

                <div class="col-md-3">
                  {{-- <div class="btn-group" role="group" aria-label="Range date">
                    <button type="button" class="btn {{ $currentMonth ? 'btn-success' : 'btn-secondary' }}" wire:click="isCurrentMonth">@lang('Current month')</button>
                    <button type="button" class="btn {{ $currentWeek ? 'btn-success' : 'btn-secondary' }}" wire:click="isCurrentWeek">@lang('Current week')</button>
                    <button type="button" class="btn {{ $today ? 'btn-success' : 'btn-secondary' }}" wire:click="isToday">@lang('Today')</button>
                  </div> --}}
                  <button type="button" class="m-1 btn {{ $history ? 'btn-warning text-white' : 'btn-secondary' }}" wire:click="isHistory">@lang('History')</button>
                  <br>
                </div>
                &nbsp;

                <div class="col-md-3">
                  <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-outline-success" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
                    <button type="button" class="btn btn-outline-success" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
                  </div>
                </div>
                &nbsp;
                
            </div>

            <div class="row justify-content-md-center mt-3">
              <div class="col col-lg-6">
                <livewire:backend.user.only-admins/>
              </div>
              <div class="col col-lg-1">
                @if($personal)
                  <a wire:click="clearPersonal" class="text-danger"><em> Limpiar personal</em></a>
                @endif
              </div>
              <div class="col col-lg-5">
                  <a type="button" href="{{ route('admin.serviceorder.printexportserviceorder', [$dateInput ?: 0, $dateOutput ?: 0, $personal ?? 0]) }}" target="_blank" class="m-1 btn btn-primary" style="{{ ($history && $personal && $dateInput && $dateOutput) ?  '' :  'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;'  }}">@lang('Export')</a>

                  <a type="button" href="{{ route('admin.serviceorder.printexportserviceorder', [$dateInput ?: 0, $dateOutput ?: 0, $personal ?? 0, true]) }}" target="_blank" class="m-1 btn btn-primary" style="{{ ($history && $personal && $dateInput && $dateOutput) ?  '' :  'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;'  }}">@lang('Export Grouped')</a>

              </div>
            </div>

        </div>
        <div class="card-body">
          @if($history)
            <div class="alert alert-info text-center" role="alert">
              El historial me muestra todas las órdenes de servicio pendientes y listas.
              {{-- <img src="{{ asset('/img/tiger.gif')}}" width="50" alt="Tiger"> --}}
            </div>
          @endif

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

            @if($selected && $serviceOrders->count() && !$deleted)
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
        				<th>
                  <a style="color:white;" wire:click.prevent="sortBy('id')" role="button" href="#">
                    f.º
                    @include('backend.includes._sort-icon', ['field' => 'id'])
                  </a>
                </th>
                <th>
                  <a style="color:white;" wire:click.prevent="sortBy('order_id')" role="button" href="#">
                    @lang('Request')
                    @include('backend.includes._sort-icon', ['field' => 'order_id'])
                  </a>
                </th>
                <th class="text-center">@lang('Type')</th>
                <th class="text-center">@lang('Personal')</th>
                <th class="text-center">@lang('Comment')</th>
                <th class="text-center">@lang('File N.')</th>
                <th class="text-center">@lang('Created by')</th>
                <th class="text-center">@lang('Details')</th>
        				<th class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('done')" role="button" href="#">
                    @lang('Status')
                    @include('backend.includes._sort-icon', ['field' => 'done'])
                  </a>
                </th>
                <th class="text-center">@lang('Actions')</th>
        			</tr>
        		</thead>
        		<tbody>
              @foreach($serviceOrders as $serviceOrder)
        			<tr>
                <td>
                  
                  <a href="{{ route('admin.order.print_service_order', [$serviceOrder->order_id, $serviceOrder->id]) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
                    <ins>
                      #{{ $serviceOrder->id }}
                    </ins>
                  </a>

                </td>
                <td>
                  <a href="{{ route('admin.store.all.edit', $serviceOrder->order_id) }}" class="card-link text-dark" target="_blank">
                    <ins class="mr-2">
                      #{!! optional($serviceOrder->order)->folio_or_id !!}
                    </ins>
                    <i class="cil-external-link"></i>
                  </a>
                  <div class="small text-muted">@lang('Request')/@lang('Sale')</div>
                </td>
                <td>
                  @if($serviceOrder->image_id)
                    <img class="card-img-top" style="width:100px !important;" src="{{ asset('/storage/' . $serviceOrder->image->image) }}" alt="{{ optional($serviceOrder->image)->title }}">
                  @endif
                </td>
                <td class="text-center">
                  {{ optional($serviceOrder->personal)->name }}
                  @if($logged_in_user->hasAllAccess())
                    <x-actions-modal.edit-icon target="assignPersonal" emitTo="backend.service-order.assign-personal" function="assignpersonal" :id="$serviceOrder->id" />
                  @endif
                </td>
                <td class="text-center">
                  {{ optional($serviceOrder->order->user)->name.' '.optional($serviceOrder->order)->info_customer }}
                  <strong>{{ optional($serviceOrder->order)->comment ?? '--' }}</strong>
                  <div class="small text-muted">{{ $serviceOrder->comment }}</div>
                </td>
                <td class="text-center">
                  {{ $serviceOrder->file_text ?? '--' }}
                </td>
        				<td class="text-center">
        					<div> 
                    {{ optional($serviceOrder->createdby)->name ?? __('undefined') }}</a> 
                  </div>
        					<div class="small text-muted">{{ $serviceOrder->date_for_humans }}</div>
        				</td>
        				<td class="text-center">
                  <span class='text-primary'>{{ optional($serviceOrder->service_type)->name }}</span>
                  <div class="small text-muted">@lang('Total'): {{ $serviceOrder->total_products }} </div>
        				</td>
                  <td class="text-center">
                    @if($logged_in_user->hasAllAccess())
                      <div class="btn-group" role="group" aria-label="Basic example">
                        @if($serviceOrder->done)
                          <button wire:loading.attr="disabled" href="#!" wire:click="done({{ $serviceOrder->id }})" class="badge badge-primary">@lang('Done')
                            <i class="cil-touch-app"></i>
                          </button>
                        @else
                          <button wire:loading.attr="disabled" href="#!" wire:click="done({{ $serviceOrder->id }})" class="badge badge-danger">@lang('Pending')
                            <i class="cil-touch-app"></i>
                          </button>
                        @endif
                      </div>
                    @else
                      {{ $serviceOrder->is_done_label }}
                    @endif

                    @if($serviceOrder->approved)
                      <br>
                      {{ $serviceOrder->approved_for_humans }}
                    @endif

                  </td>
                <td class="text-center">
                  <div class="btn-group" role="group" aria-label="Basic example">

                    <x-actions-modal.edit-icon target="editServiceOrder" emitTo="backend.service-order.edit-service-order" function="edit" :id="$serviceOrder->id" />

                    <x-actions-modal.delete-icon function="delete" :id="$serviceOrder->id" />

                  </div>

                </td>
        			</tr>
              @endforeach
        		</tbody>
        	</table>          
		      <nav class="mt-4">
            @if($serviceOrders->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $serviceOrders->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $serviceOrders->firstItem() }} - {{ $serviceOrders->lastItem() }} de {{ $serviceOrders->total() }} resultados
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
