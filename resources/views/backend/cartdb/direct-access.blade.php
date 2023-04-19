<div class="layout-switcher" tabindex="1">
  <div class="layout-switcher-head d-flex justify-content-between">
 		<span>@lang('Direct Access') &nbsp;</span>
		<i class="cil-chevron-top"></i>
  </div>
  <div class="layout-switcher-body">

      <div class="layout-switcher-option">
        <img alt="Navigation Side" data-toggle="modal" wire:click="createcustomer()" data-target="#createCustomer" src="{{ asset('/img/ga/create-user.png')}}" class="layout-switcher-icon" />
      </div>

      <div class="layout-switcher-option ">
        <img alt="Navigation Side" src="{{ asset('/img/ga/discount.webp')}}" class="layout-switcher-icon" />
      </div>
</div>

</div>