<?php

use App\Http\Controllers\DocumentController;
use App\Models\Document;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'document',
    'as' => 'document.',
    'middleware' => config('boilerplate.access.middleware.confirm'),
], function () {
    Route::get('/', [DocumentController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.document.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Document Management'), route('admin.document.index'));
        });

});
