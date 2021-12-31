<?php

use App\Http\Controllers\LoggedController;
use App\Models\Logged;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'logged',
    'as' => 'logged.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [LoggedController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.logged.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Logged Management'), route('admin.logged.index'));
        });
});