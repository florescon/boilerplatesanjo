<?php

use App\Http\Controllers\Backend\DashboardController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });

Route::get('kpis', [DashboardController::class, 'kpis'])
    ->name('kpis')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.kpis'));
    });

Route::get('graph_production', [DashboardController::class, 'graph_production'])
    ->name('graph_production')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_production'));
    });

Route::get('graph_print', [DashboardController::class, 'graph_print'])
    ->name('graph_print')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_print'));
    });

Route::get('graph_material', [DashboardController::class, 'graph_material'])
    ->name('graph_material')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_material'));
    });

Route::get('graph_efficiency', [DashboardController::class, 'graph_efficiency'])
    ->name('graph_efficiency')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_efficiency'));
    });

Route::get('graph_by_product', [DashboardController::class, 'graph_by_product'])
    ->name('graph_by_product')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_by_product'));
    });

Route::get('graph_flagship_product', [DashboardController::class, 'graph_flagship_product'])
    ->name('graph_flagship_product')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_flagship_product'));
    });

Route::get('graph_comparative', [DashboardController::class, 'graph_comparative'])
    ->name('graph_comparative')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_comparative'));
    });

Route::get('graph_projection', [DashboardController::class, 'graph_projection'])
    ->name('graph_projection')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_projection'));
    });


Route::get('graph_bottlenecks', [DashboardController::class, 'graph_bottlenecks'])
    ->name('graph_bottlenecks')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_bottlenecks'));
    });

Route::get('graph_quality', [DashboardController::class, 'graph_quality'])
    ->name('graph_quality')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_quality'));
    });

Route::get('graph_observations', [DashboardController::class, 'graph_observations'])
    ->name('graph_observations')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_observations'));
    });

Route::get('graph_costs', [DashboardController::class, 'graph_costs'])
    ->name('graph_costs')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_costs'));
    });

Route::get('graph_labour', [DashboardController::class, 'graph_labour'])
    ->name('graph_labour')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_labour'));
    });

Route::get('graph_profitability', [DashboardController::class, 'graph_profitability'])
    ->name('graph_profitability')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_profitability'));
    });

Route::get('graph_by_order', [DashboardController::class, 'graph_by_order'])
    ->name('graph_by_order')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_by_order'));
    });

Route::get('graph_oee', [DashboardController::class, 'graph_oee'])
    ->name('graph_oee')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_oee'));
    });

Route::get('graph_tendencies', [DashboardController::class, 'graph_tendencies'])
    ->name('graph_tendencies')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_tendencies'));
    });

Route::get('graph_ranking', [DashboardController::class, 'graph_ranking'])
    ->name('graph_ranking')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.graph_ranking'));
    });

Route::get('dashboard_old', [DashboardController::class, 'index_old'])
    ->name('dashboard_old')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard_old'));
    });
