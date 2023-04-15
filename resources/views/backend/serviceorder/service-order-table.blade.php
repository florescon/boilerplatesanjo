<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            @if($service_orders->count())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Dimensions')</th>
                                <th>@lang('File')</th>
                                <th>@lang('Comment')</th>
                                <th>@lang('Created by')</th>
                                <th>@lang('Created at')</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service_orders as $service_order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.order.print_service_order', [$order_id, $service_order->id]) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
                                          <ins>
                                            #{{ $service_order->id }}
                                          </ins>
                                        </a>
                                    </td>
                                    <td>{{ optional($service_order->image)->title }}</td>
                                    <td>{{ $service_order->dimensions ?? '--' }}</td>
                                    <td>{{ $service_order->file_text ?? '--' }}</td>
                                    <td>{{ $service_order->comment ?? '--' }}</td>
                                    <td>{{ optional($service_order->createdby)->name }}</td>
                                    <td>{{ $service_order->created_at }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            
                                            <x-actions-modal.edit-icon target="editServiceOrder" emitTo="backend.service-order.edit-service-order" function="edit" :id="$service_order->id" />
                        
                                            <x-actions-modal.delete-icon function="delete" :id="$service_order->id" />

                                        </div>
   
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                                
                </div>

                {{ $service_orders->links() }}

            @else
                <div class="text-center text-danger">
                    <em>Sin registros de órdenes de servicios</em>
                </div>
            @endif
        </div>
    </div>
</div>
