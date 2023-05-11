<div class="row g-3 text-center">

  <div class="col-10" wire:ignore>
    <select id="colorselect" name="color_id" id="color_id" class="custom-select" style="width: 100%;" aria-hidden="true">
    </select>
  </div>
  @if($color_id)
    <div class="col-auto">
        <button type="button" class="btn btn-outline-primary" wire:click="clear" class="btn btn-default">x</button>
    </div>
  @endif
</div>
