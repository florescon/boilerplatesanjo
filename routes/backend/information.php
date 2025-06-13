<?php

use Tabuna\Breadcrumbs\Trail;
use App\Models\Status;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\OrderController;

Route::group([
    'prefix' => 'information',
    'as' => 'information.',
], function () {
    Route::get('/', function () {
            return view('backend.information.index');
        })->name('index')
        ->middleware('permission:admin.access.dashboard.information')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Information Management'), route('admin.information.index'));
        });

    Route::get('chart', [OrderController::class, 'chart'])->name('chart')
        ->middleware('permission:admin.access.dashboard.information')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.information.index')
                ->push(__('Charts'), route('admin.information.index'));
        });

    Route::group([
        'prefix' => 'status',
        'as' => 'status.',
    ], function () {

        Route::group(['prefix' => '{status}', 
            'middleware' => 'permission:admin.access.dashboard.information'
        ], function () {

            Route::get('ticket_materia', [StatusController::class, 'ticket_materia'])
                ->name('ticket_materia')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.index', $status)
                        ->push(__('Ticket Feedstock'), route('admin.information.status.ticket_materia', $status));
                });

            Route::get('pending_materia', [StatusController::class, 'pending_materia'])
                ->name('pending_materia')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.index', $status)
                        ->push(__('Pending materia'), route('admin.information.status.pending_materia', $status));
                });

            Route::get('pending_materia_grouped/{additional?}', [StatusController::class, 'pending_materia_grouped'])
                ->name('pending_materia_grouped')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.index', $status)
                        ->push(__('Pending materia'), route('admin.information.status.pending_materia_grouped', [$status, $additional ?? false]));
                });

            Route::get('pending_vendor', [StatusController::class, 'pending_vendor'])
                ->name('pending_vendor')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.index', $status)
                        ->push(__('Pending vendor'), route('admin.information.status.pending_vendor', $status));
                });

            Route::get('pending_vendor_grouped/{additional?}', [StatusController::class, 'pending_vendor_grouped'])
                ->name('pending_vendor_grouped')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.index', $status)
                        ->push(__('Vendor grouped'), route('admin.information.status.pending_vendor_grouped', [$status, $additional ?? false]));
                });

            Route::get('add_to_vendor', [StatusController::class, 'add_to_vendor'])
                ->name('add_to_vendor')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.status.show', $status)
                        ->push(__('Add to vendor'), route('admin.information.status.add_to_vendor', $status));
                });

            Route::get('add_to_materia', [StatusController::class, 'add_to_materia'])
                ->name('add_to_materia')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.status.show', $status)
                        ->push(__('Add to materia'), route('admin.information.status.add_to_materia', $status));
                });

            Route::get('show', [StatusController::class, 'showInformation'])
                ->name('show')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.information.index', $status)
                        ->push(__('Information').' - '.$status->name, route('admin.information.status.show', $status));
                });

            Route::get('printexportquantities/{grouped?}', [StatusController::class, 'printexportquantities'])
                ->name('printexportquantities')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.order.index', $status)
                        ->push(__('Print quantities'), route('admin.order.printexportquantities', [$status, $grouped ?? false]));
                });


            Route::get('printexportquantitiesall/{grouped?}/{allStatus?}', [StatusController::class, 'printexportquantities'])
                ->name('printexportquantitiesall')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail) {
                    $trail->parent('admin.order.index')
                        ->push(__('Print quantities'), route('admin.order.printexportquantitiesall', [$grouped ?? false, $allStatus ?? false]));
                });

            Route::get('printexporthistory/{grouped?}/{dateInput?}/{dateOutput?}/{personal?}', [StatusController::class, 'printexporthistory'])
                ->name('printexporthistory')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.order.index', $status)
                        ->push(__('Print quantities'), route('admin.order.printexporthistory', [$status, $grouped ?? false, $dateInput ?? false, $dateOutput ?? false, $personal ?? false]));
                });

            Route::get('printexportreceived/{grouped?}/{dateInput?}/{dateOutput?}/{personal?}', [StatusController::class, 'printexportreceived'])
                ->name('printexportreceived')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.order.index', $status)
                        ->push(__('Print quantities received'), route('admin.order.printexportreceived', [$status, $grouped ?? false, $dateInput ?? false, $dateOutput ?? false, $personal ?? false]));
                });



            Route::get('printexportreceivedproduction/{grouped?}/{dateInput?}/{dateOutput?}/{personal?}', [StatusController::class, 'printexportreceivedproduction'])
                ->name('printexportreceivedproduction')
                ->middleware('permission:admin.access.dashboard.information')
                ->breadcrumbs(function (Trail $trail, Status $status) {
                    $trail->parent('admin.order.index', $status)
                        ->push(__('Print quantities received'), route('admin.order.printexportreceivedproduction', [$status, $grouped ?? false, $dateInput ?? false, $dateOutput ?? false, $personal ?? false]));
                });


        });

    });

});