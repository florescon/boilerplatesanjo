@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/gradient.css')}}">
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">

    <style>
        .kpi-card {
            border: none;
            border-radius: 12px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }

        .kpi-icon {
            font-size: 40px;
            opacity: .8;
        }

        .kpi-value {
            font-size: 32px;
            font-weight: bold;
        }

        .bg-gradient-blue {
            background: linear-gradient(45deg, #007bff, #00c6ff);
        }

        .bg-gradient-green {
            background: linear-gradient(45deg, #28a745, #7bed9f);
        }

        .bg-gradient-orange {
            background: linear-gradient(45deg, #fd7e14, #ffc107);
        }

        .bg-gradient-red {
            background: linear-gradient(45deg, #dc3545, #ff7675);
        }
    </style>
@endpush

@section('content')
    @if ($logged_in_user->hasAllAccess() || ($logged_in_user->can('admin.access.dashboard.kanban')))
    <div class="page-wrapper">


        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col">
                    <h4>Dashboard</h4>
                </div>
            </div>

            <livewire:backend.charts.proceed-in-order />

        <div class="row">


            <!-- Ventas -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card kpi-card bg-gradient-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Ventas</h6>
                                <div class="kpi-value">$125K</div>
                                <small>+12% este mes</small>
                            </div>
                            <i class="fas fa-dollar-sign kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card kpi-card bg-gradient-green">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Clientes</h6>
                                <div class="kpi-value">3,250</div>
                                <small>+8% crecimiento</small>
                            </div>
                            <i class="fas fa-users kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pedidos -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card kpi-card bg-gradient-orange">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Pedidos</h6>
                                <div class="kpi-value">856</div>
                                <small>Hoy</small>
                            </div>
                            <i class="fas fa-shopping-cart kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversión -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card kpi-card bg-gradient-red">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Conversión</h6>
                                <div class="kpi-value">24%</div>
                                <small>+3% vs anterior</small>
                            </div>
                            <i class="fas fa-chart-line kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

            <div class="row">
                <div class="col-md-3">
    <div class="card">
      <div class="card-header           ">
        Lote #15
      </div>

      <div class="card-body">

        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <span>Corte</span>
            <span>35</span>
          </div>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;">
            </div>
          </div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <span>Costura</span>
            <span>15</span>
          </div>
          <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" style="width: 43%;">
            </div>
          </div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <span>Empaque</span>
            <span>10</span>
          </div>
          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 29%;">
            </div>
          </div>
        </div>

        <div>
          <div class="d-flex justify-content-between">
            <span>Terminado</span>
            <span>5</span>
          </div>
          <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 14%;">
            </div>
          </div>
        </div>

      </div>
    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            Card
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            Card
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            Card
                        </div>
                    </div>
                </div>
            </div>

            <!-- resto del dashboard -->

            <div class="row card-group-row mb-lg-8pt">

                <div class="col-md-4 mb-4">

                    <div class="card h-100">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <div class="row no-gutters">

                                <div class="col-auto pr-4">
                                    <h2 class="mb-0">14</h2>
                                    <strong>Projects</strong>
                                </div>

                                <div class="col-auto pl-4 border-left">
                                    <h2 class="mb-0">2</h2>
                                    <strong>Active</strong>
                                </div>

                            </div>

                            <a href="#">
                                <i class="material-icons text-muted">more_horiz</i>
                            </a>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <div class="d-flex justify-content-between">
                                    <strong>Social Media API</strong>
                                    <small class="text-muted">due in 12 days</small>
                                </div>

                                <div class="progress mt-2" style="height:4px;">
                                    <div class="progress-bar bg-warning"
                                         style="width:20%">
                                    </div>
                                </div>

                            </div>

                            <div>

                                <div class="d-flex justify-content-between">
                                    <strong>Advertising Platform</strong>
                                    <small class="text-muted">due in 30 days</small>
                                </div>

                                <div class="progress mt-2" style="height:4px;">
                                    <div class="progress-bar bg-success"
                                         style="width:100%">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    @endif
@endsection
