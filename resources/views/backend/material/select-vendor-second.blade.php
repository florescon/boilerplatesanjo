<div class="row g-3 text-center">

  <div class="input-group">
    <div class="input-group-prepend col-12" wire:ignore>
      <select id="vendorsecondselect" name="vendor_second_id" id="vendor_second_id" class="custom-select" style="width: 100%;" aria-hidden="true">
      </select>
    </div>

    <div class="col-12 text-center mt-4">
      <a class="btn btn-danger text-white" wire:click="clearSecondVendor">
        @lang('Clear filter') - @lang('Vendor')
      </a>
    </div>

  </div>

</div>
