<div class="row g-3 text-center">

  <div class="input-group">
    <div class="input-group-prepend col-12" wire:ignore>
      <select id="colorsecondselect" name="color_second_id" id="color_second_id" class="custom-select" style="width: 100%;" aria-hidden="true">
      </select>
    </div>

    <div class="col-12 text-center mt-4">
      <a class="btn btn-danger text-white" wire:click="clearSecondColor">
        @lang('Clear filter') - @lang('Color')
      </a>
    </div>

  </div>

</div>
