<?php

use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'ticket',
    'as' => 'ticket.',
], function () {

    Route::group(['prefix' => '{ticket}'], function () {
        Route::delete('/', [TicketController::class, 'destroy'])->name('destroy');
    });

});
