<div class="container">
<br>  <h2 class="text-center">@lang('Reports')</h2>
<hr>

<div class="row">
    <aside class="col-sm-12">
        <div class="card shadow-sm">
            <article class="card-group-item">
                <div class="filter-content">
                    <div class="card-body">

		                <div class="page-header-subtitle mt-4 mb-2 no-print">
							@if($details)
								<div class="alert alert-primary text-center" role="alert">
								  Seleccione primero el rango de fecha, antes del click al hipervínculo
								</div>
							@endif
		                    <div class="row">
		                    <div class="col-3">
		                        <div class="text-right pt-2">
		                        	<strong><em>@lang('Date Range'):</em></strong>
		                          {{-- <a type="button" class="btn btn-secondary" href="{{ route('admin.order.printexportbydate', [$byYear, $byMonth]) }}" target="_blank">@lang('Reporte Órdenes') </a> --}}
		                        </div>
		                    </div>
		                    <div class="col-3">
				                <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
		                    </div>
		                    <div class="col-3">
			 	                <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
		                    </div>
		               		</div>
		                </div>
                    </div> <!-- card-body.// -->
                </div>
            </article> <!-- card-group-item.// -->

        </div>
    </aside>

    <aside class="col-sm-4">
        <p>Filtro 1 </p>


        <div class="card">
            <article class="card-group-item">
                <header class="card-header">
                    <h6 class="title">@lang('List Orders')</h6>
                </header>
                <div class="filter-content">
                    <a href="#!" wire:click="printExportOrdersForDate" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Orders') <span class="float-right badge badge-success round">@lang('EXCEL')</span></a>
                    @if($details)
                        <em class="text-center p-2 text-muted"> Descarga las <strong>órdenes</strong> en el rango especificado. En excel.</em>
                    @endif
                </div>
            </article> <!-- card-group-item.// -->


            <article class="card-group-item">
                <header class="card-header">
                    <h6 class="title">@lang('Orders')</h6>
                </header>
                <div class="filter-content">
                    <a href="#!" wire:click="exportOrderProductsMaatwebsite('xlsx', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products') <span class="float-right badge badge-success round">@lang('EXCEL')</span></a>
                    @if($details)
                        <em class="text-center p-2 text-muted"> Descarga los <strong>productos</strong> de las órdenes en el rango especificado. En excel.</em>
                    @endif
                    <a href="#!" wire:click="exportOrderProductsMaatwebsite('xlsx', '0', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Services') <span class="float-right badge badge-success round">@lang('EXCEL')</span></a>
                    @if($details)
                        <em class="text-center p-2 text-muted"> Descarga los <strong>servicios</strong> de las órdenes en el rango especificado. En excel.</em>
                    @endif

                    <a href="#!" wire:click="exportOrderProductsGroupedMaatwebsite('xlsx', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products'), agrupado <span class="float-right badge badge-success round">@lang('EXCEL')</span></a>
                    @if($details)
                        <em class="text-center p-2 text-muted"> Descarga los <strong>productos</strong> de las órdenes en el rango especificado. En excel, agrupado por pedido, producto y color.</em>
                    @endif
                    <a href="#!" wire:click="exportOrderProductsGroupedMaatwebsite('xlsx', '0', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Services'), agrupado <span class="float-right badge badge-success round">@lang('EXCEL')</span></a>
                    @if($details)
                        <em class="text-center p-2 text-muted"> Descarga los <strong>servicios</strong> de las órdenes en el rango especificado. En excel, agrupado por pedido, producto y color.</em>
                    @endif

                </div>
            </article> <!-- card-group-item.// -->
            <article class="card-group-item">
                <header class="card-header">
                    <h6 class="title">@lang('Outputs')* <br><em> desde Jul, 2024</em> </h6>
                </header>
                <div class="filter-content">
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.information.status.printexporthistory', [15, true, $dateInput, $dateOutput]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">Exportar histórico <span class="float-right badge badge-danger round">@lang('PDF')</span> </a>
                        @if($details)
                            <em class="text-center p-2 text-muted"> Obtiene los <strong>productos</strong> (asignados) de las salidas en el rango especificado. En PDF.</em>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.information.status.printexporthistory', [15, 0, $dateInput, $dateOutput]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">Exportar histórico, agrupado <span class="float-right badge badge-info round ml-1">@lang('SUMMARY')</span> <span class="float-right badge badge-danger round">@lang('PDF')</span> </a>
                        @if($details)
                            <em class="text-center p-2 text-muted"> Obtiene los <strong>productos</strong> (asignados) de las salidas en el rango especificado. En PDF.</em>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.information.status.printexportreceived', [15, true, $dateInput, $dateOutput]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">Exportar recibido <span class="float-right badge badge-danger round">@lang('PDF')</span> </a>
                        @if($details)
                            <em class="text-center p-2 text-muted"> Obtiene los <strong>productos</strong> (recibidos) de las salidas en el rango especificado. En PDF.</em>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.information.status.printexportreceived', [15, 0, $dateInput, $dateOutput]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">Exportar recibido, agrupado <span class="float-right badge badge-info round ml-1">@lang('SUMMARY')</span> <span class="float-right badge badge-danger round">@lang('PDF')</span> </a>
                        @if($details)
                            <em class="text-center p-2 text-muted"> Obtiene los <strong>productos</strong> (recibidos) de las salidas en el rango especificado. En PDF.</em>
                        @endif
                    </div>
                </div>
            </article> <!-- card-group-item.// -->
            
        </div> <!-- card.// -->

    </aside> <!-- col.// -->

    <aside class="col-sm-4">
        <p>Filtro 2</p>


        <div class="card shadow">
            <article class="card-group-item">
                <header class="card-header"><h6 class="title">@lang('Orders') - @lang('Products') </h6></header>
                <div class="filter-content">
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.order.printexportbydate', [$dateInput, $dateOutput, 0, true, 0]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products') <span class="float-right badge badge-secondary round">@lang('TEXT')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Obtiene los <strong>productos</strong> de las órdenes en el rango especificado. En texto plano, desglosado por color y talla.</em>
    				@endif
                      <a href="{{ route('admin.order.printexportbydate', [$dateInput, $dateOutput, true, true, 0]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products')  <span class="float-right badge badge-info round ml-1">@lang('SUMMARY')</span>  <span class="float-right badge badge-secondary round">@lang('TEXT')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Obtiene los <strong>productos</strong> de las órdenes en el rango especificado. En texto plano y agrupado por color y talla.</em>
    				@endif
                      <a href="#!" wire:click="exportMaatwebsite('xlsx', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products') <span class="float-right badge badge-success round">@lang('EXCEL')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Descarga los <strong>productos</strong> de las órdenes en el rango especificado. En excel, agrupado por producto y color.</em>
    				@endif
                    </div>  <!-- list-group .// -->
                </div>
            </article> <!-- card-group-item.// -->
            <article class="card-group-item">
                <header class="card-header"><h6 class="title">@lang('Orders') - @lang('Services') </h6></header>
                <div class="filter-content">
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.order.printexportbydate', [$dateInput, $dateOutput, 0, 0, true]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Services') <span class="float-right badge badge-secondary round">@lang('TEXT')</span> 

                      </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Obtiene los <strong>servicios</strong> de las órdenes en el rango especificado. En texto plano, desglosado por color y talla.</em>
    				@endif
                      <a href="{{ route('admin.order.printexportbydate', [$dateInput, $dateOutput, true, 0, true]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Services')  <span class="float-right badge badge-info round ml-1">@lang('SUMMARY')</span>  <span class="float-right badge badge-secondary round">@lang('TEXT')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Obtiene los <strong>servicios</strong> de las órdenes en el rango especificado. En texto plano y agrupado por color y talla.</em>
    				@endif
                      <a href="#!" wire:click="exportMaatwebsite('xlsx', '0', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Services') <span class="float-right badge badge-success round">@lang('EXCEL')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Descarga los <strong>servicios</strong> de las órdenes en el rango especificado. En excel, agrupado por producto y color.</em>
    				@endif
                    </div>  <!-- list-group .// -->
                </div>
            </article> <!-- card-group-item.// -->
            <article class="card-group-item">
                <header class="card-header"><h6 class="title">@lang('Orders') - @lang('All') </h6></header>
                <div class="filter-content">
                    <div class="list-group list-group-flush">
                      <a href="{{ route('admin.order.printexportbydate', [$dateInput, $dateOutput, 0, true, true]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products & Services') <span class="float-right badge badge-secondary round">@lang('TEXT')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Obtiene los <strong>productos & servicios</strong> de las órdenes en el rango especificado. En texto plano, desglosado por color y talla.</em>
    				@endif
                      <a href="{{ route('admin.order.printexportbydate', [$dateInput, $dateOutput, true, true, true]) }}" target="_blank" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products & Services')  <span class="float-right badge badge-info round ml-1">@lang('SUMMARY')</span>  <span class="float-right badge badge-secondary round">@lang('TEXT')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Obtiene los <strong>productos & servicios</strong> de las órdenes en el rango especificado. En texto plano y agrupado por color y talla.</em>
    				@endif
                      <a href="#!" wire:click="exportMaatwebsite('xlsx', '1', '1')" class="list-group-item {{ (!$dateInput || !$dateOutput) ? 'disabled' : '' }}">@lang('Products & Services') <span class="float-right badge badge-success round">@lang('EXCEL')</span> </a>
                  	@if($details)
	                  	<em class="text-center p-2 text-muted"> Descarga los <strong>productos & servicios</strong> de las órdenes en el rango especificado. En excel, agrupado por producto y color.</em>
    				@endif
                    </div>  <!-- list-group .// -->
                </div>
            </article> <!-- card-group-item.// -->
        </div> <!-- card.// -->

    </aside> <!-- col.// -->

    <aside class="col-sm-4">

        <p>Filtro  3</p>

        <div class="card">
            <article class="card-group-item">

                <header class="card-header">
                    <h6 class="title">@lang('Records of feedstock') por proveedor [ENTRADAS]</h6>
                </header>
                <div class="filter-content">
                    <div class="card-body">
                        <livewire:backend.material.select-vendor />
                    </div> <!-- card-body.// -->

                    <div class="list-group list-group-flush">
                        <a href="#!" wire:click="exportMaterialHistoryMaatwebsite('xlsx')" class="list-group-item {{ (!$dateInput || !$dateOutput || !$vendor_id) ? 'disabled' : '' }}">@lang('Records of feedstock') <span class="float-right badge badge-success round">@lang('EXCEL')</span> </a>

                        @if($details)
                            <em class="text-center p-2 text-muted"> Descarga las <strong>registros de materia prima</strong> en el rango especificado, por proveedor y agrupados. [SÓLO ENTRADAS] En Excel.</em>
                        @endif
                    </div>
                </div>

            </article> <!-- card-group-item.// -->
        </div> <!-- card.// -->

    </aside> <!-- col.// -->
</div> <!-- row.// -->

</div> 

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#vendorselect').select2({
          placeholder: '@lang("Choose vendor")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.vendor.select') }}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250,
                dropdownautowidth: true
            }
          });

          $('#vendorselect').on('change', function (e) {
            var data = $('#vendorselect').select2("val");
            Livewire.emit('postVendor', data)
          });

      });
    </script>
    <script>
        Livewire.on('clear-vendor', clear => {
            jQuery(document).ready(function () {
                $("#vendorselect").val('').trigger('change')
            });
        })
    </script>

@endpush