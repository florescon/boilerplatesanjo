<?php

use App\Http\Controllers\DepartamentController;
use App\Models\Departament;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'departament',
    'as' => 'departament.',
], function () {
    Route::get('/', [DepartamentController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.departament.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Departament Management'), route('admin.departament.index'));
        });

    Route::get('create', [DepartamentController::class, 'create'])
        ->name('create')
        ->middleware('permission:admin.access.departament.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.departament.index')
                ->push(__('Create departament'), route('admin.departament.create'));
        });
});

Route::get('select2-load-departament', [DepartamentController::class, 'select2LoadMore'])->name('departament.select');