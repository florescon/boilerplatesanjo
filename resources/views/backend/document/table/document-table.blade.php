@push('after-styles')
    <style>
      .table-striped>tbody>tr:nth-child(odd)>td, 
      .table-striped>tbody>tr:nth-child(odd)>th {
       background-color: #ffcccc;
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

  @include('backend.document.create')
  @include('backend.document.show')
  @include('backend.document.update')

  <div class="card-header" style="background-color:#ffffcc;">
    @if($deleted)
      <strong style="color: red;"> @lang('List of deleted documents') </strong>
    @else
      <strong style="color: black;"> @lang('List of documents') </strong>
    @endif
    <div class="card-header-actions">
       <em> @lang('Last request'): {{ now()->format('h:i:s') }} </em>
      @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.create'))
        <a href="#" class="card-header-action" style="color: green;" data-toggle="modal" wire:click="createmodal()" data-target="#exampleModal"><i class="c-icon cil-plus"></i> @lang('Create document') </a>
      @endif
    </div>

    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.deleted'))
      <div class="row justify-content-md-end custom-control custom-switch custom-control-inline">
        <em class="{{ $deleted ? 'text-danger' : 'text-dark' }} mt-2"> @lang('Deletions')</em>
          <div class="col-md-2 mt-2">
            <div class="form-check">
              <label class="c-switch c-switch-danger">
                <input type="checkbox" class="c-switch-input" wire:model="deleted">
                <span class="c-switch-slider"></span>
              </label>
            </div>
          </div>
      </div>
    @endif
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

    @if($selected && $documents->count() && !$deleted)
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
  <span>Tienes seleccionado <strong>{{ $documents->count() }}</strong> documentos, ¿quieres seleccionar  <strong>{{ $documents->total() }} </strong> documentos?</span>
    <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
  @else
    <span>Actualmente seleccionaste <strong>{{ $documents->total() }}</strong> documentos.</span>
  @endif

  <em>-- @lang('Sorted by date created descending') --</em>

</x-utils.alert>
@endif

  <div class="row mt-4">
    <div class="col">
      <div class="table-responsive">
        <table class="table table-sm align-items-center table-striped table-flush  table-hover">
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
                <a style="color:white;" wire:click.prevent="sortBy('title')" role="button" href="#">
                  @lang('Title')
                  @include('backend.includes._sort-icon', ['field' => 'title'])
                </a>
              </th>
              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-dst'))
                <th scope="col">@lang('File DST')</th>
              @endif
              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-emb'))
                <th scope="col">@lang('File EMB')</th>
              @endif
              <th scope="col">@lang('Comment')</th>
              <th scope="col">@lang('Updated at')</th>
              <th scope="col" style="width:90px; max-width: 90px;">@lang('Actions')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($documents as $document)
            <tr>
              @if(!$deleted)
                <td>
                  <label class="form-checkbox">
                      <input type="checkbox" wire:model="selected" value="{{ $document->id }}">
                    <i class="form-icon"></i>
                    </label>
                </td>
              @endif
              <th scope="row">
                  <div> 
                    {{ $document->title }} 
                    @if($document->image)
                      <span class="badge badge-success">
                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                      </span>
                    @endif
                  </div>
                  <div class="small text-muted">@lang('Registered'): {{ $document->date_for_humans_created }}</div>
                  <div>
                    {!! $document->is_disabled !!}
                  </div>
              </th>
              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-dst'))
                <td>
                  {!! $document->download_dst !!}
                </td>
              @endif
              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-emb'))
                <td>
                  {!! $document->download_emb !!}
                </td>
              @endif
              <td>
                <x-utils.undefined :data="$document->comment"/>
              </td>
              <td>
                {{ $document->updated_at }}
              </td>
              <td>
                <div class="btn-group" role="group" aria-label="Basic example">

                    <button type="button" data-toggle="modal" data-target="#showModal" wire:click="show({{ $document->id }})" class="btn btn-transparent-dark">
                        <i class='far fa-eye'></i>
                    </button>

                  @if(!$document->trashed())

                    {{-- <button type="button" data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $document->id }})" class="btn btn-transparent-dark">
                      <i class='far fa-edit'></i>
                    </button> --}}

                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.deactivate'))
                      <div class="dropdown">
                        <a class="btn btn-icon-only " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          @if($document->is_enabled)
                            <a class="dropdown-item" wire:click="disable({{ $document->id }})">@lang('Disable')</a>
                          @else
                            <a class="dropdown-item" wire:click="enable({{ $document->id }})">@lang('Enable')</a>
                          @endif
                          <a class="dropdown-item" wire:click="delete({{ $document->id }})">@lang('Delete')</a>
                        </div>
                      </div>
                    @endif

                  @else
                    <div class="dropdown">
                      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item" href="#" wire:click="restore({{ $document->id }})">
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

        @if($documents->count())
        <div class="row">
          <div class="col">
            <nav>
              {{ $documents->links() }}
            </nav>
          </div>
              <div class="col-sm-3 text-muted text-right">
                Mostrando {{ $documents->firstItem() }} - {{ $documents->lastItem() }} de {{ $documents->total() }} resultados
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

