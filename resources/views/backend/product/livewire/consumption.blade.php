<x-backend.card>

	<x-slot name="header">
        @lang('Consumption product')
 	</x-slot>

  <x-slot name="headerActions">
      <x-utils.link class="card-header-action btn btn-primary text-white" :href="url()->previous()" :text="__('Go to edit product')" />
      <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Back')" />
	</x-slot>

  <x-slot name="body">
		<div class="row ">
			<div class="col-12 col-md-4">
        <div class="card card-custom card-product_not_hover bg-white border-white border-0">
				  <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <h5 class="card-title"><strong>{{ $model->name }}</strong></h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $model->code }}</h6>
              </li>
              <li class="list-group-item">
                <strong>@lang('Sizes'): </strong> 
                @foreach($model->children->unique('size_id')->sortBy('size.sort') as $sizes)
                  <button type="button" style="margin-top: 3px" class="btn {{ in_array($sizes->size_id, $filters_s) ? 'btn-primary text-white' : 'btn-outline-primary' }} btn-sm" wire:click="$emit('filterBySize', {{ $sizes->size_id }})">
                    {{ $sizes->size->name }} <span class="badge bg-danger text-white">{{ ltrim($product_general->getTotalConsumptionBySize($sizes->size_id), '0') }}</span>
                  </button>
                @endforeach
              </li>
              <li class="list-group-item">
                <strong>@lang('Colors'): </strong> 
                @foreach($model->children->unique('color_id')->sortBy('color.name') as $colors)
                  <button type="button" style="margin-top: 3px" class="btn {{ in_array($colors->color_id, $filters_c) ? 'btn-primary text-white' : 'btn-outline-primary' }} btn-sm" wire:click="$emit('filterByColor', {{ $colors->color_id }})">
                    {{ $colors->color->name }} <span class="badge bg-danger text-white">{{ ltrim($product_general->getTotalConsumptionByColor($colors->color_id), '0') }}</span>
                  </button>
                @endforeach
              </li>
            </ul>
				  </div>
				</div>
			</div>

			<div class="col-12 col-sm-6 col-md-8">
				<form wire:submit.prevent="store">
					<div class="row mb-2">
						<div class="col-9">
                <div class="form-group row" wire:ignore>
                    <div class="col-sm-12" >
                      <select id="materialmultiple" multiple="multiple" class="custom-select"  aria-hidden="true" required>
                      </select>
                    </div>
							  </div>
						</div>
						@if($material_id)
							<div class="col-3">
                <button class="btn btn-sm btn-outline-primary" type="submit">@lang('Save feedstock product') 
                  <i class="cil-arrow-right"></i>
                  <strong class="text-danger">{{ ($name_color || $name_size) ? $name_color.$name_size : 'General' }}</strong> 
              </button>
							</div>
						@endif
					</div>
				</form>

        @if($model->consumption->count())
          <div class="card card-box bg-white border-white border-0">

            <div class="card-body {{ $filters_c || $filters_s ? 'border border-primary' : ''}}">

              <div class="d-flex justify-content-between mb-4">
                  @if($model->file_name)
                    <div class="d-flex flex-row align-items-center">
                        <div class="card-custom-avatar2 mr-3"> <img style="  height: 100px;" class="img-fluid" src="{{ asset('/storage/' . $model->file_name) }}" alt="{{ $model->name }}" alt="Avatar" /> </div>
                    </div>
                  @endif
                  <div class="badge">
                    <h2>
                      @if($filters_c || $filters_s)
                        <span class="badge badge-pill badge-primary">Puntual</span>
                      @else
                        <span class="badge badge-pill badge-warning text-white">General</span>
                      @endif
                    </h2>
                  </div>

              </div>

              <h5 class="card-title {{ $name_color || $name_size ? 'text-dark font-italic typewriter' : 'text-dark' }} text-monospace font-weight-bold">
                {!! ($name_color || $name_size) ? __('Consumption').' <strong class="text-danger" style=" padding-left: 10px; padding-right: 10px; padding-bottom: 2px; border: 20px solid; ">'. $name_color.$name_size.'</strong>' : __('General consumption') !!}
              </h5>

              <div class="float-right custom-control custom-switch custom-control-inline">
                <input type="checkbox" wire:model="updateQuantity" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                <label class="custom-control-label" for="customRadioInline1">Editar cantidades</label>
              </div>
              <br><br>

              {{-- @json($groups) --}}

              @if($filters_c)
                <div class="table-responsive shadow-lg">
                  <table class="table table-sm">

                    <thead class="thead-dark">
                      <tr>
                        <th scope="col"> </th>
                        <th scope="col">@lang('Feedstock') - Concentrado de <span class="badge badge-primary">{{ $name_color }}</span> en base a la tabla de abajo</th>
                        <th scope="col" style="width: 180px;">@lang('Quantity')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($groups as $group)
                        <tr>
                          <th scope="row"></th>
                          <th scope="row"> 
                            {!! $group['part_number']  
                                ? 
                                '<div class="badge badge-dark text-wrap" >'
                                  .$group['part_number'].
                                '</div>'
                                :
                                ''
                            !!}
                          {!! $group['material_id'] !!}</th>
                          <td>{{ $group['quantity'] }}</td>
                        </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>

                <br>

              @endif


              @if($filters_s)
                <div class="table-responsive shadow-lg">
                  <table class="table table-sm">

                    <thead class="thead-dark">
                      <tr>
                        <th scope="col"> </th>
                        <th scope="col">@lang('Feedstock') - Concentrado de <span class="badge badge-primary">{{ $name_size }}</span> en base a la tabla de abajo</th>
                        <th scope="col" style="width: 180px;">@lang('Quantity')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($groups as $group)
                        <tr>
                          <th scope="row"></th>
                          <th scope="row">
                            {!! $group['part_number']  
                                ? 
                                '<div class="badge badge-dark text-wrap" >'
                                  .$group['part_number'].
                                '</div>'
                                :
                                ''
                            !!}
                          {!! $group['material_id'] !!}</th>
                          <td>{{ $group['quantity'] }}</td>
                        </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>

                <br>

              @endif


              <div class="table-responsive ">
                <table class="table table-sm shadow">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col"> </th>
                      <th scope="col">
                        @lang('Feedstock')
                        {{ $filters_c ? '- Detalles' : '' }}
                      </th>

                      @if( (!$name_size && !$name_color) && ($updateQuantity == TRUE))
                        <th scope="col" style="width: 180px;">@lang('Current quantity')</th>
                      @endif

                      <th scope="col" style="width: 180px;">@lang('Quantity')</th>

                      @if(($name_color or $name_size) && ($updateQuantity == TRUE))
                        <th scope="col" style="width: 180px;">@lang('Difference')</th>
                      @endif

                      <th></th>

                    </tr>
                  </thead>
                  <tbody>

                    @foreach($grouped as $key => $consumo)

                      @foreach($consumo as $yas)
                        <tr class="{{ ($yas->color_id == null xor $yas->size_id == null)  ? 'table-primary' : 'table-warning' }}">
                          <th scope="row"></th>
                          <th scope="row" class=" {{  ($yas->color_id != null || $yas->size_id != null) ? 'font-italic' : ''  }}" > 
                            {!! $yas->material->part_number  
                                ? 
                                '<div class="badge badge-dark text-wrap" >'
                                  .$yas->material->part_number.
                                '</div>'
                                :
                                ''
                            !!}
                            {!! $yas->material->full_name !!}
                            @if($yas->puntual == TRUE)  <span class="badge badge-success">Puntual</span>@endif 
                          </th>

                          @if( (!$name_size && !$name_color) && ($updateQuantity == TRUE))
                          <th scope="col">
                            {!! $yas->quantity_formatted !!}
                          </th>
                          @endif

                          <th scope="row">

                            @if($updateQuantity == TRUE)

                              @if($yas->color_id == null && (!$name_color && !$name_size))
                                <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit;" wire:model.lazy="inputquantities.{{ $yas->id }}.consumption" wire:keydown.enter="quantities({{ $yas->id }})" type="number" step="any" required {{ ($yas->color_id == null || $yas->size_id == null) && $name_color ? 'disabled' : '' }}>
                              @else
                                {!! ($yas->color_id <> null || $yas->size_id <> null) ? __('Difference').' actual: '. $yas->quantity_formatted : $yas->quantity_formatted.'  <small class="text-muted"><em>(General)</em></small>' !!}
                              @endif
                            @else

                                {!! $yas->quantity_formatted !!}

                            @endif
                            {{-- <input type="text" class="form-control" name="quantity"> --}}
                          </th>

                        @if(($name_color or $name_size)  && ($updateQuantity == TRUE))
                          <td scope="row">
                            @if($yas->puntual == TRUE || (!$yas->color_id && !$yas->size_id))
                              <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit; border-color: blue;" wire:model.lazy="inputquantities_difference.{{ $yas->id }}.consumption" wire:keydown.enter="quantities({{ $yas->id }})" type="number" step="any" required>
                            @endif
                          </td>
                        @endif
                          <td>
                            @if($yas->puntual == TRUE || $yas->size_id || $yas->color_id)
                              <div class="dropdown">
                                <a class="btn btn-icon-only " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                  <a class="dropdown-item" wire:click="delete({{ $yas->id }})">@lang('Delete')</a>
                                </div>
                              </div>
                            @endif
                            @if(!$name_size && !$name_color)
                              <div class="dropdown">
                                <a class="btn btn-icon-only " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                  <a class="dropdown-item" wire:click="deleteRelationsFeedstock({{ $yas->id }})">@lang('Delete all relations')</a>
                                </div>
                              </div>
                            @endif
                          </td>
                        </tr>
                      @endforeach

                    @endforeach
                  </tbody>
                </table>
              </div>


              {{-- {{'Filter: '. $model->consumption_filter_count }} --}}

              <br>
              {{-- <a href="#" class="card-link">Reporte total</a> --}}
              {{-- <a href="#" class="card-link">Another link</a> --}}

                        
              <p class="h1 text-center">
                {{-- <a href="https://github.com/peterdanis/custom-bootstrap-cards" target="_blank"> --}}
                  <i class="fas fa-file-alt"></i>
                </a>
              </p>

            </div>
          </div>
        @else
          <div class="alert alert-secondary" role="alert">
            Aun nada agregado 
          </div>
        @endif

			</div>
		</div>

    </x-slot>


  <x-slot name="footer">
    <x-utils.delete-button :text="__('Delete feedstocks')" :href="route('admin.product.clear_consumption', $model->id)" />
 	  <footer class="blockquote-footer float-right">
		 Mies Van der Rohe <cite title="Source Title">Less is more</cite>
	  </footer>
	</x-slot>

</x-backend.card>

@push('after-scripts')
  <script>
    $(document).ready(function() {
      $('#materialmultiple').select2({
        closeOnSelect: false,
        placeholder: '@lang("Choose feedstocks")',
        width: 'resolve',
        theme: 'bootstrap4',
        allowClear: true,
        multiple: true,
        ajax: {
              url: '{{ route('admin.material.select') }}',
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
  	                        text:  item.part_number.fixed() + ' ' +item.name + ' ' + (item.unit_id ? item.unit.name.sup() : '') + (item.color_id  ?  '<br> Color: ' + item.color.name.bold()  : '')  + (item.size_id  ?  '<br> Talla: ' + item.size.name.bold()  : '')

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
          },
  	      escapeMarkup: function(m) { return m; }

        });

        $('#materialmultiple').on('change', function (e) {
          var data = $('#materialmultiple').select2("val");
          @this.set('material_id', data);
        });


    });
  </script>

  <script type="text/javascript">
    Livewire.on("materialReset", () => {
      $('#materialmultiple').val([]).trigger("change");
    });
  </script>
@endpush