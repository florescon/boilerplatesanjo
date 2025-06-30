<!-- Modal Update -->
<div wire:ignore.self  class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title" id="updateModalLabel"><h5>@lang('Update by sizes') ⇉ <strong>{{ $name }}</h5> <h2 class="ml-4 text-danger">{{ $getQ }} {{ $getUnit }} </h2></strong></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">

          <div class="alert alert-primary" role="alert">
            Actualizar solamente las diferencias. El valor actual es: <strong class="text-danger">{{ $getQ }} {{ $getUnit }}</strong>
          </div>

          <div class="table-responsive">
              <table class="table table-bordered table-sm">
                  <thead>
                      <tr>
                          @foreach($model->children->unique('size_id')->sortBy('size.sort') as $size)
                          <th class="text-center"><strong>{{ $size->size->name }}</strong></th>
                          @endforeach
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          @foreach($model->children->unique('size_id')->sortBy('size.sort') as $size)
                          <td>
                              <input type="text"
                                     style="min-width:45px;" 
                                     step="0.01"
                                     wire:model.defer="consumptions.{{ $size->size_id }}.quantity"
                                     class="form-control text-center text-danger" 
                                     placeholder="{{ $size->size->name }}" 
                                     name="size_{{ $size->id }}">
                            @error('consumptions.'.$size->size_id.'.quantity')
                              <span class="error" style="color: red;">{{ $message }}</span>
                            @enderror
                          </td>
                          @endforeach
                      </tr>
                  </tbody>
              </table>
          </div>

          <div class="mt-3">
              <button wire:click="updateSecondary" class="btn btn-primary">
                  Guardar Consumos
              </button>
          </div>

        </div>
    </div>
  </div>
</div>


