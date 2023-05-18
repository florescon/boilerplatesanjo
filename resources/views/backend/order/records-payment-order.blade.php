@extends('backend.layouts.app')

@section('title', __('Status records'))

@push('after-styles')
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}"> --}}
@endpush

@section('content')

<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-11 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                          <a href="{{ !$order->from_store ? route('admin.order.edit', $order->id) :  route('admin.store.all.edit', $order->id) }}" class="btn btn-primary" >
                           @lang('Go to edit order')
                          </a>
                      </div>

                        <h4 class="card-title">@lang('Payment records')</h4>
                        <p class="card-description"> @lang('Order') #{!! $order->folio_or_id !!} </p>
                        @if($records_payment->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            {{-- <th></th> --}}
                                            <th>@lang('Name')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Payment method')</th>
                                            <th>@lang('Comment')</th>
                                            <th>Creado por</th>
                                            <th>@lang('Created at')</th>
                                            <th>@lang('Actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($records_payment as $record)
                                            <tr>
                                                {{-- <td>{{ $record->order_id }}</td> --}}
                                                <td>{{ $record->name }}</td>
                                                <td>${{ $record->amount }}</td>
                                                <td>{{ $record->payment_method }}</td>
                                                <td>
                                                    <x-utils.undefined :data="$record->comment"/>
                                                </td>
                                                <td>{{ optional($record->audi)->name }}</td>
                                                <td>{{ $record->created_at }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                      <a type="button" href="{{ route('admin.store.finances.print', $record->id) }}" target="_blank" class="btn btn-transparent-dark">
                                                        <i class="fa fa-print"></i>
                                                      </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>                                
                            </div>

                            {{ $records_payment->links() }}

                        @else
                            <em>Sin registros</em>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("financeUpdate", () => {
          $("#editFinance").modal("hide");
      });
    </script>

@endpush
