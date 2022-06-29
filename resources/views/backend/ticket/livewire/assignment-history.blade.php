<div class="row">
  <div class="col-sm-9">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ $name }}</h5>
        <p class="card-text">Entrega total de productos: {{ $user->total_quantities }}</p>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Total <i class="cil-mood-very-good"></i></h5>
        <p class="card-text">${{ $user->total_quantities_with_making }}</p>
      </div>
    </div>
  </div>

  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">

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
                  esta semana (no.{{ now()->isoFormat('W') }} del año)
                @else
                  {{ $dateInput }} {{ $dateOutput ? '- '.$dateOutput : __('to this day') }}
                @endif

                <button type="button" class="btn btn-primary btn-sm disabled">@lang('Export') (@lang('Disabled'))</button>

            </div>
          </div>
        </div>

        <div class="row mb-4 justify-content-center">
          <div class="col-6">
            <div class="input-group">
              <input wire:model.debounce.350ms="searchTerm" class="form-control text-center" type="text" placeholder="{{ __('Search') }}" />
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

        <ul class="list-group">
          @foreach($assignments as $assignment)
            <li class="list-group-item list-group-item-action flex-column align-items-start">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{!! $assignment->assignment->assignmentable->product->full_name !!}
                  <span class="badge badge-primary badge-pill">{{ $assignment->quantity }}</span>
                  @if($assignment->assignment->assignmentable->product->parent->price_making)
                   <i class="cil-x"></i>
                    ${{ $assignment->assignment->assignmentable->product->parent->price_making ?? null }}
                    =
                    <strong class="text-danger">${{ $assignment->assignment->assignmentable->product->parent->price_making * $assignment->quantity }}</strong>
                  @endif
                </h5>
                <small class="text-muted">{{ $assignment->created_at }}</small>
              </div>
              <p class="mb-1">@lang('Ticket'): #{{ $assignment->ticket_id }}</p>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

</div>
