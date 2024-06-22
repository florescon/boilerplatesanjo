<?php

use App\Http\Controllers\StationController;
use App\Models\Station;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'station',
    'as' => 'station.',
], function () {

    Route::get('/', [StationController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Station Management'), route('admin.station.index'));
        });

    Route::group(['prefix' => '{station}'], function () {
        Route::get('edit', [StationController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Station $station) {
                $trail->parent('admin.station.index')
                    ->push(__('Edit'), route('admin.station.edit', $station));
            });


        Route::get('ticket/', [StationController::class, 'ticket'])
            ->name('ticket')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Station $station) {
                $trail->parent('admin.station.index')
                    ->push(__('Station assignment').' '.$station->id, route('admin.order.ticket', $station));
            });

        Route::delete('/', [StationController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [StationController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.station.index')
                ->push(__('Deleted stations'), route('admin.station.deleted'));
        });
});
