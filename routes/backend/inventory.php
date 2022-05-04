<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\InventoryController;
use App\Models\Session;
use App\Models\Inventory;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'inventory',
    'as' => 'inventory.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [SessionController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.inventory.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Inventories'), route('admin.inventory.index'));
        });

    Route::group([
        'prefix' => 'stock',
        'as' => 'stock.',
    ], function () {
        Route::get('/', [SessionController::class, 'stock'])
            ->name('index')
            ->middleware('permission:admin.access.inventory.stock')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.inventory.index')
                    ->push(__('Stock inventory'), route('admin.inventory.stock.index'));
            });
    });

    Route::group([
        'prefix' => 'feedstock',
        'as' => 'feedstock.',
    ], function () {
        Route::get('/', [SessionController::class, 'feedstock'])
            ->name('index')
            ->middleware('permission:admin.access.inventory.feedstock')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.inventory.index')
                    ->push(__('Feedstock inventory'), route('admin.inventory.feedstock.index'));
            });
    });

    Route::group([
        'prefix' => 'store',
        'as' => 'store.',
    ], function () {
        Route::get('/', [SessionController::class, 'store'])
            ->name('index')
            ->middleware('permission:admin.access.inventory.store')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.inventory.index')
                    ->push(__('Store inventory'), route('admin.inventory.store.index'));
            });

        Route::get('history', function () {
                return view('backend.inventories.history-stock');
            })->name('history')
            ->middleware('permission:admin.access.inventory.store')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.inventory.store.index')
                    ->push(__('Store inventory history Management'), route('admin.inventory.store.history'));
            });

        Route::group(['prefix' => '{inventory}'], function () {

            Route::get('show', [InventoryController::class, 'show'])
                ->name('show')
                ->middleware('permission:admin.access.inventory.store')
                ->breadcrumbs(function (Trail $trail, Inventory $inventory) {
                    $trail->parent('admin.inventory.store.history')
                        ->push(__('Show store inventory').': #'.$inventory->id, route('admin.inventory.store.show', $inventory));
                });
        });

    });
});
