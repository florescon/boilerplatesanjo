<div class="custom-control custom-switch custom-control-inline fw-bolder font-weight-bold text-primary border-0 pt-3 pr-3">

    @lang('Mass assignment') - @lang('Family')

    <div class="form-check">
        <label class="c-switch c-switch-primary">
          <input type="checkbox" class="c-switch-input" wire:model="mass" wire:click="$emit('postMass')" {{ Request::get('massAssginment') ? 'checked' : '' }}>
          <span class="c-switch-slider"></span>
        </label>
    </div>

    <div class="col-md-5" wire:ignore>
        <select id="familyselect" name="family_id" id="family_id" class="custom-select" style="width: 100%;" aria-hidden="true">
        </select>
    </div>

    @if($family_id && $mass)
        <button type="button" class="btn btn-link" wire:click="$emit('saveMassVendor', {{ $family_id }})">
            @lang('Save')
        </button>
    @endif

</div>
