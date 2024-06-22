<?php

use App\Http\Controllers\ImageController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'image',
    'as' => 'image.',
], function () {
    Route::get('/', [ImageController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.store.index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Service Type Management'), route('admin.image.index'));
        });

    Route::get('deleted', [ImageController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.store.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.image.index')
                ->push(__('Deleted services type'), route('admin.image.deleted'));
        });
});