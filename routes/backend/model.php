<?php

use App\Http\Controllers\ModelProductController;
use App\Models\ModelProduct;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'model',
    'as' => 'model.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [ModelProductController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.model.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Model Management'), route('admin.model.index'));
        });

    Route::get('deleted', [ModelProductController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.model.index')
                ->push(__('Deleted models'), route('admin.model.deleted'));
        });

    Route::group(['prefix' => '{model}'], function () {
        Route::get('associates', [ModelProductController::class, 'associates'])
            ->name('associates')
            ->breadcrumbs(function (Trail $trail, ModelProduct $model) {
                $trail->parent('admin.model.index', $model)
                    ->push(__('Associates of').' '.$model->name, route('admin.model.associates', $model));
            });
    });
});

Route::get('select2-load-model', [ModelProductController::class, 'select2LoadMore'])->name('model.select');
