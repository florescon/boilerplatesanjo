<?php

use App\Http\Controllers\BrandController;
use App\Models\Brand;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'brand',
    'as' => 'brand.',
], function () {
    Route::get('/', [BrandController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.brand.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Brand Management'), route('admin.brand.index'));
        });

    Route::group(['prefix' => '{brand}'], function () {
        Route::get('associates', [BrandController::class, 'associates'])
            ->name('associates')
            ->middleware('permission:admin.access.brand.modify')
            ->breadcrumbs(function (Trail $trail, Brand $brand) {
                $trail->parent('admin.brand.index', $brand)
                    ->push(__('Associates of').' '.$brand->name, route('admin.brand.associates', $brand));
            });
    });

    Route::get('deleted', [BrandController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.brand.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.brand.index')
                ->push(__('Deleted brands'), route('admin.brand.deleted'));
        });

});

Route::get('select2-load-brand', [BrandController::class, 'select2LoadMore'])->name('brand.select');