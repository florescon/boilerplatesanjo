<?php

use App\Http\Controllers\StatusController;
use App\Models\Status;
use App\Models\Process;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'status',
    'as' => 'status.',
], function () {
    Route::get('/', [StatusController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.states_production.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Status Management'), route('admin.status.index'));
        });

    Route::get('process', [StatusController::class, 'process'])
        ->name('process')
        ->middleware('permission:admin.access.states_production.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Status Management'), route('admin.status.process'));
        });


    Route::group(['prefix' => '{status}'], function () {
        Route::get('edit', [StatusController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.states_production.modify')
            ->breadcrumbs(function (Trail $trail, Process $status) {
                $trail->parent('admin.status.process')
                    ->push(__('Edit'), route('admin.status.edit', $status));
            });

        Route::get('assignments', [StatusController::class, 'assignments'])
            ->name('assignments')
            ->middleware('permission:admin.access.states_production.modify')
            ->breadcrumbs(function (Trail $trail, Status $status) {
                $trail->parent('admin.status.index')
                    ->push(__('Edit'), route('admin.status.assignments', $status));
            });
    });

    Route::get('deleted', [StatusController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.states_production.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.status.index')
                ->push(__('Deleted statuses'), route('admin.status.deleted'));
        });
});

Route::get('select2-load-status', [StatusController::class, 'select2LoadMore'])->name('status.select');
