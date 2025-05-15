<main role="main">

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div class="container">

      <div class="row">
        <div class="col-sm">
          <h3 class="display-4">Orden #{{ $order->folio }}</h3>
          <h3 class="display-5">@lang('Customer'): {{   $order->user_name_clear }}</h3>
          <h3 class="display-5">{{ $order->comment }}</h3>
          <h3 class="display-5 text-primary">{{ ucfirst($status->name) }} Folio: #{{ $productionBatch->folio ?: $productionBatch->id }}</h3>
        </div>
        <div class="col-sm mt-3 text-center">

          <p>
            <a class="btn btn-outline-primary" href="javascript:void(0);" onclick="window.close();" role="button"> Cerrar &nbsp;&nbsp; <i class="cil-x-circle"></i></a>
          </p>
          <p class="p-4">
            Fecha creado: {{ $order->date_for_humans }}
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
                <label class="mt-2" for="user-select-{{ $productionBatch->id }}">Seleccionar Usuario</label>
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
            <label class="mt-2">Fecha Ingresada</label>
            <input
                type="date"
                wire:model.live="date_entered"
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

      @if($getStatusCollection['initial_lot'])
        <div class="col-10">
          <div class="row justify-content-center shadow p-4">
              <div class="col-4 align-self-center text-center">
                @if(!$productionBatch->consumption)
                  <button wire:click="makeConsumption({{ $productionBatch->id }})" class="list-group-item list-group-item-primary list-group-item-action">
                      Comsumir Materia Prima
                  </button>
                @else
                  <a href="{{ route('admin.order.checklist_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action"> Ver Consumido  <i class="fas fa-external-link-alt m-1"></i></a>
                @endif
              </div>
              <div class="col-4 align-self-center text-center">
                <a href="{{ route('admin.order.ticket_materia_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action"> Ver BOM  <i class="fas fa-external-link-alt m-1"></i></a>
              </div>
              <div class="col-4 align-self-center text-center">
                <a href="{{ route('admin.order.ticket_prod', [$order->id, $productionBatch->id]) }}" target="_blank" class="list-group-item list-group-item-action"> Ticket  <i class="fas fa-external-link-alt m-1"></i></a>
              </div>
          </div>
        </div>
      @endif

      <div class="col-md-12 text-center">

        <h2 class="p-4">
          <button wire:click="receiveAll" class="btn btn-primary">
              Recibir todo
          </button>
        </h2>
        <p>

        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Producto</th>
              <th scope="col">Entrada</th>
              <th scope="col">Salida</th>
              <th scope="col">Por recibir</th>
              <th scope="col">Cantidad a recibir</th>
              <th scope="col">Activo</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productionBatch->items as $key => $item)
                <tr>
                    <th>{{ $key + 1 }}</th>
                    <th scope="row">{{ $item->product->full_name_clear }}</th>
                    <td>{{ $item->input_quantity }}</td>
                    <td>{{ $item->output_quantity }}</td>
                    <td>{{ $this->getRemainingQuantity($item) }}</td>
                    <td>
                        <input 
                            type="number"
                            wire:model="receivedQuantities.{{ $item->id }}" 
                            min="0" 
                            max="{{ $this->getRemainingQuantity($item) }}"
                            class="form-control"
                        >
                    </td>
                    <td>{{ $item->active }}</td>
                </tr>
            @endforeach
          </tbody>
        </table>

        </p>
        <p>
          <button wire:click="receiveSelected" class="btn btn-secondary">
              Recibir lo capturado &raquo;
          </button>
        </p>
      </div>
    </div>

    <hr>

  </div> <!-- /container -->

</main>
