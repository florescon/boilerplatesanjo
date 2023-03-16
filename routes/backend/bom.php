<?php

use Tabuna\Breadcrumbs\Trail;
use App\Http\Controllers\BomController;

Route::group([
    'prefix' => 'bom',
    'as' => 'bom.',
], function () {
    Route::get('/', [BomController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.bom.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Bill of Materials Management'), route('admin.bom.index'));
        });
});
