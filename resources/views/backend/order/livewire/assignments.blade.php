<x-backend.card>
    <x-slot name="header">
        @lang('Show assignament') - {{ $status_name }} - @lang('Order') #{{ $order_id }}
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.order.edit', $order_id)" :text="__('Go to edit order')" />

        <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Cancel')" />
    </x-slot>
    <x-slot name="body">

        <div class="row ">
            <div class="col-16 col-md-6">
                <div class="card card-edit card-product_not_hover card-flyer-without-hover">
                  <div class="card-body">
            
                  <h4 class="card-title font-weight-bold mb-2">{{ $status_name }}</h4>

                    <livewire:backend.user.only-admins/>
                    <div class="row justify-content-end">
                      <div class="col-9">
                        @error('user') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                      </div>
                    </div>

                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Cantidad orden</th>
                              <th  style="background-color:#5DADE2;" class="text-white">Disponible</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($model2->product_order as $product)
                              <tr>
                                <td class="text-left">{!! $product->product->full_name !!}</td>
                                <td>{{ $product->quantity }}</td>

                                <td class="table-info"> 
                                    <input type="number" 
                                        wire:model.lazy="quantityy.{{ $product->id }}.available"
                                        wire:keydown.enter="save" 
                                        class="form-control"
                                        style="color: blue;" 
                                        placeholder="{{ $product->available_assignments }}" 
                                    >
                                    @error('quantityy.'.$product->id.'.available') 
                                      <span class="error" style="color: red;">
                                        <p>@lang('Check the quantity')</p>
                                      </span> 
                                    @enderror
                                </td>
                              </tr>
                            @endforeach
                              <tr>
                                <td class="text-right">Total:</td>
                                <td>{{ $model2->total_products }}</td>
                                <td>{{ $model2->total_products_assignments }}</td>
                              </tr>
                              @if($quantityy)
                                <tr>
                                  <td colspan="2"></td>
                                  <td>
                                    <button type="button" wire:click="save" class="btn btn-primary btn-sm">@lang('Create ticket')</button>
                                  </td>
                                </tr>
                              @endif
                          </tbody>
                        </table>
                      </div>

                  </div>
                </div>
            </div>

            <div class="col-12 col-md-6">


              <p>
                <div class="btn-group" role="group" aria-label="Basic example">
                  @if($previous_status)
                    <a href="{{ route('admin.order.assignments', [$model2->id, $previous_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $previous_status->name ?? null }}"><i class="c-icon  c-icon-4x cil-people"></i> @lang('Previous status')</a>
                  @endif
                  @if($next_status)
                    <a href="{{ route('admin.order.assignments', [$model2->id, $next_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $next_status->name ?? null }}"><i class="c-icon  c-icon-4x cil-people"></i> @lang('Next status')</a>
                  @endif
                </div>
              </p>
              <br>

              <div class="row">
                <div class="col-md-12 col-sm-6">
                  @foreach($model2->tickets as $ticket)

                  <div class="card card-assignment card-block">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-6 col-sm-3">
                          {!! optional($ticket->user)->name ?? '
                          <span class="badge badge-success">Stock'. appName().'</span>
                          ' !!}
                          #{{ $ticket->id.' - '.$ticket->status->name }}
                        </div>

                        <div class="col-md-4 col-sm-3">
                            <a href="{{ route('admin.order.ticket_assignment', [$order_id, $ticket->id]) }}" class="card-link" target="_blank"><i class="cil-print"></i> Ticket </a>
                        </div>
                        <div class="col-md-2 col-sm-6 text-right">
                          {{-- <a href="{{ url('/') }}">
                            <i class="cil-x-circle"></i>
                          </a> --}}

                        <x-utils.delete-button :text="__('')" :href="route('admin.ticket.destroy', $ticket->id)" />

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

                            @foreach($ticket->assignments_direct as $assign)
                            <tr>
                              <td class="text-left">
                                {{ $assign->id }}
                              </td>

                              <td class="text-left">
                                {!! $assign->assignmentable->product->full_name !!}
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
                          @if($ticket->assignments_direct->where('output', false)->count())
                            <div class="col-6 col-md-6 text-right">
                              <a wire:click="outputUpdateAll({{ $ticket->id }})" class="card-link text-right">Se recibieron los productos</a>
                            </div>
                          @endif
                        </div>
                      </div>

                    </div>
                  </div>
                  @endforeach

                </div>
              </div>

            </div>
        </div>
    </x-slot>
</x-backend.card>


@push('after-scripts')
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
</script>
@endpush