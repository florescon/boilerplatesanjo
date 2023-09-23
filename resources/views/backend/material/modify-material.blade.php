<div class="custom-control custom-switch custom-control-inline fw-bolder font-weight-bold shadow border-0 pt-3 pr-3">

    <x-utils.link
        icon="c-icon cil-plus"
        class="card-header-action"
        data-toggle="modal" 
        style="color: green;"
        wire:click="$emit('postMassive')" 
        data-target="#massiveFeedstocks"
        :text="__('Massive changes')"
    />

    | 

    <strong class="text-primary">
        &nbsp; @lang('Edit stock')
    </strong>
    <div class="form-check">

        <label class="c-switch c-switch-primary">
          <input type="checkbox" class="c-switch-input" wire:click="$emit('postAdded')" {{ Request::get('editStock') ? 'checked' : '' }}>
          <span class="c-switch-slider"></span>
        </label>
    </div>

</div>
