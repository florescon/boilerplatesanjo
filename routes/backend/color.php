<?php

use App\Http\Controllers\ColorController;
use App\Models\Color;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'color',
    'as' => 'color.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [ColorController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.color.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Color Management'), route('admin.color.index'));
        });

    Route::group(['prefix' => '{color}'], function () {
        Route::get('associates_sub', [ColorController::class, 'associates_sub'])
            ->name('associates_sub')
            ->breadcrumbs(function (Trail $trail, Color $color) {
                $trail->parent('admin.color.index', $color)
                    ->push(__('Associated subproducts of').' '.$color->name, route('admin.color.associates_sub', $color));
            });

        Route::get('associates', [ColorController::class, 'associates'])
            ->name('associates')
            ->breadcrumbs(function (Trail $trail, Color $color) {
                $trail->parent('admin.color.index', $color)
                    ->push(__('Associates of').' '.$color->name, route('admin.color.associates', $color));
            });
    });
});

Route::get('select2-load-color', [ColorController::class, 'select2LoadMore'])->name('color.select');
