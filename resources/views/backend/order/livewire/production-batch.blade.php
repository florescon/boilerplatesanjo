<main role="main">

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div class="container">

      <div class="row">
        <div class="col-sm">
          <h3 class="display-4">Orden #{{ $order->folio }}</h3>
          <h3 class="display-5">@lang('Customer'): {{   $order->user_name_clear }}</h3>
          <h3 class="display-5">{{ $order->comment }}</h3>
          <h1 class=" text-primary">{{ ucfirst($status->name) }}</h1>
        </div>
        <div class="col-sm mt-3 text-center">

          <p>
            <a class="btn btn-primary" href="{{ route('admin.order.work', [$order->id, $status->id]) }}" role="button"> Ir a {{ ucfirst($status->name) }} </a>

              @if($next_status)
                <a href="{{ route('admin.order.work', [$order->id, $next_status->id]) }}" class="btn btn-outline-primary ml-3" data-toggle="tooltip" title="{{ $next_status->name ?? null }}">
                  <i class="cil-chevron-right"></i>
                  @if($next_status->finial_process) &nbsp; <i class="cil-running"></i> @endif
                </a>
              @endif

            {{-- <a class="btn btn-outline-primary ml-5" href="javascript:void(0);" onclick="window.close();" role="button"> Cerrar &nbsp;&nbsp; <i class="cil-x-circle"></i></a> --}}
          </p>
          <p class="p-4">
            <h1 class="custom-control-inline">Folio: #{{ $productionBatch->folio ?: $productionBatch->id }}</h1>

            <h4 class="custom-control-inline">Creado: {{ $productionBatch->date_for_humans }}</h4>
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="container">


    <!-- Example row of columns -->
    <div class="row justify-content-center">
      @if($getStatusCollection['to_add_users'])
      <div class="col-4 align-self-center shadow m-4">
        <div class="form-check mb-3 justify-content-center text-center">

            <div class="form-group mb-3">
                <label class="mt-2" for="user-select-{{ $productionBatch->id }}">Seleccionar Personal</label>
                <select id="user-select-{{ $productionBatch->id }}" wire:change="savePersonalId({{ $productionBatch->id }}, $event.target.value)" class="form-control border-primary" 
                  style=" {{ $productionBatch->personal_id ? 'color: red;' : '' }}" 
                  onfocus="disableKeyUpDown({{ $productionBatch->id }})">
                    <option value="" class="text-dark">Seleccionar</option>
                    @foreach($users as $user)
                        <option class="{{ $user->id !== $productionBatch->personal_id ? 'text-dark' : '' }}" value="{{ $user->id }}" {{ $user->id == $productionBatch->personal_id ? 'selected' : '' }}>
                          {!! $user->id == $productionBatch->personal_id  ? '>> &nbsp;&nbsp;&nbsp;' : ''!!} {{ ucwords(strtolower($user->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- <input wire:model="selectAll" type="checkbox" class="form-check-input" id="selectAll"> --}}
            {{-- <label class="form-check-label" for="selectAll">Seleccionar todo</label> --}}
        </div>
      </div>
      @endif
      <div class="col-4 align-self-center text-center shadow m-4">
        <div class="form-group mb-3">
            <label class="mt-2">Fecha</label>
            <input
                type="date"
                wire:model.live.debounce.1s="date_entered"
                id="date_entered"
                class="form-control text-center"
            >
        </div>


      </div>
      <div class="col-4 align-self-center text-center shadow m-4 p-2">
        <div class="form-group mb-3">
            <x-input.input-alpine nameData="isNote" maxlength="256" :inputText="$isNote" :originalInput="$isNote" wireSubmit="savenote" modelName="notes" :extraName="__('Comment')" />
        </div>
      </div>


      @if($getStatusCollection['not_restricted'])
        <div class="col-4 align-self-center text-center shadow m-4 p-2">

          <label for="service_type_id">Tipo de Servicio</label>
          <select 
              id="service_type_id" 
              wire:model="selectedServiceType"
              class="form-control"
          >
              <option value="">Seleccione un tipo de servicio</option>
              @foreach($inputOptions as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
              @endforeach
          </select>
          
        </div>
      @endif

        <div class="col-10">
          <div class="row justify-content-center shadow p-4">
              @if($getStatusCollection['initial_lot'])
                <div class="col-4 align-self-center text-center">
                  @if(!$productionBatch->consumption)
                    <button wire:click="makeConsumption({{ $productionBatch->id }})" class="list-group-item list-group-item list-group-item-action">
                        Consumir Materia Prima
                    </button>
                  @else
                    <a href="{{ route('admin.order.checklist_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action list-group-item-success"> Material Consumido  <i class="fas fa-external-link-alt m-1"></i></a>
                  @endif
                </div>
                <div class="col-4 align-self-center text-center">
                  <a href="{{ route('admin.order.ticket_materia_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action"> Ver BOM  <i class="fas fa-external-link-alt m-1"></i></a>
                </div>
              @endif
              <div class="col-4 align-self-center text-center">
                <a href="{{ route('admin.order.ticket_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action list-group-item-info"> Imprimir Ticket {{ ucfirst($status->name) }}  <i class="fas fa-external-link-alt m-1"></i></a>
              </div>

              @if($getStatusCollection['final_process'])
              <div class="col-4 align-self-center text-center">
                <livewire:backend.components.edit-field textDanger="true" :model="'\App\Models\ProductionBatch'" :entity="$productionBatch" :field="'invoice'" :key="'invoice'.$productionBatch->id" :text="__('Invoice')"/>
              </div>

              <div class="col-4 align-self-center text-center">
                <a wire:click="makeInvoiceDate({{ $productionBatch->id }})" class="text-danger">Fecha Factura <i class="cil-arrow-thick-right"></i> 
                  {{ $productionBatch->invoice_date_format }}
                </a>
              </div>

              <div class="col-4 align-self-center text-center p-4">
                <a href="{{ route('admin.order.output', [$productionBatch->id, true]) }}" target="_blank" class=""> <i class="cil-print"></i> @lang('Output') <i class="fas fa-external-link-alt m-1"></i></a>
              </div>              
              @endif

          </div>
        </div>

      <div class="col-md-12 text-center mt-3">

        <p>

        <table class="table table-hover table-sm">
          <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">@lang('Code')</th>
              <th scope="col">Producto</th>
              <th scope="col">Asignado</th>
              <th scope="col">Recibido</th>
              <th scope="col">Proceso</th>
              <th scope="col" style="width: 150px !important;">
              @if(!$showSentToStock)
                Recepción Parcial
              @else
                Enviar a Producto Terminado
              @endif
              </th>
              <th scope="col">Activo</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productionBatch->items->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $key => $item)
                <tr>
                    <th>{{ $key + 1 }}</th>
                    <th>{{ $item->product->parent_code }}</th>
                    <th scope="row">{!! $item->product->full_name_link !!}</th>
                    <td>{{ $item->input_quantity }}</td>
                    <td>{{ $item->output_quantity }}</td>
                    <td>{{ $this->getRemainingQuantity($item) }}</td>
                    <td>
                      @if(!$showSentToStock)
                        <input 
                            type="number"
                            wire:model.defer="receivedQuantities.{{ $item->id }}" 
                            min="0" 
                            max="{{ $this->getRemainingQuantity($item) }}"
                            class="form-control text-center quantity-input"
                        >
                      @else
                        <input 
                            type="number"
                            wire:model.defer="sendQuantities.{{ $item->id }}" 
                            min="0" 
                            max="{{ $this->getRemainingQuantity($item) }}"
                            class="form-control text-center quantity-input"
                        >
                      @endif
                    </td>
                    <td>{{ $item->active }}</td>
                </tr>
            @endforeach
            <tr class="table-dark h5">
              <th colspan="3">Totales</th>
              <td scope="row"><strong>{{ $productionBatch->total_products_prod }}</strong></td>
              <td scope="row"><strong>{{ $productionBatch->total_products_prod_output }}</strong></td>
              <td scope="row"><strong>{{ $productionBatch->total_products_prod_diferrence }}</strong></td>
              <td scope="row"><h3 class="d-inline"><span id="total-sum" class="text-danger">0</span></h3></td>
              <td scope="row"><strong>{{ $productionBatch->total_products_prod_active }}</strong></td>
            </tr>
          </tbody>
        </table>

        </p>
  <div class="row">
    <div class="col-2">
      @if(!$getStatusCollection['is_process'])
        @if($getStatusCollection['to_add_users'])
          <button wire:click="makeDelete" class="btn btn-danger" @if(!$productionBatch->receiveSomething()) disabled @endif>
            Eliminar
          <span wire:loading wire:target="makeDelete">...</span>
          </button>
        @endif
      @endif
    </div>
    <div class="col-6">
        @if($getStatusCollection['initial_process'])
            <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
              <em class="mt-2 text-danger h3"> @lang('Send to PT')</em>
                <div class="col-md-2 mt-2">
                  <div class="form-check">
                    <label class="c-switch c-switch-label c-switch-danger m-2" style="transform: scale(1.8);">
                      <input type="checkbox" wire:model="showSentToStock" class="c-switch-input">
                      <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
                    </label>
                  </div>
                </div>
            </div>
        @endif
    </div>
    <div class="col">
      <h2 class=" text-center">
        @if(!$showSentToStock)
        <button wire:click="makeReceiveAll({{ $productionBatch->id }})" class="btn btn-primary" style="{{ $productionBatch->allItemsAreBalanced() ? 'background-color: purple' : '' }}" @if($productionBatch->allItemsAreBalanced()) disabled @endif>
          @if($getStatusCollection['final_process'] ?? false)
            {{ $productionBatch->allItemsAreBalanced() ? 'Salida Efectuada' : 'Salida Todo' }}
          @else
            {{ $productionBatch->allItemsAreBalanced() ? 'Recibido' : 'Recibir todo' }}
          @endif
        </button>
        @endif
      </h2>
    </div>

    <div class="col">
      @if(!$showSentToStock)
        <button 
            wire:click="receiveSelected"
            wire:loading.attr="disabled"
            @if($buttonDisabled) disabled @endif
            onclick="setTimeout(() => { this.disabled = false }, 3000)"
            class="btn btn-outline-primary"
            @if($productionBatch->allItemsAreBalanced()) disabled @endif
            style="{{  $productionBatch->allItemsAreBalanced() ? 'background-color: purple; color: white;' : '' }}" 
            >

            @if($getStatusCollection['final_process'] ?? false)
              {{ $productionBatch->allItemsAreBalanced() ? 'Se ha dado salida a todo' : 'Dar Salida' }}
            @else
              {{ $productionBatch->allItemsAreBalanced() ? 'Ya se ha recibido todo' : 'Recibir lo capturado' }}
            @endif

            &raquo;
        </button>
      @else
        <button 
            wire:click="sendToStock"
            wire:loading.attr="disabled"
            @if($buttonDisabled) disabled @endif
            onclick="setTimeout(() => { this.disabled = false }, 3000)"
            class="btn btn-danger">
            Enviar &raquo;
        </button>
      @endif
    </div>
  </div>

      </div>
    </div>

    <hr>

  </div> <!-- /container -->

</main>
