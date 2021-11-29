@push('after-styles')
    <style>
      .table-striped>tbody>tr:nth-child(odd)>td, 
      .table-striped>tbody>tr:nth-child(odd)>th {
       background-color: #BEFFDF;
      }
      .table-striped>tbody>tr:nth-child(even)>td, 
      .table-striped>tbody>tr:nth-child(even)>th {
       background-color: #fff;
      }
      .table-striped>thead>tr>th {
         background-color: #eee;
      }
    </style>
@endpush

<div class="card shadow-lg p-3 mb-5 bg-white rounded ">

  @include('backend.departament.create')
  @include('backend.departament.show')
  @include('backend.departament.update')

  <div class="card-header" style="background-color:#E6F3ED;">
    @if($deleted)
      <strong style="color: red;"> @lang('List of deleted departaments') </strong>
    @else
      <strong style="color: #0061f2;"> @lang('List of departaments') </strong>
    @endif
    <div class="card-header-actions">
       <em> Última petición: {{ now()->format('h:i:s') }} </em>
      <a href="#" class="card-header-action" style="color: green;" data-toggle="modal" wire:click="createmodal()" data-target="#exampleModal"><i class="c-icon cil-plus"></i> @lang('Create departament') </a>
    </div>

    <div class="row justify-content-md-end">
        <div class="col-md-2 mt-2">
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

    @if($selected && $departaments->count() && !$deleted)
    <div class="dropdown table-export">
      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @lang('Export')        
      </button>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" wire:click="exportMaatwebsite('csv')">CSV</a>
        <a class="dropdown-item" wire:click="exportMaatwebsite('pdf')">PDF</a>
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
  <span>Tienes seleccionado <strong>{{ $departaments->count() }}</strong> colores, ¿quieres seleccionar  <strong>{{ $departaments->total() }} </strong> colores?</span>
    <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
  @else
    <span>Actualmente seleccionaste <strong>{{ $departaments->total() }}</strong> colores.</span>
  @endif
</x-utils.alert>
@endif

  <div class="row mt-4">
    <div class="col">
      <div class="table-responsive">
        <table class="table table-sm align-items-center table-striped table-flush table-bordered table-hover">
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
                  @lang('Name')
                  @include('backend.includes._sort-icon', ['field' => 'name'])
                </a>
              </th>
              <th scope="col">@lang('Email')</th>
              <th scope="col">@lang('Comment')</th>
              <th scope="col">@lang('Updated at')</th>
              <th scope="col" style="width:90px; max-width: 90px;">@lang('Actions')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($departaments as $departament)
            <tr>
              @if(!$deleted)
                <td>
                  <label class="form-checkbox">
                      <input type="checkbox" wire:model="selected" value="{{ $departament->id }}">
                    <i class="form-icon"></i>
                    </label>
                </td>
              @endif
              <th scope="row">
                  <div> {{ $departament->name }} </div>
                  <div class="small text-muted">@lang('Registered'): {{ $departament->date_for_humans_created }}</div>
                  <div>
                    {!! $departament->is_disabled !!}
                  </div>
              </th>
              <td>
                {{ $departament->email }}
              </td>
              <td>
                <x-utils.undefined :data="$departament->comment"/>
              </td>
              <td>
                {{ $departament->updated_at }}
              </td>
              <td>
                <div class="btn-group" role="group" aria-label="Basic example">

                    <button type="button" data-toggle="modal" data-target="#showModal" wire:click="show({{ $departament->id }})" class="btn btn-transparent-dark">
                        <i class='far fa-eye'></i>
                    </button>

                  @if(!$departament->trashed())

                    <button type="button" data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $departament->id }})" class="btn btn-transparent-dark">
                      <i class='far fa-edit'></i>
                    </button>

                    <div class="dropdown">
                      <a class="btn btn-icon-only " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        @if($departament->is_enabled)
                          <a class="dropdown-item" wire:click="disable({{ $departament->id }})">@lang('Disable')</a>
                        @else
                          <a class="dropdown-item" wire:click="enable({{ $departament->id }})">@lang('Enable')</a>
                        @endif
                        <a class="dropdown-item" wire:click="delete({{ $departament->id }})">@lang('Delete')</a>
                      </div>
                    </div>

                  @else
                    <div class="dropdown">
                      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item" href="#" wire:click="restore({{ $departament->id }})">
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

        @if($departaments->count())
        <div class="row">
          <div class="col">
            <nav>
              {{ $departaments->links() }}
            </nav>
          </div>
              <div class="col-sm-3 text-muted text-right">
                Mostrando {{ $departaments->firstItem() }} - {{ $departaments->lastItem() }} de {{ $departaments->total() }} resultados
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

      </div>

    </div>
  </div>
</div>
</div>

