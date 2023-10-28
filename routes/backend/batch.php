<?php

use App\Http\Controllers\BatchController;
use App\Models\Batch;
use Tabuna\Breadcrumbs\Trail;
use App\Domains\Auth\Models\User;

Route::group([
    'prefix' => 'batch',
    'as' => 'batch.',
], function () {

    Route::get('/', [BatchController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Batch Management'), route('admin.batch.index'));
        });

    Route::get('conformed', [BatchController::class, 'index_conformed'])
        ->name('conformed')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Batch Management'), route('admin.batch.conformed'));
        });

    Route::get('manufacturing', [BatchController::class, 'index_manufacturing'])
        ->name('manufacturing')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Batch Management'), route('admin.batch.manufacturing'));
        });

    Route::get('personalization', [BatchController::class, 'index_personalization'])
        ->name('personalization')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Batch Management'), route('admin.batch.personalization'));
        });

    Route::get('shipment', [BatchController::class, 'index_shipment'])
        ->name('shipment')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Batch Management'), route('admin.batch.shipment'));
        });

    Route::group(['prefix' => '{batch}'], function () {
        Route::delete('/', [BatchController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [BatchController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.batch.index')
                ->push(__('Deleted batches'), route('admin.batch.deleted'));
        });
});
