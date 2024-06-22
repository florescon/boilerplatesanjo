<?php

use App\Http\Controllers\ServiceTypeController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'servicetype',
    'as' => 'servicetype.',
], function () {
    Route::get('/', [ServiceTypeController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.store.index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Service Type Management'), route('admin.servicetype.index'));
        });

    Route::get('deleted', [ServiceTypeController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.store.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.servicetype.index')
                ->push(__('Deleted services type'), route('admin.servicetype.deleted'));
        });
});