<x-backend.card>
    <x-slot name="header">
        @lang('Where is products?') @lang('Order') #{{ $order_id }}
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.order.edit', $order_id)" :text="__('Go to edit order')" />

        <x-utils.link class="card-header-action" :href="route('admin.order.index')" icon="fa fa-chevron-left" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">
        <div class="row ">
            <div class="col-12 col-md-4">
                <br>
                <div class="card-section border rounded p-3 text-center">
                    <div class="card-header-first card-header-first rounded pb-5">
                        <h2 class="card-header-title text-white pt-3">@lang('Order') #{{ $order_id }}</h2>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text">
                            <ins><em>
                              @lang('Where is products?')
                            </em></ins> 
                            <i class="fa fa-question" aria-hidden="true"></i>
                        </p>
                    </div>
                </div>

            </div>
            <div class="col-12 col-sm-6 col-md-8">

              <div class="row">
                <div class="col-md-12 col-sm-6">
                  @forelse($model->tickets as $ticket)

                  <div class="card card-assignment card-block">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-9 col-sm-3">

                          <h4 class="card-title font-weight-bold">{{ $ticket->status->name }}</h4>
                          <!-- Text -->
                          <p class="card-text"> 
                            {!! optional($ticket->user)->name ?? '
                            <span class="badge badge-success">Stock  '. appName().'</span>
                            ' !!}
                            -
                            Ticket: #{{ $ticket->id}}
                          </p>
                        </div>
                        <div class="col-md-3 col-sm-6 text-right">
                          <a href="{{ url('/') }}">
                            <i class="cil-x-circle"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body ">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Producto</th>
                              <th>Asignado</th>
                              <th>Salida</th>
                            </tr>
                          </thead>
                          <tbody>

                            {{-- @json($model2) --}}

                            {{-- @json($ticket) --}}

                            @foreach($ticket->assignments_direct as $assign)

                            <tr>
                              <td class="text-left">
                                {{ $assign->id }}
                              </td>

                              <td class="text-left">
                                {!! $assign->assignmentable->product->full_name  !!}

                                {{-- {{ $assign->assignmentable->product->parent->name }} --}}
                              </td>

                              <td> 
                                {{ $assign->quantity }}
                              </td>

                              <td> 
                                @if($assign->isOutput())
                                  <span class='badge badge-success'><i class='cil-check'></i></span>
                                @else
                                  <span class='badge badge-danger' wire:click="outputUpdate({{ $assign->id }})">@lang('To give out')</span>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                            <tr>
                              <td colspan="2" class="text-right">Total:</td>
                              <td>{{ $ticket->total_products_assignment_ticket }}</td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="card-footer bg-transparent ">
                        <div class="row">
                          <div class="col-6 col-md-6 text-left">
                            {{ $ticket->date_diff_for_humans }}
                          </div>

                          <div class="col-6 col-md-3 text-right">
                            <a href="{{ route('admin.order.assignments', [$model->id, $ticket->status->id]) }}" class="card-link text-right">Ir a {{ $ticket->status->name }} <i class="cil-chevron-double-right"></i></a>
                          </div>

                          @if($ticket->assignments_direct->where('output', false)->count())
                            <div class="col-6 col-md-3 text-right">
                              <a wire:click="outputUpdateAll({{ $ticket->id }})" class="card-link text-right">Dar salida al ticket</a>
                            </div>
                          @endif
                        </div>
                      </div>

                    </div>
                  </div>
                  @empty
                      <p>No hay nada asignado o ya se dio salida</p>
                  @endforelse

                </div>
              </div>


            </div>
        </div>
    </x-slot>
</x-backend.card>