<div class="row g-3 text-center">

  <div class="input-group">
    <div class="input-group-prepend col-12" wire:ignore>
      <select id="familysecondselect" name="family_second_id" id="family_second_id" class="custom-select" style="width: 100%;" aria-hidden="true">
      </select>
    </div>

    <div class="col-12 text-center mt-4">
      <a class="btn btn-danger text-white" wire:click="clearSecondFamily">
        @lang('Clear filter') - @lang('Family')
      </a>
    </div>

  </div>

</div>
