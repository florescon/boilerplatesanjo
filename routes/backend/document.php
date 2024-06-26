<?php

use App\Http\Controllers\DocumentController;
use App\Models\Document;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'document',
    'as' => 'document.',
], function () {
    Route::get('/', [DocumentController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.document.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Document Management'), route('admin.document.index'));
        });

    Route::group(['prefix' => '{document}'], function () {

        Route::get('threads', [DocumentController::class, 'threads'])
            ->name('threads')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail, Document $document) {
                $trail->parent('admin.document.index')
                    ->push(__('Threads'), route('admin.document.threads', $document));
            });


        Route::get('print', [DocumentController::class, 'print'])
            ->name('print');

        Route::get('download_dst', [DocumentController::class, 'download_dst'])
            ->name('download_dst');

        Route::get('download_emb', [DocumentController::class, 'download_emb'])
            ->name('download_emb');

        Route::get('download_pdf', [DocumentController::class, 'download_pdf'])
            ->name('download_pdf');
    });
});
