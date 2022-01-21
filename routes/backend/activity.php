<?php

use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'activity',
    'as' => 'activity.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', function () {
            return view('backend.activity.index');
        })->name('index')
        // ->middleware('permission:admin.access.line.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Activity panel'), route('admin.activity.index'));
        });
});
