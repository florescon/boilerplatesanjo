<?php

use App\Http\Controllers\ServiceOrderController;
use App\Models\ServiceOrder;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'serviceorder',
    'as' => 'serviceorder.',
], function () {
    Route::get('/', [ServiceOrderController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.order.print_service_order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Service Order Management'), route('admin.serviceorder.index'));
        });

    Route::get('printexportserviceorder/{dateInput?}/{dateOutput?}/{personal?}', [ServiceOrderController::class, 'printexportserviceorder'])
        ->name('printexportserviceorder')
        ->middleware('permission:admin.access.dashboard.information')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.serviceorder.index')
                ->push(__('Print quantities'), route('admin.serviceorder.printexportserviceorder', [$dateInput ?? false, $dateOutput ?? false, $personal ?? false]));
        });


    Route::get('deleted', [ServiceOrderController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.order.create_service_order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.serviceorder.index')
                ->push(__('Deleted services orders'), route('admin.serviceorder.deleted'));
        });
});