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
                ->push(__('Bom of Materials Management'), route('admin.bom.index'));
        });

    Route::get('ticket_bom/{materials?}', [BomController::class, 'ticket_bom'])
        ->name('ticket_bom')
        ->middleware('permission:admin.access.bom.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.bom.index')
                ->push(__('Bill of Materials'), route('admin.order.ticket_bom', $materials ?? null));
        });

});
