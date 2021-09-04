@extends('frontend.layouts.app_ga')

@section('title', __('Dashboard'))

@section('content')
        <div class="section over-hide padding-top-bottom-120 section-background-10">
            <div class="section-1400">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 padding-top-120 padding-top-mob-nav">
                            <div class="section border-0 bg-transparent padding-top-bottom-120">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 text-center" data-scroll-reveal="enter left move 40px over 2s after 0.3s">
                                            <h4 class="mb-0 color-dark">@lang('You are logged in') <span class="color-primary">{{ Auth::user()->name }}</span>!</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->orders()->exists())
                        <div class="col-lg-2 px-0">
                            <div class="section padding-top padding-sm-top-50 z-bigger" id="sticker">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <nav class="navbar navbar-expand-lg navbar-light d-lg-block">
                                                <h4 class="mb-0 d-inline-block d-lg-none">@lang('Functions')</h4>                           
                                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                                                    <span class="navbar-toggler-icon"></span>
                                                </button>                               
                                                <div class="collapse navbar-collapse text-left flex-column" id="navbarMenu">
                                                    <a href="#data-tables" class="link size-16 w-100 mt-1" data-hover="@lang('Orders')">@lang('Orders')</a> 
                                                </div>                                  
                                            </nav>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-10 px-0">
                            <div class="container padding-top testimonials-v1" id="data-tables">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h4 class="mb-2">@lang('Orders')/@lang('Sales')</h4>
                                        <p class="mb-3 pb-3">@lang('View all') <span class="border-bottom-primary">@lang('orders and/or sales')</span>.</p>
                                    </div>
                                    <div class="section"></div>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover js-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ID</th>
                                                        <th scope="col">@lang('Tracking')</th>
                                                        <th scope="col">@lang('Comment')</th>
                                                        <th scope="col">@lang('Status')</th>
                                                        <th scope="col">@lang('Type')</th>
                                                        <th scope="col">@lang('Approved')</th>
                                                        <th scope="col">@lang('Date')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($orders as $order)
                                                    <tr class="table-tr"
                                                        @if($order->slug)
                                                            data-url="{{ route('frontend.track.show', $order->slug) }}"
                                                        @endif
                                                    >
                                                        <td>{{ $order->id }}</td>
                                                        <td>{{ $order->slug }}</td>
                                                        <td>{{ $order->comment }}</td>
                                                        <td>{!! $order->last_status_order_label !!}</td>
                                                        <td>{!! $order->type_order !!}</td>
                                                        <td>{!! $order->approved_label !!}</td>
                                                        <td>{{ $order->date_entered }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>    

                                            {{ $orders->links() }}

                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

@endsection


@push('after-scripts')
    <script type="text/javascript">
        $(function () {

            $(".js-table").on("click", "tr[data-url]", function () {
                window.location = $(this).data("url");
            });

        });
    </script>
@endpush