<?php

use App\Http\Controllers\ThreadController;
use App\Models\Thread;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'thread',
    'as' => 'thread.',
], function () {
    Route::get('/', [ThreadController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Thread Management'), route('admin.thread.index'));
        });

    Route::get('deleted', [ThreadController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.thread.index')
                ->push(__('Deleted Threads'), route('admin.thread.deleted'));
        });
});

Route::get('select2-load-thread', [ThreadController::class, 'select2LoadMore'])->name('thread.select');
