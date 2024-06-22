<div class="animated fadeIn">

  <!-- /.row-->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">

            <div class="card p-3 border-0">

              <div class="row">
                <div class="col-sm-2">
                  <div class="c-callout c-callout-primary b-t-1 b-r-1 b-b-1 pb-3">
                    <small class="text-muted">Entrada</small><br>
                    <strong class="h4">{{ $status->getAllQuantitiesByStatusOpened() }}</strong>
                  </div>
                </div><!--/.col-->
                <div class="col-sm-2">
                  <div class="c-callout c-callout-success b-t-1 b-r-1 b-b-1 pb-3">
                    <small class="text-muted">Recibido</small><br>
                    <strong class="h4">{{ $status->getAllQuantitiesByStatusClosed() }}</strong>
                  </div>
                </div><!--/.col-->
                <div class="col-sm-4">
                  <div class="c-callout b-t-1 b-r-1 b-b-1 pb-3">
                    <small class="text-muted">{{ ucfirst($status->name) }}</small><br>
                    <strong class="h4">{{ ucfirst($status->name) }}</strong>
                  </div>
                </div><!--/.col-->

                <div class="col-sm-4">
                  <div class="c-callout b-t-1 b-r-1 b-b-1 pb-3">
                    <small class="text-muted">Actual: {{ ucfirst($status->name) }}</small><br>
                    <select id="redirectSelect" class="form-control">
                        <option value="NoLink">Redireccionar a Estación</option>
                        @foreach(\App\Models\Status::orderBy('level')->whereActive(true)->get() as $s)
                          <option style="color:#0071c5;" value="{{ route('admin.information.status.show', $s->id) }}">
                            <strong>
                              {{ ucfirst($s->name) }}
                            </strong>
                          </option>
                        @endforeach
                    </select>
                  </div>
                </div><!--/.col-->

              </div><!--/.row-->

              <div class="mt-3">

                <h5 class="heading">
                  <kbd>
                    <i class="cil-short-text"></i> 
                    @lang('Products Station') - {{ ucfirst($status->name) }}
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

            <div class="alert alert-light" role="alert">
              <em>
                La búsqueda es por nombre del producto, código del producto, folio de estación y folio de orden.
              </em>
            </div>

            <a type="button" target="_blank" href="{{ route('admin.information.status.printexportquantities', [$status->id, true]) }}" class="btn btn-outline-dark mb-4 mr-2">Exportar cantidades</a>

            <a type="button" target="_blank" href="{{ route('admin.information.status.printexportquantities', [$status->id, false]) }}" class="btn btn-outline-dark mb-4 mr-2">Exportar cantidades agrupadas</a>

            @if($status->initial_lot)
              <a type="button" target="_blank" href="{{ route('admin.information.status.ticket_materia', $status->id) }}" class="btn btn-outline-dark mb-4">Exportar BOM - Ticket</a>

              <a type="button" target="_blank" href="{{ route('admin.information.status.pending_materia', $status->id) }}" class="btn btn-outline-dark mb-4">Exportar pendiente de consumo</a>

              <a type="button" target="_blank" href="{{ route('admin.information.status.pending_materia_grouped', [$status->id, false]) }}" class="btn btn-outline-dark mb-4">Exportar materia a requerir, por proveedor</a>

              <a type="button" target="_blank" href="{{ route('admin.information.status.add_to_materia', $status->id) }}" class="btn btn-outline-primary mb-4">Agregar materia prima al pedido global</a>
            @endif

            @if($status->supplier)
              <a type="button" target="_blank" href="{{ route('admin.information.status.pending_vendor', $status->id) }}" class="btn btn-outline-dark mb-4">Exportar pedido a proveedor</a>

              <a type="button" target="_blank" href="{{ route('admin.information.status.pending_vendor_grouped', [$status->id, false]) }}" class="btn btn-outline-dark mb-4">Exportar pedido a proveedor, agrupado</a>

              <a type="button" target="_blank" href="{{ route('admin.information.status.add_to_vendor', $status->id) }}" class="btn btn-outline-primary mb-4">Agregar cantidades al pedido global</a>
            @endif


            <a type="button" target="_blank" href="{{ route('admin.information.status.printexporthistory', [$status->id, true]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$history ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;': ''}}">Exportar histórico </a>

            <a type="button" target="_blank" href="{{ route('admin.information.status.printexporthistory', [$status->id, false]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$history ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;': ''  }}">Exportar histórico, agrupado </a>

            {{-- <a type="button" target="_blank" href="{{ route('admin.information.status.printexportreceived', [$status->id, true, $dateInput, $dateOutput, $personal]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$history ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;': '' }}">Exportar recibido </a> --}}


            <a type="button" target="_blank" href="{{ route('admin.information.status.printexportreceived', [$status->id, true, $dateInput ?: 0, $dateOutput ?: 0, $personal ?? 0]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$history ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;' : '' }}">Exportar recibido</a>

            <a type="button" target="_blank" href="{{ route('admin.information.status.printexportreceived', [$status->id, 0, $dateInput ?: 0, $dateOutput ?: 0, $personal ?? 0]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$history ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;': ''  }}">Exportar recibido, agrupado </a>

            @if($status->to_add_users)
              <div class="row justify-content-md-center">
                <div class="col col-lg-6">
                  <livewire:backend.user.only-admins/>
                </div>
                <div class="col col-lg-1">
                  @if($personal)
                    <a wire:click="clearPersonal" class="text-danger"><em> Limpiar personal</em></a>
                  @endif
                </div>
              </div>
            @endif

            <div class="page-header-subtitle mb-2">
              <em>
                @lang('Filter by update date range'). <strong class="text-danger">@lang('Maximum date range'): {{ $this->days }} @lang('days')</strong>
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

            @if($selected && $productsStation->count() && !$deleted)
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
                    ID
                    @include('backend.includes._sort-icon', ['field' => 'id'])
                  </a>
                </th>
                <th>
                  f.º
                </th>
                <th>
                  <a style="color:white;" wire:click.prevent="sortBy('order_id')" role="button" href="#">
                    @lang('Order')
                    @include('backend.includes._sort-icon', ['field' => 'order_id'])
                  </a>
                </th>
                <th class="text-center">@lang('Product')</th>
                <th class="text-center">@lang('Details')</th>
                @if($status->to_add_users)
                <th class="text-center">@lang('Personnel')</th>
                @endif
                <th class="text-center">@lang('About it')</th>
        				<th class="text-center">@lang('Status')</th>
                <th class="text-center"></th>
        			</tr>
        		</thead>
        		<tbody>
              @foreach($productsStation as $productStation)
        			<tr>
                <td>
                  <strong>
                    <a href="{{ route('admin.station.edit', $productStation->station_id) }}" target="_blank"> #{{ $productStation->station_id }} <i class="fas fa-external-link-alt m-1"></i></a>
                  </strong>
                  <div class="small text-muted">@lang('Workstation')</div>
                </td>
                <td>
                  #{{ optional($productStation->station)->folio }}
                </td>
                <td>
                  <a target="_blank" href="{{ route('admin.order.station', [$productStation->order_id, $status->id]) }}"> #{{ $productStation->order_id }} <i class="fas fa-external-link-alt m-1"></i> </a> 
                  <div class="small text-muted">@lang('Order')</div>
                </td>
                <td class="text-center">
                  {!! $productStation->product->full_name_break !!}
                </td>
                <td class="text-center">
                  {!! '[<p style="display:inline; color: blue;">'.$productStation->metadata['open'].'</p>]  [<p style="display:inline; color: green;">'.$productStation->metadata['closed'].'</p>]' !!}<br>
                  <strong>{{ optional($productStation->order)->comment ?? '--' }}</strong>
                </td>
                @if($status->to_add_users)
        				<td class="text-center">
        					<div> {{ optional($productStation->personal)->name ?? '-- --' }} </div>
        					<div class="small text-muted">{{ $productStation->date_for_humans }}</div>
        				</td>
                @endif
        				<td class="text-center">
                  <span class='badge badge-primary'>{{ ucfirst(optional($productStation->status)->name) }}</span>
                  @if($status->id === 14)
                    @if($productStation->not_consider)
                      <span class='badge badge-danger'>Solicitado</span>
                    @endif
                  @endif

                  @if($status->id === 4)
                    @if($productStation->not_consider)
                      <span class='badge badge-danger'>N/A P/BOM</span>
                    @endif
                  @endif

                  <div class="small text-muted">@lang('Total'): 
                    {{ $productStation->quantity }} 
                  </div>
        				</td>
                <td class="text-center">
                  @if(!$productStation->active)
                    <span class="dot-alert"></span>
                  @else
                    <span class="dot-success"></span>
                  @endif
                </td>
                <td class="text-center">
                  @if($productStation->order)
                    @if($productStation->order->trashed())
                      <x-utils.delete-button :text="__('')" :href="route('admin.ticket.destroy', $productStation->id)" />
                    @else
                      <div class="btn-group" role="group" aria-label="Basic example">
                          <x-utils.view-button :href="route('admin.order.assignments', [$productStation->order_id, $productStation->status_id])" />
                      </div>
                    @endif
                  @else
                    <x-utils.delete-button :text="__('Request not found, delete ticket')" :href="route('admin.ticket.destroy', $productStation->id)" />
                  @endif
                </td>
        			</tr>
              @endforeach
        		</tbody>
        	</table>          
		      <nav class="mt-4">
            @if($productsStation->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $productsStation->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $productsStation->firstItem() }} - {{ $productsStation->lastItem() }} de {{ $productsStation->total() }} resultados
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
