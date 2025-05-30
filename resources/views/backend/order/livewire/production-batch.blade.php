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

            <h4 class="custom-control-inline">Creado: {{ $order->date_for_humans }}</h4>
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
                wire:model.live.debounce.2.5s="date_entered"
                id="date_entered"
                class="form-control text-center"
            >
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>


      </div>
      <div class="col-4 align-self-center text-center shadow m-4 p-2">
        <div class="form-group mb-3">
            <x-input.input-alpine nameData="isNote" maxlength="256" :inputText="$isNote" :originalInput="$isNote" wireSubmit="savenote" modelName="notes" :extraName="__('Comment')" />
        </div>
      </div>

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
                <a href="{{ route('admin.order.ticket_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action"> Imprimir Ticket {{ ucfirst($status->name) }}  <i class="fas fa-external-link-alt m-1"></i></a>
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

              {{-- <div class="col-4 align-self-center text-center p-4">
                <a href="{{ route('admin.order.output', $productionBatch->id) }}" target="_blank" class=""> <i class="cil-print"></i> @lang('Output') <i class="fas fa-external-link-alt m-1"></i></a>
              </div>
              <div class="col-4 align-self-center text-center p-4">
                <a href="{{ route('admin.order.output', [$productionBatch->id, true]) }}" target="_blank" class=""> <i class="cil-print"></i> @lang('Output') @lang('Grouped') <i class="fas fa-external-link-alt m-1"></i></a>
              </div> --}}              
              @endif

              @if($getStatusCollection['initial_process'])
                <div class="col-4 align-self-center text-center">
                  <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
                    <em class=" mt-2"> @lang('Capture to send a finished product')</em>
                      <div class="col-md-2 mt-2">
                        <div class="form-check">
                          <label class="c-switch c-switch-label c-switch-primary">
                            <input type="checkbox" wire:model="showSentToStock" class="c-switch-input">
                            <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
                          </label>
                        </div>
                      </div>
                  </div>
                </div>              
              @endif
          </div>
        </div>

      <div class="col-md-12 text-center">

        <h2 class="p-4 text-center">
          <button wire:click="makeReceiveAll({{ $productionBatch->id }})" class="btn btn-primary">
              Recibir todo
          </button>
        </h2>
        <p>

        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Producto</th>
              <th scope="col">Asignado</th>
              <th scope="col">Recibido</th>
              <th scope="col">Proceso</th>
              <th scope="col">
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
            @foreach($productionBatch->items as $key => $item)
                <tr>
                    <th>{{ $key + 1 }}</th>
                    <th scope="row">{!! $item->product->full_name_break !!}</th>
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
                            class="form-control text-center"
                        >
                      @else
                        <input 
                            type="number"
                            wire:model.defer="sendQuantities.{{ $item->id }}" 
                            min="0" 
                            max="{{ $this->getRemainingQuantity($item) }}"
                            class="form-control text-center"
                        >
                      @endif
                    </td>
                    <td>{{ $item->active }}</td>
                </tr>
            @endforeach
          </tbody>
        </table>

        </p>
        <div class="text-right">

          @if(!$showSentToStock)
            <button 
                wire:click="receiveSelected"
                wire:loading.attr="disabled"
                @if($buttonDisabled) disabled @endif
                onclick="setTimeout(() => { this.disabled = false }, 3000)"
                class="btn btn-primary">
                Recibir lo capturado &raquo;
            </button>
          @else
            <button 
                wire:click="sendToStock"
                wire:loading.attr="disabled"
                @if($buttonDisabled) disabled @endif
                onclick="setTimeout(() => { this.disabled = false }, 3000)"
                class="btn btn-primary">
                Enviar &raquo;
            </button>
          @endif
        </div>
      </div>
    </div>

    <hr>

  </div> <!-- /container -->

</main>
