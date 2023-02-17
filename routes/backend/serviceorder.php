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
        ->middleware('permission:admin.access.service.index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Service Order Management'), route('admin.serviceorder.index'));
        });

    Route::get('deleted', [ServiceOrderController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.service.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.serviceorder.index')
                ->push(__('Deleted services orders'), route('admin.serviceorder.deleted'));
        });
});