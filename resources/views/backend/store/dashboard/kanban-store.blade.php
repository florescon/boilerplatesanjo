<div class="container-kanban">
  @lang('Last Updated'): {{ now()->format('g:i a') }}

  <div class="container-fluid page-header d-flex justify-content-between align-items-start">
    <div>
      <h1>Sucursal: La Salle</h1>
      {{-- <p class="lead d-none d-md-block">Research, ideate and present brand concepts for client consideration</p> --}}
    </div>
  </div>
  <div class="kanban-board container-fluid mt-lg-3">

    <div class="kanban-col">
      <div class="card-list" style="background-color: #feebe5;">
        <div class="card-list-header">
          <h6>@lang('Quotations')</h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            	+
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">Edit (inactive)</a>
              <a class="dropdown-item text-danger" href="#">Archive (inactive)</a>
            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($quotations as $quotation)
            <div class="card card-kanban">
              <div class="card-body">
                <div class="dropdown card-options">
                  <button class="btn-options" type="button" id="kanban-dropdown-button-13" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  	+
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit (inactive)</a>
                    <a class="dropdown-item text-danger" href="#">Archive (inactive)</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank"  href="{{ route('admin.store.all.edit', $quotation->id) }}" ><h6>{{ Str::limit($quotation->customer, 22) }}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.store.all.edit', $quotation->id) }}"><h6>{{ Str::limit($quotation->comment, 23) }}</h6></a></p>
                </div>
                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $quotation->sum }}</span>
                  </div>
                  <span class="text-small">{{ $quotation->date }}</span>
                </div>
              </div>
            </div>
          @endforeach

          @if($quotations->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
              </div>
            </div>
          @endif

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">Add quotation (inactive)</button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>@lang('Pendings') - @lang('Requests')</h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              +
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">Edit (inactive)</a>
              <a class="dropdown-item text-danger" href="#">Archive (inactive)</a>
            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($requests_pendings as $request)
            <div class="card card-kanban">
              <div class="card-body">
                <div class="dropdown card-options">
                  <button class="btn-options" type="button" id="kanban-dropdown-button-13" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    +
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit (inactive)</a>
                    <a class="dropdown-item text-danger" href="#">Archive (inactive)</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank"  href="{{ route('admin.store.all.edit', $request->id) }}" ><h6>{{ Str::limit($request->customer, 22) }}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.store.all.edit', $request->id) }}"><h6>{{ Str::limit($request->comment, 23) }}</h6></a></p>
                </div>
                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $request->sum }}</span>
                  </div>
                  <span class="text-small">{{ $request->date }}</span>
                </div>
              </div>
            </div>
          @endforeach

          @if($requests_pendings->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
              </div>
            </div>
          @endif

        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>@lang('Pendings') - @lang('Service Orders')</h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              +
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">Edit (inactive)</a>
              <a class="dropdown-item text-danger" href="#">Archive (inactive)</a>
            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($services_pendings as $service)
            <div class="card card-kanban">
              <div class="card-body">
                <div class="dropdown card-options">
                  <button class="btn-options" type="button" id="kanban-dropdown-button-13" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    +
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit (inactive)</a>
                    <a class="dropdown-item text-danger" href="#">Archive (inactive)</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank"  href="{{ route('admin.order.print_service_order', [$service->order_id, $service->id]) }}" ><h6>{!! Str::limit($service->customer, 22) ?? '<em>'.__('undefined personal').'</em> <i class="cil-face-dead"></i>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.store.all.edit', $service->id) }}">
                    <em style="color:purple">{{ Str::limit($service->name_service, 23) }}</em>
                    <h6>
                    {{ Str::limit($service->comment, 23) }}</h6></a></p>
                </div>
                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $service->sum }}</span>
                  </div>
                  <span class="text-small">{{ $service->date }}</span>
                </div>
              </div>
            </div>
          @endforeach

          @if($services_pendings->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
              </div>
            </div>
          @endif

        </div>
      </div>
    </div>
    <div class="kanban-col">
      <div class="card-list">
        <button class="btn btn-link btn-sm text-small">Test </button>
      </div>
    </div>
  </div>
</div>
