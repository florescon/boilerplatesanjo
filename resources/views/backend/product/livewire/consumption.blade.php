<x-backend.card>

	<x-slot name="header">
        @lang('Consumption product')
 	</x-slot>

  <x-slot name="headerActions">

      <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $model->id)" :text="__('Go to edit product')" />

      <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
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
                @foreach($model->children->unique('size_id')->sortBy('size.name') as $sizes)
                <a href="#" class="badge badge-light">{{ $sizes->size->name }}</a>
                @endforeach
              </li>

              <li class="list-group-item">
                <strong>@lang('Colors'): </strong> 
                @foreach($model->children->unique('color_id')->sortBy('size.name') as $colors)
                  <button type="button" style="margin-top: 3px" class="btn {{ in_array($colors->color_id, $filters) ? 'btn-primary text-white' : 'btn-outline-primary' }} btn-sm" wire:click="$emit('filterByTag', {{ $colors->color_id }})">
                    {{ $colors->color->name }} <span class="badge bg-secondary text-dark">4</span>
                  </button>
                @endforeach
              </li>

            </ul>

				  </div>
				</div>

			</div>

  			<div class="col-12 col-sm-6 col-md-8">

  				<form wire:submit.prevent="store">
  					<div class="row mb-4">
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
                  <button class="btn btn-sm btn-primary" type="submit">@lang('Save feedstock product')</button>
  							</div>
  						@endif
  					</div>
  				</form>

          @if($model->consumption->count())
          <div class="card card-box bg-white border-white border-0">

          <div class="card-custom-img" style="background-image: url(http://res.cloudinary.com/d3/image/upload/c_scale,q_auto:good,w_1110/trianglify-v1-cs85g_cc5d2i.jpg);"></div>


          @if($model->file_name)
          <div class="card-custom-avatar">
            <img class="img-fluid" src="{{ asset('/storage/' . $model->file_name) }}" alt="{{ $model->name }}" alt="Avatar" />
          </div>
          @endif
            <div class="card-body">
              <h5 class="card-title text-monospace font-weight-bold">{{ $name_color ? __('Consumption').' '. $name_color : __('General consumption') }}</h5>
  
              <div class="float-right custom-control custom-switch custom-control-inline">
                <input type="checkbox" wire:model="updateQuantity" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                <label class="custom-control-label" for="customRadioInline1">Editar cantidades</label>
              </div>
              <br><br>


              {{-- @json($groups) --}}

              @if($filters)
                <div class="table-responsive shadow-lg">
                  <table class="table table-sm">

                    <thead class="thead-dark">
                      <tr>
                        <th scope="col"> </th>
                        <th scope="col">@lang('Feedstock') - Total de <span class="badge badge-primary">{{ $name_color }}</span></th>
                        <th scope="col" style="width: 180px;">@lang('Quantity')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($groups as $group)
                        <tr>
                          <th scope="row"></th>
                          <th scope="row">{!! $group['material_id'] !!}</th>
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
                        {{ $filters ? '- Detalles' : '' }}
                      </th>
                      <th scope="col" style="width: 180px;">@lang('Quantity')</th>

                      @if($name_color && ($updateQuantity == TRUE))
                        <th scope="col" style="width: 180px;">@lang('Difference')</th>
                      @endif

                    </tr>
                  </thead>
                  <tbody>

                    @foreach($grouped as $key => $consumo)

                      @foreach($consumo as $yas)
                        <tr class="{{ $yas->color_id == null ? 'table-warning' : 'table-primary' }}">
                          <th scope="row"></th>
                          <th scope="row" class=" {{  $yas->color_id != null ? 'font-italic' : ''  }}" > {!! $yas->material->full_name !!}</th>
                          <th scope="row">

                            @if($updateQuantity == TRUE)

                              @if($yas->color_id == null && !$name_color)
                                <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit;" wire:model="inputquantities.{{ $yas->id }}.consumption" wire:keydown.enter="quantities({{ $yas->id }})" type="number" step="any" required {{ $yas->color_id == null && $name_color ? 'disabled' : '' }}>
                              @else
                                {!! ($yas->color_id <> null) ? __('Difference').' actual: '. $yas->quantity : $yas->quantity.'  <small class="text-muted"><em>(General)</em></small>' !!}
                              @endif
                            @else

                                {!! $yas->quantity !!}

                            @endif
                            {{-- <input type="text" class="form-control" name="quantity"> --}}
                        </th>

                        @if($name_color && ($updateQuantity == TRUE))
                          <td scope="row">
                            @if($yas->color_id == null)
                              <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit; border-color: blue;" wire:model="inputquantities_difference.{{ $yas->id }}.consumption" wire:keydown.enter="quantities({{ $yas->id }})" type="number" step="any" required>
                            @endif
                          </td>
                        @endif
                        
                        </tr>
                      @endforeach

                  @endforeach
                  </tbody>
                </table>
              </div>


              {{-- {{'Filter: '. $model->consumption_filter_count }} --}}

              <br>
              <a href="#" class="card-link">Reporte total</a>
              <a href="#" class="card-link">Another link</a>

            <p class="h1 text-center">
              <a href="https://github.com/peterdanis/custom-bootstrap-cards" target="_blank">
                <i class="fas fa-file-alt"></i>
              </a>
            </p>

            </div>
          </div>
          @endif

  			</div>
		</div>

    </x-slot>


  <x-slot name="footer">
 	  <footer class="blockquote-footer float-right">
		 Mies Van der Rohe <cite title="Source Title">Less is more</cite>
	  </footer>
	</x-slot>

</x-backend.card>



@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#materialmultiple').select2({
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