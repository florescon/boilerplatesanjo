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

      .card-columns {
        column-count: 4;
      }

    </style>
@endpush

<div class="card shadow-lg p-3 mb-5 bg-white rounded ">

  @include('backend.document.create')
  @include('backend.document.show')
  @include('backend.document.update')

  <div class="card-header" style="background-color:#ffffcc;">
    @if($deleted)
      <strong style="color: red;"> @lang('List of deleted documents_') </strong>
    @else
      <strong style="color: black;"> <kbd>@lang('List of documents_')</kbd> </strong>
    @endif
    <div class="card-header-actions">
      @if (!$deleted && ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.create')))
        <a href="#" class="card-header-action" style="color: green;" data-toggle="modal" wire:click="createmodal()" data-target="#exampleModal"><i class="c-icon cil-plus"></i> @lang('Create document_') </a>
      @endif
    </div>

    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.deleted'))
      <div class="row justify-content-md-end custom-control custom-switch custom-control-inline">
        <em class="{{ $deleted ? 'text-danger' : 'text-dark' }} mt-2"> @lang('Deletions')</em>
          <div class="col-md-1 mt-2">
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
        <option>8</option>
        <option>20</option>
        <option>40</option>
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
      <div class="">

        @if($documents->count())
        <div class="row m-4">
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

      <div class="card-columns">
        @foreach($documents as $document)
          <div class="card">
            @if($document->image)
              <img class="card-img-top" src="{{ asset('/storage/' . $document->image) }}" alt="Card image cap">
            @endif
            <div class="card-body">
              <h5 class="card-title text-center">{{ $document->title }}</h5>
              <h5 class="card-title text-center">{!! $document->is_disabled !!}</h5>
              <p class="card-text text-center">
                {{
                  \Illuminate\Support\Str::limit($document->comment, 70, '...')
                }}
              </p>
            </div>
            <div class="card-body text-center">

              <button type="button" data-toggle="modal" data-target="#showModal" wire:click="show({{ $document->id }})" class="btn btn-transparent-dark">
                  <i class='far fa-eye'></i>
              </button>
              @if(!$deleted)
                <button type="button" data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $document->id }})" class="btn btn-transparent-dark">
                    <i class='far fa-edit'></i>
                </button>
              @endif

              <a href="{{ route('admin.document.threads', $document->id) }}" target="_blank" class="text-danger ml-2 mr-2"> @lang('Threads') <i class="fas fa-external-link-alt m-1"></i></a> 

              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-dst'))
                {!! $document->card_link_dst !!}
              @endif

              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-emb'))
                {!! $document->card_link_emb !!}
              @endif

              @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.show-pdf'))
                {!! $document->card_link_pdf !!}
              @endif

              @if(!$document->trashed())

                {{-- <button type="button" data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $document->id }})" class="btn btn-transparent-dark">
                  <i class='far fa-edit'></i>
                </button> --}}

                @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.document.deactivate'))
                  <div class="dropdown d-inline">
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
            <div class="card-footer text-center">
              <a class="btn btn-outline-danger btn-sm" type="button"  target="_blank"  href="{{ route('admin.document.print', $document->id ) }}"><strong> @lang('File') <i class="fas fa-print m-1"></i> </strong></a>
            </div>
            <div class="card-footer text-muted text-center">
              <em class="text-dark"><strong>@lang('Updated at'):</strong></em> {{ $document->updated_at }}
            </div>
          </div>
        @endforeach
      </div>

      </div>

    </div>
  </div>
</div>
</div>

@push('after-scripts')
  <script>
  document.addEventListener('livewire:load', function () {
      Livewire.on('fileDstRemoved', () => {
          document.getElementById('file_dst').value = '';
      });
  });
  </script>

  <script>
  document.addEventListener('livewire:load', function () {
      Livewire.on('fileEmbRemoved', () => {
          document.getElementById('file_emb').value = '';
      });
  });
  </script>

  <script>
  document.addEventListener('livewire:load', function () {
      Livewire.on('filePdfRemoved', () => {
          document.getElementById('file_pdf').value = '';
      });
  });
  </script>
@endpush