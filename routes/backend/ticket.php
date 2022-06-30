<?php

use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use Tabuna\Breadcrumbs\Trail;
use App\Domains\Auth\Models\User;

Route::group([
    'prefix' => 'ticket',
    'as' => 'ticket.',
], function () {

    Route::get('/', [TicketController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Ticket Management'), route('admin.ticket.index'));
        });

    Route::group(['prefix' => '{user}'], function () {
        Route::get('history', [TicketController::class, 'history'])
            ->name('history')
        ->middleware('permission:admin.access.order.order')
            ->breadcrumbs(function (Trail $trail, User $user) {
                $trail->parent('admin.ticket.index')
                    ->push(__('Assignment history'), route('admin.ticket.history', $user));
            });
   });

    Route::group(['prefix' => '{ticket}'], function () {
        Route::delete('/', [TicketController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [TicketController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.ticket.index')
                ->push(__('Deleted tickets'), route('admin.ticket.deleted'));
        });
});
