<div class="container-kanban" wire:poll.keep-alive.600s>
  @lang('Last Updated'): {{ now()->format('g:i a') }}

{{-- <div class="container">
    <div class="row my-3">
        <div class="col">
            <h4>sjuniformes.com</h4>
        </div>
    </div>

    <div class="row py-2">
        <div class="col-md-4 py-1">
            <div class="card">
                <div class="card-body">
                    <canvas id="chDonut1"></canvas>
                    <p class="text-center">Termoformados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-1">
            <div class="card">
                <div class="card-body">
                    <canvas id="chDonut2"></canvas>
                    <p class="text-center">Zona San José</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-1">
            <div class="card">
                <div class="card-body">
                    <canvas id="chDonut3"></canvas>
                    <p class="text-center">Zona Álvaro</p>
                </div>
            </div>
        </div>
    </div>
</div> --}}

  <div class="container-fluid page-header d-flex justify-content-between align-items-start">
    <div>
      <h1>@lang('Process')</h1>
      <p class="lead d-none d-md-block">@lang('Research, ideate and present brand concepts for client consideration')</p>
    </div>
    <div class="d-flex align-items-center">
      <button class="btn btn-round flex-shrink-0" data-toggle="tooltip" data-placement="top" title="Create register">
        <i class="cil-plus"></i>
      </button>
      <input type="text" class="form-control ml-3" placeholder="Inactive" aria-label="Recipient's username" aria-describedby="basic-addon2">
    </div>
  </div>

  <div class="kanban-board container-fluid mt-lg-3">

    <div class="kanban-col">
      <div class="card-list" style="background-color: #feebe5;">
        <div class="card-list-header">
          <h6>@lang('Quotations') 
            <span class="badge badge-danger">{{ $quotations->total() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $quotations->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($quotations as $quotation)
            <div class="card card-kanban">

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}">
                    {{ $quotation->folio ?? $quotation->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}"><h6>{{ Str::limit($quotation->customer, 22) }}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $quotation->id) }}"><h6>{{ Str::limit($quotation->comment, 23) }}</h6></a></p>
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
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more', 'pageQuotations')">@lang('Load more')</button>
              </div>
            </div>
          @endif
          
        </div>
        <div class="card-list-footer">
          <a href="{{ route('admin.order.quotation') }}" class="btn btn-link btn-sm text-small text-danger">@lang('Add quotation') + </a>
          <a href="{{ route('admin.order.quotations') }}" class="btn btn-link btn-sm text-small">@lang('Show all quotations')</a>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>@lang('To be defined')
            <span class="badge badge-danger">{{ $orders_to_be_defined->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_to_be_defined->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_to_be_defined as $order_captured)
            <div class="card card-kanban">

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}">
                    {{ $order_captured->folio ?? $order->captured->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}"><h6>{!! Str::limit($order_captured->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}"><h6>{{ Str::limit($order_captured->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order_captured->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order_captured->date }}</span>
                </div>
              </div>

            </div>
          @endforeach

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>@lang('Captured')
            <span class="badge badge-danger">{{ $orders_captured->total() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_captured->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_captured as $order_captured)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order_captured->percentage_status }}%; background-color: #f80f46;" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}">
                    {{ $order_captured->folio ?? $order_captured->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}"><h6>{!! Str::limit($order_captured->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order_captured->id) }}"><h6>{{ Str::limit($order_captured->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order_captured->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order_captured->date }}</span>
                </div>
              </div>
            </div>
          @endforeach

          @if($orders_captured->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more', 'pageCaptured')">@lang('Load more')</button>
              </div>
            </div>
          @endif

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>@lang('Request') - @lang('Provider')
            <span class="badge badge-danger">{{ $orders_production->total() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_production->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>
            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_production as $order_production)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order_production->percentage_status }}%; background-color: #f80f46;" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order_production->id) }}">
                    {{ $order_production->folio ?? $order_production->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order_production->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order_production->id) }}"><h6>{!! Str::limit($order_production->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order_production->id) }}"><h6>{{ Str::limit($order_production->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order_production->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order_production->date }}</span>
                </div>
              </div>
            </div>
          @endforeach

          @if($orders_production->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more', 'pageProduction')">@lang('Load more')</button>
              </div>
            </div>
          @endif

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>Corte
            <span class="badge badge-danger">{{ $orders_court->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_court->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_court as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #fdc31c;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">
                    {{ $order->folio ?? $order->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{{ Str::limit($order->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order->date }}</span>
                </div>
              </div>

            </div>
          @endforeach

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>Confección
            <span class="badge badge-danger">{{ $orders_making->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_making->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_making as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #6afb4c;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">
                    {{ $order->folio ?? $order->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{{ Str::limit($order->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order->date }}</span>
                </div>
              </div>

            </div>
          @endforeach

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>Revisión intermedia
            <span class="badge badge-danger">{{ $orders_revision->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_revision->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_revision as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #34ff61;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">
                    {{ $order->folio ?? $order->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{{ Str::limit($order->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order->date }}</span>
                </div>
              </div>

            </div>
          @endforeach

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>Personalización
            <span class="badge badge-danger">{{ $orders_personalization->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_personalization->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_personalization as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #05ff73;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">
                    {{ $order->folio ?? $order->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{{ Str::limit($order->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order->date }}</span>
                </div>
              </div>

            </div>
          @endforeach

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <div class="card-list-header">
          <h6>Revisión final
            <span class="badge badge-danger">{{ $orders_revision_final->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_revision_final->sum('sum') }}</span>
          </h6>
          <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="cil-list"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">@lang('Edit') <span class="badge badge-secondary">@lang('Inactive')</span></a>
              <a class="dropdown-item text-danger" href="#">@lang('Archive List') <span class="badge badge-secondary">@lang('Inactive')</span></a>            </div>
          </div>
        </div>
        <div class="card-list-body">

          @foreach($orders_revision_final as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #00ff85;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <a class="btn-options" type="button" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">
                    {{ $order->folio ?? $order->id }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">@lang('Edit')</a>
                    <a class="dropdown-item text-danger" href="#">@lang('Archive Card')</a>
                  </div>
                </div>
                <div class="card-title">
                  <a target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><a class="btn-options" target="_blank" href="{{ route('admin.order.edit', $order->id) }}"><h6>{{ Str::limit($order->comment, 23) }}</h6></a></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>@lang('Articles'): {{ $order->sum }}</span>
                  </div>
                  <span class="text-small">{{ $order->date }}</span>
                </div>
              </div>

            </div>
          @endforeach

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all')  <span class="badge badge-secondary">@lang('Inactive')</span></button>
        </div>
      </div>
    </div>

    <div class="kanban-col">
      <div class="card-list">
        <a href="{{ route('admin.order.quotation') }}" class="btn btn-link btn-sm text-small">@lang('Add quotation')</a>
      </div>
    </div>
  </div>
</div>


@push('after-scripts')

<script type="text/javascript">
  
/* chart.js chart examples */

// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];



/* bar chart */
var chBar = document.getElementById("chBar");
if (chBar) {
  new Chart(chBar, {
  type: 'bar',
  data: {
    labels: ["S", "M", "T", "W", "T", "F", "S"],
    datasets: [{
      data: [589, 445, 483, 503, 689, 692, 634],
      backgroundColor: colors[0]
    },
    {
      data: [639, 465, 493, 478, 589, 632, 674],
      backgroundColor: colors[1]
    }]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        barPercentage: 0.4,
        categoryPercentage: 0.5
      }]
    }
  }
  });
}

/* 3 donut charts */
var donutOptions = {
  cutoutPercentage: 85, 
  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
};

// donut 1
var chDonutData1 = {
    labels: ['Corte', 'Confeccion', 'Personalizacion'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [74, 11, 40]
      }
    ]
};

var chDonut1 = document.getElementById("chDonut1");
if (chDonut1) {
  new Chart(chDonut1, {
      type: 'pie',
      data: chDonutData1,
      options: donutOptions
  });
}

// donut 2
var chDonutData2 = {
    labels: ['Corte', 'Confeccion', 'Personalizacion'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [40, 45, 30]
      }
    ]
};
var chDonut2 = document.getElementById("chDonut2");
if (chDonut2) {
  new Chart(chDonut2, {
      type: 'pie',
      data: chDonutData2,
      options: donutOptions
  });
}

// donut 3
var chDonutData3 = {
    labels: ['Corte', 'Confeccion', 'Personalizacion', 'Confeccion', 'Personalizacion'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [21, 45, 55, 33, 44, 100]
      }
    ]
};
var chDonut3 = document.getElementById("chDonut3");
if (chDonut3) {
  new Chart(chDonut3, {
      type: 'pie',
      data: chDonutData3,
      options: donutOptions
  });
}

/* 3 line charts */
var lineOptions = {
    legend:{display:false},
    tooltips:{interest:false,bodyFontSize:11,titleFontSize:11},
    scales:{
        xAxes:[
            {
                ticks:{
                    display:false
                },
                gridLines: {
                    display:false,
                    drawBorder:false
                }
            }
        ],
        yAxes:[{display:false}]
    },
    layout: {
        padding: {
            left: 6,
            right: 6,
            top: 4,
            bottom: 6
        }
    }
};

var chLine1 = document.getElementById("chLine1");
if (chLine1) {
  new Chart(chLine1, {
      type: 'line',
      data: {
          labels: ['Jan','Feb','Mar','Apr','May'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [10, 11, 4, 11, 4],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
var chLine2 = document.getElementById("chLine2");
if (chLine2) {
  new Chart(chLine2, {
      type: 'line',
      data: {
          labels: ['A','B','C','D','E'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [4, 5, 7, 13, 12],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

var chLine3 = document.getElementById("chLine3");
if (chLine3) {
  new Chart(chLine3, {
      type: 'line',
      data: {
          labels: ['Pos','Neg','Nue','Other','Unknown'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [13, 15, 10, 9, 14],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

</script>
@endpush