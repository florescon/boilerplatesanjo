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

            <div class="container mt-">
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
                          <h2 class="blog-card__title" itemprop="headline">@lang('List of suborders')</h2>
                          <h5 class="blog-card__category">@lang('Suborders')</h5>
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

@endpush