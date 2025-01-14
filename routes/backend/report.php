<?php

use Tabuna\Breadcrumbs\Trail;
use App\Http\Controllers\ReportController;

Route::group([
    'prefix' => 'report',
    'as' => 'report.',
], function () {
    Route::get('/', [ReportController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.report.show')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Report Management'), route('admin.report.index'));
        });

});
