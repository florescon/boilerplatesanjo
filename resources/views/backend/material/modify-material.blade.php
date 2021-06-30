<div class="custom-control custom-switch custom-control-inline">

    Editar stock

    <div class="form-check">
        <label class="c-switch c-switch-sm c-switch-3d c-switch-primary">
          <input wire:click="$emit('postAdded')" type="checkbox" class="c-switch-input" 
              {{ Request::get('editStock') ? 'checked' : '' }}
          >
          <span class="c-switch-slider"></span>
        </label>
    </div>

</div>
