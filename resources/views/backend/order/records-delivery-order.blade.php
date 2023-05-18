@extends('backend.layouts.app')

@section('title', __('Status records'))

@push('after-styles')
    {{-- <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}"> --}}
@endpush

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                          <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-primary" >
                           @lang('Go to edit order')
                          </a>
                      </div>

                        <h4 class="card-title">@lang('Delivery records')</h4>
                        <p class="card-description"> @lang('Order') #{!! $order->folio_or_id !!} </p>
                        @if($records_delivery->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            {{-- <th></th> --}}
                                            <th>@lang('Status')</th>
                                            <th>Creado por</th>
                                            <th>@lang('Created at')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($records_delivery as $record)
                                            <tr>
                                                {{-- <td>{{ $record->order_id }}</td> --}}
                                                <td>{{ $record->formatted_type }}</td>
                                                <td>{{ optional($record->user)->name }}</td>
                                                <td>{{ $record->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>                                
                            </div>

                            {{ $records_delivery->links() }}

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
