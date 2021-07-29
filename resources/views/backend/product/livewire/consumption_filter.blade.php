<x-backend.card>

	<x-slot name="header">
        @lang('Product consumption filter')
 	</x-slot>

 	<x-slot name="headerActions">

 	</x-slot>

	<x-slot name="body">

		<div class="row ">
			<div class="col-12 col-sm-6 col-md-6">
			    <div class="card card-product_not_hover card-flyer-without-hover">
			      <div class="card-header text-center">
				    <h5 class="card-title"><strong> {!! '<strong>' .$model->parent->name.' </strong> ' !!} </strong></h5>
			        <h6 class="card-subtitle mb-2 text-muted">
			        	{!! '('.optional($model->color)->name.'  '.optional($model->size)->name.')' !!}
			        </h6>

				  </div>

			      <div class="card-body">
					<div class="list-group list-group-accent">
						<div class="list-group-item list-group-item-accent-info list-group-item-info">
							Concentrado
						</div>
						<table class="table table-hover">
						  <tbody>
		                  	@foreach($groups as $consumption_group)
							    <tr>
							      <td>{!! $consumption_group['material_id'] !!}</td>
							      <th scope="row">{{ $consumption_group['quantity'] }}</th>
							    </tr>
		                  	@endforeach
						  </tbody>
						</table>
  					</div>
			      </div>
				</div>				
			</div>

			<div class="col-12 col-sm-6 col-md-6">
			    <div class="card card-flyer-without-hover border-0">
			      {{-- <div class="card-header text-center">
				    <h5 class="card-title"><strong> General </strong></h5>
				  </div> --}}
			      <div class="card-body">
					<div class="list-group list-group-accent">
						<div class="list-group-item list-group-item-accent-warning list-group-item-warning">
							General
						</div>
						<table class="table table-hover">
						  <tbody>
		                  	@foreach($model->consumption_filter->where('color_id', null)->where('size_id', null) as $consumption)
							    <tr>
							      <td>{{ $consumption->material->name }}</td>
							      <th scope="row">{{ $consumption->quantity }}</th>
							    </tr>
		                  	@endforeach
						  </tbody>
						</table>
  					</div>
			      </div>
				</div>				

			    <div class="card card-flyer-without-hover border-0">
			      {{-- <div class="card-header text-center">
				    <h5 class="card-title"><strong> Diferencias </strong></h5>
				  </div> --}}
			      <div class="card-body">
					<div class="list-group list-group-accent">
						<div class="list-group-item list-group-item-accent-primary list-group-item-primary">
							Diferencia color
						</div>
						<table class="table table-hover">
						  <tbody>
		                  	@foreach($model->consumption_filter->where('color_id', $model->color_id) as $consumption)
							    <tr>
							      <td>{{ $consumption->material->name }}</td>
							      <th scope="row">{{ $consumption->quantity }}</th>
							    </tr>
		                  	@endforeach
						  </tbody>
						</table>
  					</div>

					<div class="list-group list-group-accent">
						<div class="list-group-item list-group-item-accent-primary list-group-item-primary">
							Diferencia talla
						</div>
						<table class="table table-hover">
						  <tbody>
		                  	@foreach($model->consumption_filter->where('size_id', $model->size_id) as $consumption)
							    <tr>
							      <td>{{ $consumption->material->name }}</td>
							      <th scope="row">{{ $consumption->quantity }}</th>
							    </tr>
		                  	@endforeach
						  </tbody>
						</table>
  					</div>

			      </div>
				</div>				

			</div>

		</div>

	</x-slot>

	<x-slot name="footer">
 	  <footer class="blockquote-footer float-right">
		 Mies Van der Rohe <cite title="Source Title">Less is more</cite>
	  </footer>
	</x-slot>

</x-backend.card>
