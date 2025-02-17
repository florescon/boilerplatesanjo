<div class="layout-switcher" tabindex="1">
  <div class="layout-switcher-head d-flex justify-content-between">
 		<span>
        @lang('Direct Access') &nbsp; 
        {{-- <div class="d-inline">
          <input type="text" class="text-center" name="sum" id="sum" readonly style="width: 50px; background: transparent; border: none;" />
        </div> --}}
      </span>
		<i class="cil-chevron-top"></i>
  </div>
  <div class="layout-switcher-body">

      <div class="layout-switcher-option">
        <img alt="Navigation Side" data-toggle="modal" wire:click="createcustomer()" data-target="#createCustomer" src="{{ asset('/img/ga/create-user.png')}}" class="layout-switcher-icon" />
      </div>

      {{-- <div class="layout-switcher-option ">
        <img alt="Navigation Side" src="{{ asset('/img/ga/discount.webp')}}" class="layout-switcher-icon" />
      </div> --}}
  </div>
</div>

@push('after-scripts')
  <script>
  $(function() {
      $(".num-input").on("input", function() {
          let sum = 0;
          $(".num-input").each(function() {
              sum += Number($(this).val()) || 0;  // Si el valor no es un número, se considera 0
          });
          $("#sum").val(sum);
      });
  });
  </script>
@endpush