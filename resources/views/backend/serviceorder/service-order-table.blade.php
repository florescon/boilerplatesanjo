<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            @if($service_orders->count())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Created by')</th>
                                <th>@lang('Created at')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service_orders as $record)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.order.print_service_order', [$order_id, $record->id]) }}" class="card-link text-dark" target="_blank"><i class="cil-print"></i>
                                          <ins>
                                            #{{ $record->id }}
                                          </ins>
                                        </a>
                                    </td>
                                    <td>{{ $record->image->title }}</td>
                                    <td>{{ $record->createdby->name }}</td>
                                    <td>{{ $record->created_at }}</td>
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