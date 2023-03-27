<?php

use App\Http\Controllers\FamilyController;
use App\Models\Family;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'family',
    'as' => 'family.',
], function () {
    Route::get('/', [FamilyController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.family.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Brand Management'), route('admin.family.index'));
        });

    Route::group(['prefix' => '{family}'], function () {
        Route::get('associates', [FamilyController::class, 'associates'])
            ->name('associates')
            ->middleware('permission:admin.access.family.modify')
            ->breadcrumbs(function (Trail $trail, Family $family) {
                $trail->parent('admin.family.index', $family)
                    ->push(__('Associates of').' '.$family->name, route('admin.family.associates', $family));
            });
    });

    Route::get('deleted', [FamilyController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.family.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.family.index')
                ->push(__('Deleted families'), route('admin.family.deleted'));
        });

});

Route::get('select2-load-family', [FamilyController::class, 'select2LoadMore'])->name('family.select');