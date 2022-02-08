<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'service',
    'as' => 'service.',
], function () {
    Route::get('/', [ProductController::class, 'indexService'])
        ->name('index')
        ->middleware('permission:admin.access.service.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Service Management'), route('admin.service.index'));
        });

    Route::get('records', [ProductController::class, 'recordsService'])
        ->name('records')
        ->middleware('permission:admin.access.service.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.service.index')
                ->push(__('Service records'), route('admin.service.records'));
        });

    Route::get('deleted', [ProductController::class, 'deletedService'])
        ->name('deleted')
        ->middleware('permission:admin.access.service.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.service.index')
                ->push(__('Deleted services'), route('admin.service.deleted'));
        });
});