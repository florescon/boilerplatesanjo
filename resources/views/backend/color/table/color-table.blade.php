<div class="card shadow-lg p-3 mb-5 bg-white rounded gradient-box">

  @include('backend.color.update')
  @include('backend.color.create')
  @include('backend.color.show')

  <div class="card-header" style="background: rgb(238,174,202);
  background: linear-gradient(90deg, rgba(238,174,202,1) 0%, rgba(148,233,202,1) 100%);">
    @if($deleted)
      <strong style="color: red;"> @lang('List of deleted colors') </strong>
    @else
      <strong style="color: #0061f2;"> @lang('List of colors') </strong>
    @endif
    <div class="card-header-actions">
      <em> @lang('Last request'): {{ now()->format('h:i:s') }} </em>
      @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.color.create'))
        <a href="#" class="card-header-action" style="color: green;"  data-toggle="modal" wire:click="createmodal()" data-target="#exampleModal"><i class="c-icon cil-plus"></i> @lang('Create color') </a>
      @endif
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
          
          @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.color.deleted'))
            <div class="col-md-1">
              <div class="custom-control custom-switch">
                <input type="checkbox" wire:model="deleted" class="custom-control-input" id="deletedSwitch">
                <label class="custom-control-label" for="deletedSwitch"> <p class="{{ $deleted ? 'text-primary' : 'text-dark' }}"> @lang('Deletions')</p></label>
              </div>
            </div>
          @endif
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


      @if($selected && $colors->count() && !$deleted)
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
    <span>Tienes seleccionado <strong>{{ $colors->count() }}</strong> colores, ¿quieres seleccionar  <strong>{{ $colors->total() }} </strong> colores?</span>
      <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
    @else
      <span>Actualmente seleccionaste <strong>{{ $colors->total() }}</strong> colores.</span>
    @endif

    <em>-- @lang('Order by name') --</em>

  </x-utils.alert>
  @endif

    <div class="row mt-4">
      <div class="col">
        <div class="table-responsive">
          <table class="table  table-sm align-items-center table-flush table-bordered table-hover">
            <thead style="
            " class="thead-dark">
              <tr>
                @if(!$deleted && ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.color.export')))
                  <th style="width:30px; max-width: 30px;">
                    <label class="form-checkbox">
                      <input type="checkbox" wire:model="selectPage">
                      <i class="form-icon"></i>
                    </label>
                  </th>
                @endif
                <th scope="col">
                  <a style="color:white;" wire:click.prevent="sortBy('name')" role="button" href="#">
                    @lang('Name')
                    @include('backend.includes._sort-icon', ['field' => 'name'])
                  </a>
                </th>
                <th scope="col" class="text-center">
                  <a style="color:white;" wire:click.prevent="sortBy('short_name')" role="button" href="#">
                    @lang('Coding')
                    @include('backend.includes._sort-icon', ['field' => 'short_name'])
                  </a>
                </th>
                
                <th scope="col" class="text-center">@lang('Color hex code')</th>

                <th style="width:45px; max-width: 45px;">
                  @lang('Prim')
                </th>
                <th style="width:45px; max-width: 45px;">
                  @lang('Sec')
                </th>
                <th scope="col" class="text-center"># @lang('Associates')</th>

                <th scope="col" class="text-center"># @lang('Associated subproducts')</th>

                <th scope="col">
                  <a style="color:white;" wire:click.prevent="sortBy('updated_at')" role="button" href="#">
                    @lang('Updated at')
                    @include('backend.includes._sort-icon', ['field' => 'updated_at'])
                </a>
                </th>
                <th scope="col" style="width:90px; max-width: 90px;">@lang('Actions')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($colors as $color)
              <tr>
                @if(!$deleted && ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.color.export')))
                  <td class="text-center">
                    <label class="form-checkbox">
                        <input type="checkbox" wire:model="selected" value="{{ $color->id }}">
                        <i class="form-icon"></i>
                      </label>
                  </td>
                @endif
                <th >
                  {{ $color->name }}
                </th>
                <td class="align-middle text-center">
                  <x-utils.undefined :data="$color->short_name"/>
                </td>
                <td class="align-middle text-center">
                  <x-utils.undefined :data="$color->color"/>
                </td>
                <td class="align-middle" style="background-color: {{ $color->color }}">
                  {!! !$color->color ? 
                    '<div class="d-flex justify-content-center">
                      <i class="cil-x-circle"></i>
                    </div>' 
                    : '' 
                  !!}
                </td>
                <td class="align-middle" style="background-color: {{ $color->secondary_color }}">
                  {!! !$color->secondary_color ? 
                    '<div class="d-flex justify-content-center">
                      <i class="cil-x-circle"></i>
                    </div>' 
                    : '' 
                  !!}
                </td>
                <td class="text-center align-middle">
                  <a href="{{ route('admin.color.associates', $color->id) }}"> {{ $color->count_product }}</a>
                </td>
                <td class="text-center align-middle">
                  <a href="{{ route('admin.color.associates_sub', $color->id) }}"> {{ $color->count_products }}</a>
                </td>
                <td class="align-middle">
                  <span class="badge badge-dot mr-4">
                    <i class="bg-warning"></i> {{ $color->date_for_humans }}
                  </span>
                </td>
                <td>
                  <div class="btn-group" role="group" aria-label="Basic example">


                      <button type="button" data-toggle="modal" data-target="#showModal" wire:click="show({{ $color->id }})" class="btn btn-transparent-dark">
                          <i class='far fa-eye'></i>
                      </button>

                    @if(!$color->trashed())

                      @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.color.modify'))
                        <button type="button" data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $color->id }})" class="btn btn-transparent-dark">
                          <i class='far fa-edit'></i>
                        </button>
                      @endif

                      @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.color.delete'))
                        <div class="dropdown">
                          <a class="btn btn-icon-only " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" wire:click="delete({{ $color->id }})">@lang('Delete')</a>
                          </div>
                        </div>
                      @endif

                    @else
                      <div class="dropdown">
                        <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          <a class="dropdown-item" href="#" wire:click="restore({{ $color->id }})">
                            @lang('Restore')
                          </a>
                        </div>
                      </div>
                    @endif

                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          @if($colors->count())
          <div class="row">
            <div class="col">
              <nav>
                {{ $colors->onEachSide(1)->links() }}
              </nav>
            </div>
                <div class="col-sm-3 mb-2 text-muted text-right">
                  Mostrando {{ $colors->firstItem() }} - {{ $colors->lastItem() }} de {{ $colors->total() }} resultados
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