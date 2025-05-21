<?php

use App\Http\Controllers\ProductionOrderController;
use App\Models\ProductionOrder;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'production',
    'as' => 'production.',
], function () {
    Route::get('/', [ProductionOrderController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.order.print_service_order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Service Order Management'), route('admin.production.index'));
        });

    Route::get('printexportserviceorder/{dateInput?}/{dateOutput?}/{personal?}/{grouped?}', [ProductionOrderController::class, 'printexportserviceorder'])
        ->name('printexportserviceorder')
        ->middleware('permission:admin.access.dashboard.information')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.production.index')
                ->push(__('Print quantities'), route('admin.production.printexportserviceorder', [$dateInput ?? false, $dateOutput ?? false, $personal ?? false, $grouped ?? false]));
        });


    Route::get('deleted', [ProductionOrderController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.order.create_service_order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.production.index')
                ->push(__('Deleted services orders'), route('admin.production.deleted'));
        });
});