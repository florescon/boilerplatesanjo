<?php

use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'ticket',
    'as' => 'ticket.',
], function () {

    Route::get('/', [TicketController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.ticket.index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Ticket Management'), route('admin.ticket.index'));
        });

    Route::group(['prefix' => '{ticket}'], function () {
        Route::delete('/', [TicketController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [TicketController::class, 'deleted'])
        ->name('deleted')
        // ->middleware('permission:admin.access.ticket.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.ticket.index')
                ->push(__('Deleted tickets'), route('admin.ticket.deleted'));
        });
});
