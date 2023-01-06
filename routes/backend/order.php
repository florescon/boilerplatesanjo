<?php

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Status;
use App\Models\Ticket;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'order',
    'as' => 'order.',
], function () {
    Route::get('/', [OrderController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Order - Sale Management'), route('admin.order.index'));
        });
    Route::get('suborders', [OrderController::class, 'suborders_list'])
        ->name('suborders')
        ->middleware('permission:admin.access.order.suborders')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Suborders'), route('admin.order.suborders'));
        });
    Route::get('sales', [OrderController::class, 'sales_list'])
        ->name('sales')
        ->middleware('permission:admin.access.order.sales')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Sales'), route('admin.order.sales'));
        });
    Route::get('mix', [OrderController::class, 'mix_list'])
        ->name('mix')
        ->middleware('permission:admin.access.order.order-sales')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Mix'), route('admin.order.mix'));
        });
    Route::get('all', [OrderController::class, 'all_list'])
        ->name('all')
        ->middleware('permission:admin.access.order.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.suborders')
                ->push(__('All orders'), route('admin.order.all'));
        });

    Route::get('createsuborder', [OrderController::class, 'createsuborder'])
        ->name('createsuborder')
        ->middleware('permission:admin.access.order.suborders')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Create suborder output'), route('admin.order.createsuborder'));
        });

    Route::get('deleted', [OrderController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.order.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Deleted products'), route('admin.order.deleted'));
        });

    Route::group(['prefix' => '{order}'], function () {
        Route::get('edit', [OrderController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent($order->from_store ? 'admin.store.all.index' : 'admin.order.index')
                    ->push(__('Edit'), route('admin.order.edit', $order));
            });

        Route::get('whereIs', [OrderController::class, 'where_is_products'])
            ->name('whereIs')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Where is products?'), route('admin.order.whereIs', $order));
            });

        Route::post('end-add-stock', [OrderController::class, 'end_add_stock'])
            ->name('end-add-stock');
            // ->middleware('permission:admin.order.end-add-stock');

        Route::post('delete-consumption', [OrderController::class, 'delete_consumption'])
            ->name('delete-consumption');
            // ->middleware('permission:admin.order.end-add-stock');

        Route::post('reasign-user-departament', [OrderController::class, 'reasign_user_departament'])
            ->name('reasign-user-departament');
            // ->middleware('permission:admin.order.end-add-stock');

        Route::get('print', [OrderController::class, 'print'])
            ->name('print')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Print order'), route('admin.order.print', $order));
            });

        Route::get('ticket', [OrderController::class, 'ticket'])
            ->name('ticket')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket', $order));
            });

        Route::get('ticket_order', [OrderController::class, 'ticket_order'])
            ->name('ticket_order')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket_order', $order));
            });

        Route::get('ticket_monitoring', [OrderController::class, 'ticket_monitoring'])
            ->name('ticket_monitoring')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Monitoring dashboard ticket'), route('admin.order.ticket_monitoring', $order));
            });

        Route::get('ticket_materia', [OrderController::class, 'ticket_materia'])
            ->name('ticket_materia')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket_materia', $order));
            });

        Route::get('sub', [OrderController::class, 'suborders'])
            ->name('sub')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Suborders'), route('admin.order.sub', $order));
            });

        Route::get('advanced', [OrderController::class, 'advanced'])
            ->name('advanced')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Advanced options'), route('admin.order.advanced', $order));
            });

        Route::get('assignments/{status}', [OrderController::class, 'assignments'])
            ->name('assignments')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order, Status $status) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Assignments').' - '.$status->name, route('admin.order.assignments', [$order, $status]));
            });

        Route::get('ticket_assignment/{ticket}', [OrderController::class, 'ticket_assignment'])
            ->name('ticket_assignment')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Ticket $ticket) {
                $trail->parent('admin.order.edit', $ticket)
                    ->push(__('Ticket assignment').' '.$ticket->id, route('admin.order.ticket_assignment', [$order, $ticket]));
            });

        Route::get('records', [OrderController::class, 'records'])
            ->name('records')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Status records'), route('admin.order.records', $order));
            });

        Route::get('records_delivery', [OrderController::class, 'records_delivery'])
            ->name('records_delivery')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Status records delivery'), route('admin.order.records_delivery', $order));
            });

        Route::get('records_payment', [OrderController::class, 'records_payment'])
            ->name('records_payment')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Status records payment'), route('admin.order.records_payment', $order));
            });

        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
    });

});