<?php

use App\Http\Controllers\VendorController;
use App\Models\Vendor;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'vendor',
    'as' => 'vendor.',
], function () {
    Route::get('/', [VendorController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.vendor.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Vendor Management'), route('admin.vendor.index'));
        });

    Route::get('create', [VendorController::class, 'create'])
        ->name('create')
        ->middleware('permission:admin.access.vendor.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.vendor.index')
                ->push(__('Create vendor'), route('admin.vendor.create'));
        });

    Route::get('deleted', [VendorController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.vendor.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.vendor.index')
                ->push(__('Deleted vendors'), route('admin.vendor.deleted'));
        });

    Route::group(['prefix' => '{vendor}'], function () {

        Route::get('edit', [VendorController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.vendor.modify')
            ->breadcrumbs(function (Trail $trail, Vendor $vendor) {
                $trail->parent('admin.vendor.index')
                    ->push(__('Edit vendor'), route('admin.vendor.edit', $vendor));
            });

        Route::patch('/', [VendorController::class, 'update'])->name('update');

    });

    Route::group(['prefix' => '{vendor}'], function () {
        Route::get('associates', [VendorController::class, 'associates'])
            ->name('associates')
            ->middleware('permission:admin.access.vendor.modify')
            ->breadcrumbs(function (Trail $trail, Vendor $vendor) {
                $trail->parent('admin.vendor.index', $vendor)
                    ->push(__('Associates of').' '.$vendor->name, route('admin.vendor.associates', $vendor));
            });
    });

    Route::group(['prefix' => '{vendor}'], function () {
        Route::get('associates_materia', [VendorController::class, 'associates_materia'])
            ->name('associates_materia')
            ->middleware('permission:admin.access.vendor.modify')
            ->breadcrumbs(function (Trail $trail, Vendor $vendor) {
                $trail->parent('admin.vendor.index', $vendor)
                    ->push(__('Associates of').' '.$vendor->name, route('admin.vendor.associates_materia', $vendor));
            });
    });

    Route::get('deleted', [VendorController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.vendor.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.vendor.index')
                ->push(__('Deleted vendors'), route('admin.vendor.deleted'));
        });

});

Route::get('select2-load-vendor', [VendorController::class, 'select2LoadMore'])->name('vendor.select');