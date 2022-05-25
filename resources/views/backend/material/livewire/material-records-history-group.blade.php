<x-backend.card>
  <x-slot name="header">

   <div class="card-header-actions">
    <x-utils.link class="card-header-action" icon="fa fa-chevron-left" :href="route('admin.material.index')" :text="__('Back to feedstock')" />
  </div>

    <h3 class="mb-5">
      <strong class="text-primary"> @lang('List of grouped history feedstock records') </strong>
    </h3>

    <div class="row input-daterange justify-content-center">
        <div class="col-md-3 mr-2 mb-2 pr-5=">
          <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
        </div>

        <div class="col-md-3 mr-2 mb-2">
          <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
        </div>
        &nbsp;

        <div class="col-md-3 mb-2">
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
            <button type="button" class="btn btn-outline-primary" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
          </div>
        </div>
        &nbsp;
    </div>
    <div class="page-header-subtitle mt-2 mb-2 text-center">
      <em>
        @lang('Filter by updated date range')
      </em>
      <div class="mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <strong>Filtrado por</strong>
            @if(!$dateInput)
              mes de {{ now()->isoFormat('MMMM') }}
            @else
              {{ $dateInput }} {{ $dateOutput ? '- '.$dateOutput : __('to this day') }}
            @endif

            <button type="button" class="btn btn-primary btn-sm disabled">@lang('Export') (@lang('Disabled'))</button>

        </div>
      </div>
    </div>

  </x-slot>

  <x-slot name="body">
    <div class="row mb-4 justify-content-center">
      <div class="col-6">
        <div class="input-group">
          <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search by folio, tracking number or comment') }}..." />
          @if($searchTerm !== '')
            <div class="input-group-append">
              <button type="button" wire:click="clear" class="close" aria-label="Close">
                <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
              </button>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="row mt-4 justify-content-center">
     <div class="col-sm-9 col-md-9 col-lg-9">
      <div class="card shadow-lg">
        @forelse ($materials as $day => $material)
          <div class="border border-white">
            <h2 class="text-center mt-4">
              {{ ucfirst($day) }}
            </h2>
            <ul class="list-group list-group-flush ">
              @foreach($material as $key => $appointment)
               <li class="list-group-item  list-group-item-action d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">{!! $appointment->material->full_name !!}</div>
                    {{ $appointment->created_at_iso }}
                    {{ $appointment->comment ? ', '.$appointment->comment : '' }}
                  </div>
                  <div class="col-6">
                    @if($appointment->price != $appointment->old_price)
                    <div class="form-check-inline text-center">
                        <label>
                          ${{ $appointment->old_price }}<br>
                          @lang('Old price')
                        </label>
                      </div>
                    @endif
                    <div class="form-check-inline text-center">
                      <label>
                        ${{ $appointment->price }}<br>
                        @lang('Price')
                      </label>
                    </div>
                    <div class="form-check-inline text-center">
                      <label>
                        {{ $appointment->old_stock }}<br>
                        @lang('Old stock')
                      </label>
                    </div>
                    <div class="form-check-inline text-center">
                      <label>
                        {{ $appointment->stock }}<br>
                        @lang('Stock')
                      </label>
                    </div>
                  </div>
                  <span class="badge {{ $appointment->class_stock }}  text-white">{{ $appointment->stock }}</span>
                </li>
                @if($key > 3 && !$loop->last && !in_array($day, $myDate))
                  <li class="list-group-item d-flex justify-content-center align-items-start">
                    <button type="button" class="btn btn-primary btn-sm" style="background-image: linear-gradient(106deg, #64a749, #149ab3); box-shadow: 0 10px 20px -7px rgb(100 167 73 / 90%);" wire:click="loadMore('{{ $day }}')"> @lang('Show more') {{ $day }} => {{ $loop->count }} </button>
                    @if(!in_array($day, $myDate))
                      @break
                    @endif
                  </li>
                @endif
              @endforeach
            </ul>
          </div>

        @empty
          <p class="text-center align-middle mt-3">@lang('No results!')</p>
        @endforelse
      </div>
    </div>
    </div>
  </x-slot>

</x-backend.card>
