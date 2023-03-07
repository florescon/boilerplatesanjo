<div class="container-kanban">
  <div class="container-fluid page-header d-flex justify-content-between align-items-start">
      <div>
        <h1>@lang('Kanban Board')</h1>
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
            <span class="badge badge-danger">{{ $quotations->count() }}</span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-13" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $quotation->id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{{ Str::limit($quotation->customer, 25) }}</h6></a>
                  <p><h6>{{ Str::limit($quotation->comment, 23) }}</h6></p>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order_captured->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order_captured->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order_captured->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order_captured->sum }}
                      <br>
                    </span>
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
            <span class="badge badge-danger">{{ $orders_captured->count() }}</span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order_captured->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order_captured->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order_captured->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order_captured->sum }}
                      <br>
                      {{ $order_captured->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
                  </div>

                  <span class="text-small">{{ $order_captured->date }}</span>

                </div>

              </div>
            </div>
          @endforeach

          @if($orders_captured->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
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
          <h6>@lang('Production')
            <span class="badge badge-danger">{{ $orders_production->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_production->sum('sum') }}</span>
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

          @foreach($orders_production as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #fb5b35;"  aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                {{-- <ul class="avatars">

                  <li>
                    <a href="#" data-toggle="tooltip" title="Peggy">
                      <img alt="Peggy Brown" class="avatar" src="{{ asset('/img/avatar/avatar-female-2.jpg')}}" />
                    </a>
                  </li>

                  <li>
                    <a href="#" data-toggle="tooltip" title="Ravi">
                      <img alt="Ravi Singh" class="avatar" src="{{ asset('/img/avatar/avatar-male-3.jpg')}}" />
                    </a>
                  </li>

                  <li>
                    <a href="#" data-toggle="tooltip" title="Marcus">
                      <img alt="Marcus Simmons" class="avatar" src="{{ asset('/img/avatar/avatar-male-1.jpg')}}" />
                    </a>
                  </li>

                  <li>
                    <a href="#" data-toggle="tooltip" title="Harry">
                      <img alt="Harry Xai" class="avatar" src="{{ asset('/img/avatar/avatar-male-2.jpg')}}" />
                    </a>
                  </li>

                </ul> --}}

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
          <h6>Sublimación
            <span class="badge badge-danger">{{ $orders_sublimation->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_sublimation->sum('sum') }}</span>
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

          @foreach($orders_sublimation as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: #bef12c;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
                  <button class="btn-options" type="button" id="kanban-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order_captured->last_status_order_id }} --}}
                    </span>
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
          <h6>@lang('Done')
            <span class="badge badge-danger">{{ $orders_finalized->count() }}</span>
            <span class="badge badge-info">@lang('Articles'): {{ $orders_finalized->sum('sum') }}</span>
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

          @foreach($orders_finalized as $order)
            <div class="card card-kanban">

              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $order->percentage_status }}%; background-color: blue;" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
              </div>

              <div class="card-body">
                <div class="dropdown card-options">
                  <button class="btn-options" type="button" id="kanban-dropdown-button-9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $order->order_id }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item text-danger" href="#">Archive Card</a>
                  </div>
                </div>
                <div class="card-title">
                  <a href="#" data-toggle="modal" data-target="#task-modal"><h6>{!! Str::limit($order->customer, 25) ?? '<em>'.__('undefined customer').'</em>' !!}</h6></a>
                  <p><h6>{{ Str::limit($order->comment, 23) }}</h6></p>
                </div>

                <div class="card-meta d-flex justify-content-between">
                  <div class="d-flex align-items-center">
                    <span>
                      @lang('Articles'): {{ $order->sum }}
                      <br>
                      {{ $order->name_status }} 
                      {{-- {{ $order->last_status_order_id }} --}}
                    </span>
                  </div>

                  <span class="text-small">{{ $order->date }}</span>

                </div>

              </div>
            </div>
          @endforeach

          @if($orders_finalized->hasMorePages())
            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .9;">
              <div class="card-body">
                <button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
              </div>
            </div>
          @endif

        </div>
        <div class="card-list-footer">
          <button class="btn btn-link btn-sm text-small">@lang('Show all done')</button>
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