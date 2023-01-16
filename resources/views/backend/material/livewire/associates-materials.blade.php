<x-backend.card>

  <x-slot name="header">
    @lang('Associated materials') - {{ $attribute->name }}
  </x-slot>

  <x-slot name="headerActions">
    <x-utils.link class="card-header-action" :href="$link ?? null" icon="fa fa-chevron-left" :text="__('Back')" />
  </x-slot>
  <x-slot name="body">
    @if($associates->count() || $searchTerm <> '')
      <div class="row ">
        <div class="col-12 col-sm-12 col-md-12" style="margin-top: 40px;">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-6">
                <div class="c-callout c-callout-info"><small class="text-muted">@lang('Associated materials')</small>
                  <div class="text-value-lg">{{ $attribute->count_materials }}</div>
                </div>
              </div>

              <div class="col-6">
                <div class="c-callout c-callout-danger"><small class="text-muted">Porcentaje de asociados del total de materias primas</small>
                  <div class="text-value-lg">{{ round($attribute->total_percentage_materia, 2) }}%</div>
                </div>
              </div>
            </div>

            <div class="row mb-4 justify-content-md-center">
              <div class="col form-inline">
                @lang('Per page'): &nbsp;

                <select wire:model="perPage" class="form-control">
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                  <option>100</option>
                  <option>200</option>
                </select>
              </div><!--col-->

              <div class="col">
                <div class="input-group">
                  <input wire:model.debounce.350ms="searchTerm" class="form-control shadow border-primary" type="text" placeholder="{{ __('Search') }}..." />
                  @if($searchTerm !== '')
                  <div class="input-group-append">
                    <button type="button" wire:click="clear" class="close" aria-label="Close">
                      <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
                    </button>
                  </div>
                  @endif
                </div>
              </div>

              {{-- @if($selected && $associates->count())
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
              @endif --}}
            </div>

            @if($selectPage)
              <x-utils.alert type="primary">
                @unless($selectAll)
                <span>Tienes seleccionado <strong>{{ $associates->count() }}</strong> materias, ¿quieres seleccionar  <strong>{{ $associates->total() }} </strong> materias?</span>
                  <a href="#" wire:click="selectAll" class="alert-link">Seleccionar todo</a>
                @else
                  <span>Actualmente seleccionaste <strong>{{ $associates->total() }}</strong> materias.</span>
                @endif

                <em>-- @lang('Order by name') --</em>

              </x-utils.alert>
            @endif

            <div class="progress-group">
              <div class="progress-group-header align-items-end">
                <svg class="c-icon progress-group-icon">
                  <use xlink:href="vendors/@coreui/icons/svg/brand.svg#cib-google"></use>
                </svg>
                <div>{{ $attribute->name }}</div>
                <div class="mfs-auto font-weight-bold mfe-2">{{ $attribute->count_materials }}</div>
                <div class="text-muted small">({{ round($attribute->total_percentage_materia, 2) }}%)</div>
              </div>
              <div class="progress-group-bars">
                <div class="progress progress-xs">
                  <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $attribute->total_percentage_materia }}%" aria-valuenow="{{ $attribute->total_percentage_materia }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
          <hr class="mt-0">
          @if($associates->count())
            <div class="row container d-flex justify-content-center">
              <div class="col-lg-10 grid-margin stretch-card">
                <table class="table table-responsive-sm table-hover table-outline mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:30px; max-width: 30px;">
                              <label class="form-checkbox">
                                <input type="checkbox" wire:model="selectPage">
                                <i class="form-icon"></i>
                              </label>
                            </th>
                            <th>@lang('Feedstock')</th>
                            <th>@lang('Created at')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($associates as $material)
                        <tr>
                            <td>
                              <label class="form-checkbox">
                                  <input type="checkbox" wire:model="selected" value="{{ $material->id }}">
                                <i class="form-icon"></i>
                                </label>
                            </td>
                            <td>
                                <div>{!! $material->full_name !!}</div>
                                <div class="small text-muted"><span>{{ $material->created_at }}</span> @lang('Registered material'): {{ $material->date_for_humans_special }}</div>
                            </td>
                            <td>
                                <div class="small text-muted">@lang('Associated')</div><strong>{{ $material->created_at }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                  @if($associates->count())
                    <div class="row">
                      <div class="col">
                        <nav>
                          {{ $associates->onEachSide(1)->links() }}
                        </nav>
                      </div>
                          <div class="col-sm-3 mb-2 text-muted text-right">
                            Mostrando {{ $associates->firstItem() }} - {{ $associates->lastItem() }} de {{ $associates->total() }} resultados
                          </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @else
            <div class="card">
              <div class="card-body text-center">
                @lang('No associated data')
              </div>
            </div>
          @endif
        </div>
      </div>
    @else
      <div class="jumbotron">
          <h1 class="display-3">@lang('No associates!')</h1>
          <p class="lead">@lang('There are no associated feedstocks.')</p>
          <hr class="my-4">
      </div>
    @endif
  </x-slot>

  <x-slot name="footer">
    <footer class="blockquote-footer float-right">
      Mies Van der Rohe <cite title="Source Title">Less is more</cite>
    </footer>
  </x-slot>

</x-backend.card>