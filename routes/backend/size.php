<?php

use App\Http\Controllers\SizeController;
use App\Models\Size;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'size',
    'as' => 'size.',
], function () {
    Route::get('/', [SizeController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.size.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Size Management'), route('admin.size.index'));
        });

    Route::get('deleted', [SizeController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.size.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.size.index')
                ->push(__('Deleted sizes'), route('admin.size.deleted'));
        });

    Route::group(['prefix' => '{size}'], function () {
        Route::get('associates_sub', [SizeController::class, 'associates_sub'])
            ->name('associates_sub')
            ->middleware('permission:admin.access.size.modify')
            ->breadcrumbs(function (Trail $trail, Size $size) {
                $trail->parent('admin.size.index', $size)
                    ->push(__('Associated subproducts of').' '.$size->name, route('admin.size.associates_sub', $size));
            });

        Route::get('associates', [SizeController::class, 'associates'])
            ->name('associates')
            ->middleware('permission:admin.access.size.modify')
            ->breadcrumbs(function (Trail $trail, Size $size) {
                $trail->parent('admin.size.index', $size)
                    ->push(__('Associates of').' '.$size->name, route('admin.size.associates', $size));
            });
    });
});

Route::get('select2-load-size', [SizeController::class, 'select2LoadMore'])->name('size.select');