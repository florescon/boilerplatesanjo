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
        ->middleware('permission:admin.access.station.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Station Management'), route('admin.station.index'));
        });

    Route::group(['prefix' => '{station}'], function () {
        Route::get('edit', [StationController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.station.modify')
            ->breadcrumbs(function (Trail $trail, $station) {
                $trail->parent('admin.station.index')
                    ->push(__('Edit'), route('admin.station.edit', $station));
            });


        Route::get('ticket/', [StationController::class, 'ticket'])
            ->name('ticket')
            ->middleware('permission:admin.access.station.list');


        Route::get('output/{grouped?}', [StationController::class, 'output'])
            ->name('output')
            ->middleware('permission:admin.access.station.list');

        Route::get('checklist_details/', [StationController::class, 'checklist_details'])
            ->name('checklist_details')
            ->middleware('permission:admin.access.station.modify')
            ->breadcrumbs(function (Trail $trail, Station $station) {
                $trail->parent('admin.station.index')
                    ->push(__('Edit'), route('admin.station.checklist_details', $station));
            });

        Route::get('checklist/', [StationController::class, 'checklist'])
            ->name('checklist')
            ->middleware('permission:admin.access.station.list')
            ->breadcrumbs(function (Trail $trail, Station $station) {
                $trail->parent('admin.station.index')
                    ->push(__('Checklist Station').' '.$station->id, route('admin.order.checklist', $station));
            });

        Route::get('checklist_ticket/', [StationController::class, 'checklist_ticket'])
            ->name('checklist_ticket')
            ->middleware('permission:admin.access.station.list')
            ->breadcrumbs(function (Trail $trail, Station $station) {
                $trail->parent('admin.station.index')
                    ->push(__('Ticket Consumption').' '.$station->id, route('admin.order.checklist_ticket', $station));
            });

        Route::delete('/', [StationController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [StationController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.station.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.station.index')
                ->push(__('Deleted stations'), route('admin.station.deleted'));
        });
});
