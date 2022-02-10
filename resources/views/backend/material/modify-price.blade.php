<div class="custom-control custom-switch custom-control-inline fw-bolder font-weight-bold text-primary shadow border-0 pt-3 pr-3">

    @lang('Edit price')

    <div class="form-check">
        <label class="c-switch c-switch-primary">
          <input type="checkbox" class="c-switch-input" wire:click="$emit('postPrice')" {{ Request::get('editPrice') ? 'checked' : '' }}>
          <span class="c-switch-slider"></span>
        </label>
    </div>

</div>
