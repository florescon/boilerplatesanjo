@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
@endpush

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            {{-- @lang('Welcome to the Dashboard') --}}

            @if($logged_in_user->isMasterAdmin())
                <div class="page-content page-container" id="page-content">
                    <div class="padding">
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-12 grid-margin stretch-card ">
                                <div class="card ">
                                    <div class="card-body">
                                        <h4 class="card-title"> @lang('Orders')/@lang('Sales') </h4>
                                        <p class="card-description"> @lang('Complete recent listing') </p>
                                        @if($orders->count())
                                            <div class="table-responsive">
                                                <table class="table js-table">
                                                    <thead>
                                                        <tr>
                                                            <th> f.º </th>
                                                            <th> @lang('User') </th>
                                                            <th> @lang('Comment') </th>
                                                            <th> @lang('Progress') </th>
                                                            <th> @lang('Total') </th>
                                                            <th> @lang('Created') </th>
                                                            <th> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($orders as $order)
                                                        <tr class="table-tr" style="{{ $order->type_order_classes }}" data-url="{{ route('admin.order.edit', $order->id) }}">
                                                            <td class="py-1"> 
                                                                #{{ $order->id }}  
                                                                {!! $order->approved_alert !!}
                                                            </td>
                                                            <td> {!! $order->user_name !!} </td>
                                                            <td> {!! Str::limit($order->comment, 100) !!} </td>
                                                            <td>
                                                                @if($order->last_status_order)
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $order->last_status_order_percentage }}%" aria-valuenow="{{ $order->last_status_order_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                @endif
                                                                {!! $order->last_status_order_label !!}
                                                            </td>
                                                            <td> $ {{ number_format((float)$order->total_sale_and_order, 2) }} </td>
                                                            <td> {{ $order->date_for_humans }} </td>
                                                            <td> {!! $order->from_store_or_user_label !!} </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{ $orders->links() }}
                                        @else
                                            <div class="text-center">
                                                <em>@lang('No results!')</em>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-1">
                <div class="row">
                    <div class="col-md-12">
                      <article >
                            <div class="col-xs-24 col-sm-8 col-lg-6">
                                <a href="{{ route('admin.cart.index') }}" class="blog-card hyphy">                  
                                  <div class="blog-card__square"></div>
                                  <div class="blog-card__circle"></div>
                                  <h2 class="blog-card__title text-right" itemprop="headline">
                                    <i class="cil-plus fa-3x"></i><br>
                                    @lang('Order')<br>
                                    o<br>
                                    @lang('Sale')
                                  </h2>
                                  <h5 class="blog-card__category">@lang('Create')</h5>
                                </a>              
                            </div>
                      </article>
                      <article >
                          <div class="col-xs-24 col-sm-8 col-lg-6">
                            <a href="{{ route('admin.order.index') }}" class="blog-card linear">                  
                                <div class="blog-card__square"></div>
                              <div class="blog-card__circle"></div>
                              <h2 class="blog-card__title" itemprop="headline">@lang('Show Order - Sale')</h2>
                              <h5 class="blog-card__category">@lang('Orders')<br>@lang('Sales')</h5>
                            </a>              
                          </div>
                      </article>
                      <article >
                          <div class="col-xs-24 col-sm-8 col-lg-6">
                            <a href="{{ route('admin.product.index') }}" class="blog-card text">                  
                                <div class="blog-card__square"></div>
                                <div class="blog-card__circle"></div>
                                <h2 class="blog-card__title" itemprop="headline">@lang('Show products')</h2>
                                <h5 class="blog-card__category">@lang('Products')</h5>
                                </a>            
                          </div>
                      </article>
                      <article >
                        <div class="col-xs-24 col-sm-8 col-lg-6">
                            <a href="{{ route('admin.material.index') }}" class="blog-card radial">                  
                                <div class="blog-card__square"></div>
                                <div class="blog-card__circle"></div>
                                <h2 class="blog-card__title" itemprop="headline">@lang('Show feedstock')</h2>
                                <h5 class="blog-card__category">@lang('Feedstock')</h5>
                            </a>              
                        </div>
                      </article>
                      <article >
                          <div class="col-xs-24 col-sm-8 col-lg-6">
                                <a href="{{ route('admin.auth.user.index') }}" class="blog-card powerpoint">                  
                                  <div class="blog-card__square"></div>
                                  <div class="blog-card__circle"></div>
                                  <h2 class="blog-card__title" itemprop="headline">@lang('Show users')</h2>
                                  <h5 class="blog-card__category">@lang('Users')</h5>
                                </a>              
                            </div>
                      </article>
                      <article >
                        <div class="col-xs-24 col-sm-8 col-lg-6">
                            <a href="{{ route('admin.order.suborders') }}" class="blog-card repeating">                  
                              <div class="blog-card__square"></div>
                              <div class="blog-card__circle"></div>
                              <h2 class="blog-card__title" itemprop="headline">@lang('List of suborder outputs')</h2>
                              <h5 class="blog-card__category">@lang('Suborder outputs')</h5>
                          </a>              
                        </div>
                      </article>
                      <article >
                            <div class="col-xs-24 col-sm-8 col-lg-6">
                                <a href="{{ route('admin.product.list') }}" class="blog-card photoshop">                  
                                  <div class="blog-card__square"></div>
                                  <div class="blog-card__circle"></div>
                                  <h2 class="blog-card__title" itemprop="headline">@lang('Product variants')</h2>
                                  <h5 class="blog-card__category">@lang('List of products')</h5>
                                </a>              
                            </div>
                      </article>
                      <article >
                          <div class="col-xs-24 col-sm-8 col-lg-6">
                                <a href="{{ route('admin.line.index') }}" class="blog-card background">                  
                                  <div class="blog-card__square"></div>
                                  <div class="blog-card__circle"></div>
                                  <h2 class="blog-card__title" itemprop="headline">@lang('Show lines')</h2>
                                  <h5 class="blog-card__category">@lang('Lines')</h5>
                                </a>              
                            </div>
                      </article>
                    </div>
            </div>
        </div>
            <div class="container mt-2">
            <div class="row">
                    <div class="col-md-12 ">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <canvas id="canvas" height="280" width="600"></canvas>
                            </div>
                        </div>
                    </div>
            </div>
            </div>

        </x-slot>
    </x-backend.card>
@endsection


@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>

<script>
    var months2 = <?php echo $months2; ?>;
    var user = <?php echo $user; ?>;
    var users_label = @json(__('Users'));
    var barChartData = {
        labels: months2,
        datasets: [{
            label: users_label,
            backgroundColor: "pink",
            data: user
        }]
    };

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Monthly User Joined'
                }
            }
        });
    };
</script>

<script type="text/javascript">
    $(function () {
        $(".js-table").on("click", "tr[data-url]", function () {
            window.location = $(this).data("url");
        });
    });
</script>

@endpush