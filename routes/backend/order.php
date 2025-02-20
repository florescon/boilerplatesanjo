<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceTypeController;
use App\Models\Order;
use App\Models\Status;
use App\Models\Station;
use App\Models\Ticket;
use App\Models\Batch;
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
    Route::get('quotations', [OrderController::class, 'quotations_list'])
        ->name('quotations')
        ->middleware('permission:admin.access.order.order-sales')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Quotations'), route('admin.order.quotations'));
        });
    Route::get('all', [OrderController::class, 'all_list'])
        ->name('all')
        ->middleware('permission:admin.access.order.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.suborders')
                ->push(__('All orders'), route('admin.order.all'));
        });

    Route::get('quotation', function () {
            return view('backend.order.quotation');
        })->name('quotation')
        ->middleware('permission:admin.access.order.quotation')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Quotation Panel Management'), route('admin.order.quotation'));
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

    Route::get('printexportorders/{orders?}', [OrderController::class, 'printexportorders'])
        ->name('printexportorders')
        ->middleware('permission:admin.access.order.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Print orders'), route('admin.order.printexportorders', $orders ?? null));
        });

    Route::get('printexportbydate/{dateInput?}/{dateOutput?}/{summary?}/{isProduct?}/{isService?}', [OrderController::class, 'printexportbydate'])
        ->name('printexportbydate')
        ->middleware('permission:admin.access.order.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Print orders'), route('admin.order.printexportbydate', [$dateInput, $dateOutput, $summary ?? 0, $isProduct ?? 0, $isService ?? 0]));
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

        Route::get('print/{breakdown?}/{grouped?}/{emptyPrices?}', [OrderController::class, 'print'])
            ->name('print')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Print order'), route('admin.order.print', [$order, $breakdown, $grouped, $emptyPrices]));
            });


        Route::get('printgropedwithoutprice', [OrderController::class, 'printgropedwithoutprice'])
            ->name('printgropedwithoutprice')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Print order'), route('admin.order.printgropedwithoutprice'));
            });

        Route::get('report', [OrderController::class, 'report'])
            ->name('report')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.order.index')
                    ->push(__('Report'), route('admin.order.report', $orders));
            });


        Route::get('ticket', [OrderController::class, 'ticket'])
            ->name('ticket')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket', $order));
            });

        Route::get('ticket_order/{breakdown?}/{emptyPrices?}', [OrderController::class, 'ticket_order'])
            ->name('ticket_order')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket_order', [$order, $breakdown, $emptyPrices]));
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


        Route::get('ticket_materia_station/{station}', [OrderController::class, 'ticket_materia_station'])
            ->name('ticket_materia_station')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order, Station $station) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket_materia_station', [$order, $station]));
            });

        Route::get('short_ticket_materia', [OrderController::class, 'short_ticket_materia'])
            ->name('short_ticket_materia')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.short_ticket_materia', $order));
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

        Route::get('print_service_order/{service}', [OrderController::class, 'print_service_order'])
            ->name('print_service_order')
            ->middleware('permission:admin.access.order.print_service_order')
            ->breadcrumbs(function (Trail $trail, Order $order, ServiceOrder $service) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Service Order').' - '.$service->name, route('admin.order.print_service_order', [$order, $service]));
            });

        Route::get('print_service_order_html/{service}', [OrderController::class, 'print_service_order_html'])
            ->name('print_service_order_html')
            ->middleware('permission:admin.access.order.print_service_order')
            ->breadcrumbs(function (Trail $trail, Order $order, ServiceOrder $service) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Service Order').' - '.$service->name, route('admin.order.print_service_order_html', [$order, $service]));
            });

        Route::get('assignments/{status}', [OrderController::class, 'assignments'])
            ->name('assignments')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order, Status $status) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Assignments').' - '.$status->name, route('admin.order.assignments', [$order, $status]));
            });

        Route::get('batches/{status}', [OrderController::class, 'batches'])
            ->name('batches')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order, Status $status) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Batches').' - '.$status->name, route('admin.order.batches', [$order, $status]));
            });


        Route::get('station/{status}', [OrderController::class, 'station'])
            ->name('station')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order, Status $status) {
                $trail->parent('admin.order.edit_chart', $order)
                    ->push(__('Workstations').' - '.$status->name, route('admin.order.station', [$order, $status]));
            });

        Route::get('process/{status}', [OrderController::class, 'process'])
            ->name('process')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order, Status $status) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Process').' - '.$status->name, route('admin.order.process', [$order, $status]));
            });

        Route::get('ticket_assignment/{ticket}', [OrderController::class, 'ticket_assignment'])
            ->name('ticket_assignment')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Ticket $ticket) {
                $trail->parent('admin.order.edit', $ticket)
                    ->push(__('Ticket assignment').' '.$ticket->id, route('admin.order.ticket_assignment', [$order, $ticket]));
            });

        Route::get('ticket_batch/{batch}', [OrderController::class, 'ticket_batch'])
            ->name('ticket_batch')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Batch $batch) {
                $trail->parent('admin.order.edit', $batch)
                    ->push(__('Batch assignment').' '.$batch->id, route('admin.order.ticket_batch', [$order, $batch]));
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

        Route::post('/', [OrderController::class, 'create_service_order'])->name('create_service_order');

        Route::get('service_orders', [OrderController::class, 'service_orders'])
            ->name('service_orders')
            ->middleware('permission:admin.access.order.create_service_order')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Service Orders'), route('admin.order.service_orders', $order));
            });

        Route::get('edit_chart', [OrderController::class, 'edit_chart'])
            ->name('edit_chart')
            ->middleware('permission:admin.access.order.modify')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent($order->from_store ? 'admin.store.all.index' : 'admin.order.request_chart')
                    ->push(__('Edit'), route('admin.order.edit_chart', $order));
            });

        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
    });

    Route::get('quotation_chart', function () {
            return view('backend.flowchart.quotation');
        })->name('quotation_chart')
        ->middleware('permission:admin.access.order.quotation')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Quotation Panel Management'), route('admin.order.quotation_chart'));
    });

    Route::get('quotations_chart', [OrderController::class, 'quotations_chart_list'])
        ->name('quotations_chart')
        ->middleware('permission:admin.access.order.order-sales')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Quotations'), route('admin.order.quotations_chart'));
        });
    Route::get('all_chart', [OrderController::class, 'all_chart'])
        ->name('all_chart')
        ->middleware('permission:admin.access.order.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.suborders')
                ->push(__('All orders'), route('admin.order.all_chart'));
        });


    Route::get('request_chart', [OrderController::class, 'flowchart_request'])
        ->name('request_chart')
        ->middleware('permission:admin.access.order.order')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Requests Management'), route('admin.order.request_chart'));
        });

});

Route::get('select2-service-type', [ServiceTypeController::class, 'select2LoadMore'])->name('servicetype.select');
