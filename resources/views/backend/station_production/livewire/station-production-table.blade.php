<div class="animated fadeIn">

  <!-- /.row-->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">

            <div class="card p-3 border-0">
              <div class="row">
                <div class="col-sm-4">
                  <div class="c-callout b-t-1 b-r-1 b-b-1 pb-5 pt-2 shadow">
                    <small class="text-muted">Actual: </small><br>
                    <select id="selectStatus" wire:model="selectStatus" class="form-control" onfocus="disableKeyUpDown()">
                        <option value="">Elegir Estación 👈</option>
                        @foreach(\App\Models\Status::orderBy('level')->whereActive(true)->get() as $s)
                          <option style="color:#0071c5;" value="{{ $s->id }}">
                            <strong>
                              {{ ucfirst($s->name) }}
                            </strong>
                          </option>
                        @endforeach
                    </select>
                    

                  </div>
                </div><!--/.col-->
`
                <div class="col-sm-6 mt-3">
                  <div class="alert alert-light shadow-sm" role="alert">
                    <p>
                      <kbd>
                        @lang('Workstation') @if($statusName) ——> {{ __(ucfirst($statusName)) }} 📌 @endif
                      </kbd>

                      @if($selectStatus)
                        <a type="button" wire:click="clearSelectStatus" class="btn btn-danger btn-sm ml-4 text-white"> <i class="cil-x-circle"></i> </a>
                      @endif

                    </p>
                    <strong> @lang('Search by'): </strong> folio, comentario, nombre del personal, comentario del pedido, orden de compra, factura y folio del pedido.
                  </div>
                </div><!--/.col-->
              </div>
            </div>

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



              <a type="button" target="_blank" href="{{ route('admin.information.status.printexportreceivedproduction', [$selectStatus ?? 6, true, $dateInput ?: 0, $dateOutput ?: 0, $personal ?? 0]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$personal  ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;': ''}}">Exportar histórico </a>

              <a type="button" target="_blank" href="{{ route('admin.information.status.printexportreceivedproduction', [$selectStatus ?? 6, 0, $dateInput ?: 0, $dateOutput ?: 0, $personal ?? 0]) }}" class="btn btn-primary mb-4 mr-2" style="{{ !$personal ? 'pointer-events: none; cursor: default; color: #ccc; background-color: #6c757d;': ''  }}">Exportar histórico, agrupado </a>

            <div class="page-header-subtitle  mb-2">
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
                <input wire:model.debounce.350ms="searchTerm" class="form-control input-search-green" type="text" placeholder="🔍 {{ __('Search') }}..." />
                @if($searchTerm !== '')
                <div class="input-group-append">
                  <button type="button" wire:click="clear" class="close" aria-label="Close">
                    <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
                  </button>

                </div>
                @endif
              </div>
            </div>

            @if($selected && $stations->count() && !$deleted)
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
                    @lang('Order')
                    @include('backend.includes._sort-icon', ['field' => 'order_id'])
                  </a>
                </th>
                <th class="text-center">@lang('Customer')</th>
                <th class="text-center">@lang('Total')</th>
                <th class="text-center">@lang('Comment')</th>
                <th class="text-center">@lang('Personal')</th>
                <th class="text-center">@lang('Details')</th>
        				<th class="text-center">@lang('Status')</th>
                <th></th>
        			</tr>
        		</thead>
        		<tbody>
              @foreach($stations as $station)
        			<tr>
                <td>
                  <a href=" {{ route('admin.order.production_batch', [$station->order_id, $station->id]) }}" target="_blank"><strong> #{{ $station->folio ?? $station->id }} <i class="fas fa-external-link-alt m-1"></i></strong> </a>

                </td>
                <td>
                  <a target="_blank" href="{{ route('admin.order.work', [$station->order_id, $station->status_id]) }}"> 
                    #{!! $station->order->folio_or_id !!}
                    <i class="fas fa-external-link-alt m-1"></i>
                  </a> 
                </td>
                <td class="text-center">
                  {!! optional($station->order)->user_name !!}
                </td>
                <td class="text-center">
                  @if($status != 'deleted')
                    {{ $station->total_products_station }}
                  @else
                    {{ $station->total_products_station_deleted }}
                  @endif
                </td>
                <td>
                  <strong>{{ optional($station->order)->comment ?? '--' }}</strong>
                  <div class="small text-muted">{{ $station->notes ?? '--' }}</div>
                </td>
        				<td class="text-center">
                  <div> {{ optional($station->personal)->name ?? '--' }} </div>
        					<div class="small text-muted">{{ $station->date_for_humans }}</div>
        				</td>
        				<td class="text-center">
                  <span class='badge badge-primary'>{{ $station->status->name }}</span>
                  @if($station->allItemsAreBalanced())
                    <br>
                    <span class="badge" style="background-color: purple; color: white;"> Total Recibido </span>
                  @endif
        				</td>
                <td class="text-center">
                  @if($station->total_products_prod_active > 0)
                    {{ $station->total_products_prod_active }}<br><span class="badge badge-success">@lang('Active')</span>
                  @else
                    <span class="badge badge-danger">@lang('Inactive')</span>
                  @endif

                </td>
                <td >
                  @if($station->order->trashed())
                    {{-- <x-utils.delete-button :text="__('')" :href="route('admin.batch.destroy', $station->id)" /> --}}
                    <span class="badge badge-danger">Orden eliminada</span>
                  @else
                    {{-- <div class="btn-group" role="group" aria-label="Basic example">
                        <x-utils.view-button :href="route('admin.order.batches', [$station->order_id, $station->status_id])" />
                    </div> --}}
                  @endif
                </td>
        			</tr>
              @endforeach
        		</tbody>
        	</table>          
		      <nav class="mt-4">
            @if($stations->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $stations->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $stations->firstItem() }} - {{ $stations->lastItem() }} de {{ $stations->total() }} resultados
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
