@extends('backend.layouts.app')

@section('title', __('Charts'))

@push('after-styles')

	{{-- <style>
		.card-columns {
		    column-count: 3;
		}
	</style> --}}

@endpush

@section('content')

<div class="animated fadeIn">

  <!-- /.row-->
  <div class="row mt-2">
    <div class="col">

		<div class="d-flex mb-4">
	        Mostrando {{ $orders->firstItem() }} - {{ $orders->lastItem() }} de
	        {{ $orders->total() }} resultados
	        <div class="ml-4 pl-4">
	      		{{ $orders->links() }}
	      	</div>
	  	</div>
    
      	<div class="card-columns pr-1 pl-1">
	      	@foreach($orders as $order)
	        <div class="card shadow">
	          <h5 class="card-header p-3 text-center">ID: #{{ $order->id }}, Folio: #{!! $order->folio_or_id !!}</h5>
	          <h5 class="card-header p-3 text-center">{!! $order->user_name !!}</h5>
	          <div class="card-body text-center">
	            @if($order->request)
	              <p class="card-text"><strong>@lang('Request number'): </strong> {{ $order->request }}</p>
	            @endif
	            @if($order->purchase)
	              <p class="card-text"><strong>@lang('Purchase order'): </strong>{{ $order->purchase }}</p>
	            @endif
	            @if($order->invoice)
	              <p class="card-text"><strong>@lang('Invoice')</strong>{{ $order->invoice }}</p>
	            @endif
	            <p class="card-text">{{ $order->info_customer }}</p>
	            <p class="card-text">{{ $order->comment }}</p>
	            <p class="card-text">{{ $order->observation }}</p>

	            <p class="card-text"><strong>@lang('Total'):</strong> <strong class="text-danger">{{ $order->total_products }}</strong> </p>

	            <div>
	              <canvas id="doughnut-chart-{{ $order->id }}" width="800" height="550"></canvas>
	            </div>
	   
	            <div class="text-center p-4">
	            	@foreach($order->total_graphic_work['collectionExtra'] as $key => $value)
	                	<li class="list-group-item list-group-item-secondary" style="background-color: ;">{!! ucfirst($key) .': <strong class="text-danger">'.$value.'</strong>' !!}</li>
	              @endforeach
	            </div>

	          </div>
	          <div class="card-body text-center">
	            <a href="{{ route('admin.order.edit_chart', $order->id) }}" target="_blank" class="btn btn-outline-primary border-0 shadow-sm p-2 w-75">Ir a la orden</a>
	          </div>
	        </div>
	      	@endforeach
      	</div>

    </div>
  </div>
  <!-- /.row-->
</div>

@endsection

@push('after-scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> 

  <script>
    @foreach ($orders as $order)
      var labels{{ $order->id }} =  {!! json_encode($order->total_graphic_work['collection']->keys()) !!};
      var values{{ $order->id }} =  {!! json_encode($order->total_graphic_work['collection']->values()) !!};
      var colors{{ $order->id }} = {!! json_encode($order->total_graphic_work['colors']) !!};

      // Map labels to their corresponding colors
      var backgroundColors{{ $order->id }} = labels{{ $order->id }}.map(label => colors{{ $order->id }}[label] || '#000000'); // Default to black if no color is found

      new Chart(document.getElementById("doughnut-chart-{{ $order->id }}"), {
          type: 'doughnut',
          data: {
            labels: labels{{ $order->id }},
            datasets: [
              {
                label: "Estaciones",
                backgroundColor: backgroundColors{{ $order->id }},
                data: values{{ $order->id }}
              }
            ]
          },
          options: {
            title: {
              display: true,
              text: 'Avance'
            }
          }
      });
    @endforeach
  </script>
@endpush
