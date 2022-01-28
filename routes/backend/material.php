<?php

use App\Http\Controllers\MaterialController;
use App\Models\Material;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'material',
    'as' => 'material.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [MaterialController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.material.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Feedstock Management'), route('admin.material.index'));
        });

    Route::get('records', [MaterialController::class, 'recordsMaterial'])
        ->name('records')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Records of feedstock'), route('admin.material.records'));
        });

    Route::group(['prefix' => '{material}'], function () {
        Route::get('edit', [MaterialController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Material $material) {
                $trail->parent('admin.material.index')
                    ->push(__('Edit feedstock'), route('admin.material.edit', $material));
            });

        Route::patch('/', [MaterialController::class, 'update'])->name('update');
        // Route::delete('/', [UserController::class, 'destroy'])->name('destroy');
    });


    Route::patch('material', [MaterialController::class, 'updateStock'])
        ->name('updateStock');

    Route::get('deleted', [MaterialController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.material.index')
                ->push(__('Deleted feedstocks'), route('admin.material.deleted'));
        });
});

Route::get('select2-load-material', [MaterialController::class, 'select2LoadMore'])->name('material.select');