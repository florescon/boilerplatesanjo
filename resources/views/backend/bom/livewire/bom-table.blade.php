<div class="container-kanban">
  <div class="kanban-board container-fluid mt-lg-3">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="content-list-body">

	        @json($selectedtypes)


		    @if($selectedtypes && $orders->count() && ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.bom.list'))))
			    <div class="dropdown table-export mb-4">
			      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			        @lang('Export')        
			      </button>

			      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			        <a class="dropdown-item" wire:click="exportMaatwebsite('csv')">CSV</a>
			        <a class="dropdown-item" wire:click="exportMaatwebsite('xlsx')">Excel</a>
			        <a class="dropdown-item" wire:click="exportMaatwebsite('xls')">Excel ('XLS')</a>
			        <a class="dropdown-item" wire:click="exportMaatwebsite('html')">HTML</a>
			        <a class="dropdown-item" wire:click="exportMaatwebsite('tsv')">TSV</a>
			        <a class="dropdown-item" wire:click="exportMaatwebsite('ods')">ODS</a>
			      </div>

			    </div><!--export-dropdown-->
		    @endif

		    @if($materials)
	           @foreach($materials as $material)
	           	{!! $material['material_name'].' <strong>'.$material['quantity'].'</strong> ' !!}<br>
	           @endforeach
	        @endif

            @foreach($orders as $order)
              <form class="checklist">
                <div class="row">
                  <div class="form-group col">
                    <span class="checklist-reorder">
                    	<i class="cil-list"></i>
                    </span>
                    <div class="custom-control custom-checkbox col">
                      <input type="checkbox" class="custom-control-input" value="{{ $order->id }}" wire:model="selectedtypes" id="checklist-item-{{ $order->id }}">
                      <label class="custom-control-label" for="checklist-item-{{ $order->id }}"></label>
                      <div>
                      	<strong>#{{ $order->id }}</strong>
                        <input type="text" placeholder="{{ __('undefined customer') }}" value="{{ Str::limit($order->customer, 50) }}" data-filter-by="value" />
                        {{ $order->comment }}
                        <div class="checklist-strikethrough"></div>
                      </div>
                    </div>
                  </div>
                  <!--end of form group-->
                </div>
              </form>
            @endforeach

	        @if($orders->hasMorePages())
	            <div class="card text-center" style="background-color: rgba(245, 245, 245, 1); opacity: .8;">
	              <div class="card-body">
	                <button type="button" class="btn btn-primary" wire:click="$emit('load-more')">@lang('Load more')</button>
	              </div>
	            </div>
	        @endif

           </div>
      	</div>
      </div>
    </div>
  </div>
</div>