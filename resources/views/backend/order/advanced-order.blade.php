@extends('backend.layouts.app')

@section('title', __('Advanced order'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/advanced-order.css') }}">
@endpush


@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="ibox-content m-b-sm border-bottom">
                <div class="p-xs">
                    <div class="pull-left m-r-md">
                        <i class="fa fa-globe text-navy mid-icon"></i>
                    </div>
                    <h2> &nbsp;Bienvenido a opciones avanzadas, Folio #</h2>
                    <span> &nbsp;@lang("Feel free to choose option your'e interested in.")</span>
                </div>
            </div>

            <div class="ibox-content forum-container">

                <div class="forum-title">
                    <div class="pull-right forum-desc">
                        <samll>Total posts: 320,800</samll>
                    </div>
                    <h3>General subjects</h3>
                </div>

                <div class="forum-item active">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="forum-icon">
                                <i class="fa fa-shield"></i>
                            </div>
                            <a href="forum_post.html" class="forum-item-title">Quiero finalizar orden y agregar todo al stock</a>
                            <div class="forum-sub-title">Regresa las ventas al stock de tienda, las ordenes al stock de revision intermedia y la materia prima al stock correspondiente</div>
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
                <div class="forum-item">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="forum-icon">
                                <i class="fa fa-bolt"></i>
                            </div>
                            <a href="forum_post.html" class="forum-item-title">Cancelar venta</a>
                            <div class="forum-sub-title">New to the community? Please stop by, say hi and tell us a bit about yourself. </div>
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

                <div class="forum-title">
                    <div class="pull-right forum-desc">
                        <samll>Total posts: 17,800,600</samll>
                    </div>
                    <h3>Other subjects</h3>
                </div>

                <div class="forum-item">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="forum-icon">
                                <i class="fa fa-bomb"></i>
                            </div>
                            <a href="forum_post.html" class="forum-item-title">There are many variations of passages</a>
                            <div class="forum-sub-title"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the . </div>
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

            </div>
        </div>
    </div>
</div>

@endsection
