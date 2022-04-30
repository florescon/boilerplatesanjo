<?php

use App\Http\Controllers\SessionController;
use App\Models\Session;
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

    Route::get('stock', [SessionController::class, 'stock'])
        ->name('stock')
        ->middleware('permission:admin.access.inventory.stock')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.inventory.index')
                ->push(__('Stock inventory'), route('admin.inventory.stock'));
        });

    Route::get('feedstock', [SessionController::class, 'feedstock'])
        ->name('feedstock')
        ->middleware('permission:admin.access.inventory.feedstock')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.inventory.index')
                ->push(__('Feedstock inventory'), route('admin.inventory.feedstock'));
        });

    Route::get('store', [SessionController::class, 'store'])
        ->name('store')
        ->middleware('permission:admin.access.inventory.store')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.inventory.index')
                ->push(__('Store inventory'), route('admin.inventory.store'));
        });
});
