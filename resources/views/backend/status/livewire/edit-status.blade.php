<div class="card">
    <div class="card-header">
        <h5>Rutas del proceso</h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Estación</th>
                    <th width="120">Secuencia</th>
                    <th width="80"></th>
                </tr>
            </thead>

            <tbody>

                @foreach($routes as $index => $route)
                    <tr>
                        <td>
                            <select
                                class="form-control"
                                wire:model="routes.{{ $index }}.operation_id">

                                <option value="">Seleccione...</option>

                                @foreach($operations as $operation)
                                    <option value="{{ $operation->id }}">
                                        {{ $operation->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('routes.'.$index.'.operation_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>

                        <td>
                            <input
                                type="number"
                                min="1"
                                class="form-control"
                                wire:model="routes.{{ $index }}.sequence">

                            @error('routes.'.$index.'.sequence')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                wire:click="removeRoute({{ $index }})">
                                X
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
<div class="d-flex">
    <button
        type="button"
        class="btn btn-secondary"
        wire:click="addRoute">
        Agregar estación
    </button>

    <button
        type="button"
        class="btn btn-primary ml-auto"
        wire:click="save">
        Guardar
    </button>
</div>

    </div>
</div>