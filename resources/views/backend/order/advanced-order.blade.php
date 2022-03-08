@extends('backend.layouts.app')

@section('title', __('Advanced options'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}">
@endpush

@section('content')

<x-backend.card>

    <x-slot name="header">
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.order.edit', $order->id)" icon="fa fa-chevron-left" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">

    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content animated fadeInRight">

                <div class="ibox-content m-b-sm border-bottom">
                    <div class="p-xs">
                        <div class="pull-left m-r-md">
                            <i class="fa fa-globe text-navy mid-icon"></i>
                        </div>
                        <h2 class="mt-2"> &nbsp;Bienvenido a opciones avanzadas</h2> 

                        <h4>
                            &nbsp;{!! $order->type_order !!}
                            Folio #{{ $order->id }}, @lang('Order track'): {{ $order->slug }}
                  <a href="{{ route('frontend.track.show', $order->slug) }}" target=”_blank”>
                    <span class="badge badge-primary"> 
                      <i class="cil-external-link"></i>
                    </span>
                  </a>

                        </h4>
                        <span> &nbsp; {{ $order->comment }} </span>
                    </div>
                </div>

                <div class="ibox-content forum-container">

                    <div class="forum-title">
                        <div class="pull-right forum-desc">
                            {{-- <samll>Total posts: 320,800</samll> --}}
                        </div>
                        <h3>@lang('Products')</h3>
                    </div>

                    <div class="forum-item active">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="forum-icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
                                <a href="forum_post.html" class="forum-item-title">Quiero finalizar {{ $order->type_order_clear }} y agregar productos al stock</a>
                                <div class="forum-sub-title">Los productos se agregan al stock</div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    1216
                                </span>
                                <div>
                                    <small>Views</small>
                                </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    368
                                </span>
                                <div>
                                    <small>Topics</small>
                                </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    140
                                </span>
                                <div>
                                    <small>Posts</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="forum-title">
                        <div class="pull-right forum-desc">
                            {{-- <samll>Total posts: 17,800,600</samll> --}}
                        </div>
                        <h3>@lang('Feedstock')</h3>
                    </div>

                    <div class="forum-item active">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="forum-icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
                                <a href="forum_post.html" class="forum-item-title">@lang('Delete consumption')</a>
                                <div class="forum-sub-title"> Se eliminará el consumo. Aplicable sólo una vez a esta orden. </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    1766
                                </span>
                                <div>
                                    <small>Views</small>
                                </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    321
                                </span>
                                <div>
                                    <small>Topics</small>
                                </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    42
                                </span>
                                <div>
                                    <small>Posts</small>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="forum-title">
                        <div class="pull-right forum-desc">
                            {{-- <samll>Total posts: 17,800,600</samll> --}}
                        </div>
                        <h3>@lang('Other options')</h3>
                    </div>

                    <div class="forum-item active">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="forum-icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
                                <a href="forum_post.html" class="forum-item-title">Reasignar usuario/departamento</a>
                                <div class="forum-sub-title">Toda la orden se reasignara al usuario. No aplica si la orden tiene subordenes definidas. Aplicable sólo una vez a esta {{ $order->type_order_clear }}. </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    890
                                </span>
                                <div>
                                    <small>Views</small>
                                </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    120
                                </span>
                                <div>
                                    <small>Topics</small>
                                </div>
                            </div>
                            <div class="col-md-1 forum-info">
                                <span class="views-number">
                                    154
                                </span>
                                <div>
                                    <small>Posts</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </x-slot>

</x-backend.card>

@endsection
