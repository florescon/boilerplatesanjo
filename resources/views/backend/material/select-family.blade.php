<div class="row g-3 text-center">

  <div class="input-group">
    <div class="input-group-prepend col-9" wire:ignore>
      <select id="familyselect" name="family_id" id="family_id" class="custom-select" style="width: 100%;" aria-hidden="true">
      </select>
    </div>

    @if($family_id)
      <div class="input-group-append">
        <span class="input-group-text" id="basic-addon2" wire:click="clear" >x</span>
      </div>
    @endif
  </div>

</div>
