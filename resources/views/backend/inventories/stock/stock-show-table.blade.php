<div class="animated fadeIn">

  <!-- /.row-->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="card p-3 border-0">
              <div class="mt-3">
                <h3 class="heading">
                  @lang('Store inventory')
                  <br>
                  f.º <em class="text-primary">#{{ $inventory_id }}</em>
                </h3>
                @if($status == 'deleted')
                  <a href="{{ route('admin.store.box.history') }}">
                    <i class="fa fa-hand-o-left" aria-hidden="true"></i>
                   @lang('to return')
                 </a>
                @endif
              </div>
          </div>
          Productos capturados: {{ $inventory->captured() }}<br>
          Productos totales: {{ $inventory->total() }}
        </div>
        <div class="card-body">

          @if($selectPage)
            <x-utils.alert type="primary">
              @unless($selectAll)
              <span>Tienes seleccionado <strong>{{ $cashes->count() }}</strong> productos, ¿quieres seleccionar  <strong>{{ $cashes->total() }} </strong> productos?</span>
                <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
              @else
                <span>Actualmente seleccionaste <strong>{{ $cashes->total() }}</strong> productos.</span>
              @endif

              <em>-- @lang('Order by name') --</em>

            </x-utils.alert>
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

            @if($selected && $cashes->count() && !$deleted)
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
                <th style="width:30px; max-width: 30px;">
                  <label class="form-checkbox">
                    <input type="checkbox" wire:model="selectPage">
                    <i class="form-icon"></i>
                  </label>
                </th>
        				<th>
                  <a style="color:white;" wire:click.prevent="sortBy('id')" role="button" href="#">
                    Identificador
                    @include('backend.includes._sort-icon', ['field' => 'id'])
                  </a>
                </th>
                <th class="text-center">@lang('Product')</th>
                <th class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('capture')" role="button" href="#">
                    Capturado
                    @include('backend.includes._sort-icon', ['field' => 'capture'])
                  </a>
                </th>
                <th class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('stock')" role="button" href="#">
                    Total
                    @include('backend.includes._sort-icon', ['field' => 'stock'])
                  </a>
                </th>
                <th class="text-center">Diferencia</th>
                <th class="text-center">Detalle</th>
                <th>@lang('Created')</th>
                <th></th>
        			</tr>
        		</thead>
        		<tbody>
              @foreach($cashes as $cash)
        			<tr>
                <td>
                  <label class="form-checkbox">
                      <input type="checkbox" wire:model="selected" value="{{ $cash->id }}">
                    <i class="form-icon"></i>
                    </label>
                </td>
                <td>
                  {{ $cash->id }}
                </td>
        				<td class="text-center">
        					<div> {!! $cash->product->full_name !!} </div>
                  <div class="small text-muted">@lang('Code'): <em class="text-primary">{!! $cash->product->code_label !!}</em></div>
        				</td>
                <td class="text-center">
                  {{ $cash->capture }}
                </td>
                <td class="text-center">
                  {{ $cash->stock }}
                </td>
                <td class="text-center table-secondary">
                  {{ $cash->difference() }}
                </td>
                <td class="text-center table-secondary">
                  {!! $cash->description_difference !!}
                </td>
                <td>
                  <div class="small text-muted"></div><strong>{{ $cash->created_at }}</strong>
                </td>
                <td >
                </td>
        			</tr>
              @endforeach
        		</tbody>
        	</table>          
		      <nav class="mt-4">
            @if($cashes->count())
            <div class="row">
              <div class="col">
                <nav>
                  {{ $cashes->onEachSide(1)->links() }}
                </nav>
              </div>
                  <div class="col-sm-3 text-muted text-right">
                    Mostrando {{ $cashes->firstItem() }} - {{ $cashes->lastItem() }} de {{ $cashes->total() }} resultados
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
