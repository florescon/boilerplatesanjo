<?php

use App\Http\Controllers\MaterialController;
use App\Models\Material;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'material',
    'as' => 'material.',
], function () {
    Route::get('/', [MaterialController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.material.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Feedstock Management'), route('admin.material.index'));
        });

    Route::get('create', [MaterialController::class, 'create'])
        ->name('create')
        ->middleware('permission:admin.access.material.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Create feedstock'), route('admin.material.create'));
        });

    Route::get('out', [MaterialController::class, 'out'])
        ->name('out')
        ->middleware('permission:admin.access.material.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Warehouse relaese form'), route('admin.material.out'));
        });

    Route::get('out_history', function () {
            return view('backend.material.out-history');
        })->name('out_history')
        ->middleware('permission:admin.access.material.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.out')
                ->push(__('Warehouse relaese form Management'), route('admin.material.out_history'));
        });

    Route::get('ticket_out/{out}', [MaterialController::class, 'ticket_out'])
        ->name('ticket_out')
        ->middleware('permission:admin.access.material.list')
        ->breadcrumbs(function (Trail $trail, Ticket $out) {
            $trail->parent('backend.material.out_history')
                ->push(__('Ticket assignment').' '.$out->id, route('admin.material.ticket_out', $out));
        });

    Route::get('records', [MaterialController::class, 'recordsMaterial'])
        ->name('records')
        ->middleware('permission:admin.access.material.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Records of feedstock'), route('admin.material.records'));
        });

    Route::get('records_history', [MaterialController::class, 'recordsHistoryMaterial'])
        ->name('records_history')
        ->middleware('permission:admin.access.material.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Records of history feedstock'), route('admin.material.records_history'));
        });

    Route::get('records_history_group', [MaterialController::class, 'recordsHistoryMaterialGroup'])
        ->name('records_history_group')
        ->middleware('permission:admin.access.material.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Records of history feedstock'), route('admin.material.records_history_group'));
        });

    Route::group(['prefix' => '{material}'], function () {
        Route::get('edit', [MaterialController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.material.modify')
            ->breadcrumbs(function (Trail $trail, Material $material) {
                $trail->parent('admin.material.index')
                    ->push(__('Edit feedstock'), route('admin.material.edit', $material));
            });

        Route::get('print', [MaterialController::class, 'print'])
            ->name('print')
            ->middleware('permission:admin.access.material.modify')
            ->breadcrumbs(function (Trail $trail, Material $material) {
                $trail->parent('admin.material.index')
                    ->push(__('Print feedstock'), route('admin.material.print', $material));
            });

        Route::get('t/{quantity?}', [MaterialController::class, 'short_ticket'])
            ->name('t')
            ->breadcrumbs(function (Trail $trail, Material $material) {
                $trail->parent('admin.material.index', $material)
                    ->push(__('Short ticket'), route('admin.material.t', [$material, $quantity]));
            });

        Route::patch('/', [MaterialController::class, 'update'])->name('update');
        // Route::delete('/', [UserController::class, 'destroy'])->name('destroy');

        Route::patch('updateStock', [MaterialController::class, 'updateStock'])
            ->name('updateStock');

        Route::patch('updatePrice', [MaterialController::class, 'updatePrice'])
            ->name('updatePrice');
    });

    Route::get('deleted', [MaterialController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.material.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Deleted feedstocks'), route('admin.material.deleted'));
        });
});

Route::get('select2-load-material', [MaterialController::class, 'select2LoadMore'])->name('material.select');

Route::get('select2-load-material-thread', [MaterialController::class, 'select2LoadMoreThread'])->name('material.selectthread');

Route::get('search', [MaterialController::class, 'search'])->name('material.search');