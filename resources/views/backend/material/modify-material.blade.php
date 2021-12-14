<div class="custom-control custom-switch custom-control-inline">

    @lang('Edit stock')

    <div class="form-check">
        <label class="c-switch c-switch-primary">
          <input type="checkbox" class="c-switch-input" wire:click="$emit('postAdded')" {{ Request::get('editStock') ? 'checked' : '' }}>
          <span class="c-switch-slider"></span>
        </label>

    </div>

</div>
