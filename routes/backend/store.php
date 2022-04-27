<?php

use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\OrderController;
use App\Models\Finance;
use App\Models\Cash;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'store',
    'as' => 'store.',
], function () {
    Route::get('pos', function () {
            return view('backend.store.pos');
        })->name('pos')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Shop Panel Management'), route('admin.store.pos'));
        });

    Route::group([
        'prefix' => 'all',
        'as' => 'all.',
    ], function () {
        Route::get('/', [OrderController::class, 'all_list_store'])
            ->name('index')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('All store list'), route('admin.store.all.index'));
            });

        Route::get('orders', [OrderController::class, 'orders_list_store'])
            ->name('orders')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.all.index')
                    ->push(__('All store orders'), route('admin.store.all.orders'));
            });

        Route::get('sales', [OrderController::class, 'sales_list_store'])
            ->name('sales')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.all.index')
                    ->push(__('All store sales'), route('admin.store.all.sales'));
            });

        Route::get('mix', [OrderController::class, 'mix_list_store'])
            ->name('mix')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.all.index')
                    ->push(__('All store mix orders/sales'), route('admin.store.all.mix'));
            });
    });

    Route::group([
        'prefix' => 'finances',
        'as' => 'finances.',
    ], function () {
        Route::get('/', function () {
                return view('backend.store.finances');
            })->name('index')
            ->middleware('permission:admin.access.store.list_finance')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Finances Management'), route('admin.store.finances.index'));
            });

        Route::group(['prefix' => '{finances}'], function () {
            Route::get('print', [FinanceController::class, 'print'])
                ->name('print')
                ->middleware('permission:admin.access.store.list_finance')
                ->breadcrumbs(function (Trail $trail, Finance $finances) {
                    $trail->parent('admin.store.finances.index', $finances)
                    ->push(__('Print finance'), route('admin.store.finances.print', $finances));
                });
        });

        Route::get('deleted', [FinanceController::class, 'deleted'])
            ->name('deleted')
            ->middleware('permission:admin.access.store.list_finance')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.finances.index')
                    ->push(__('Deleted finances'), route('admin.store.finances.deleted'));
            });
    });

    Route::group([
        'prefix' => 'box',
        'as' => 'box.',
    ], function () {
        Route::get('/', function () {
                return view('backend.store.box.box');
            })->name('index')
            ->middleware('permission:admin.access.store.create_box')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Daily cash closing Management'), route('admin.store.box.index'));
            });
        Route::get('history', function () {
                return view('backend.store.box.box-history');
            })->name('history')
            ->middleware('permission:admin.access.store.list_box')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.box.index')
                    ->push(__('Daily cash closing history Management'), route('admin.store.box.history'));
            });
        Route::get('deleted', [CashController::class, 'deleted'])
            ->name('deleted')
            ->middleware('permission:admin.access.store.list_box')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.box.index')
                    ->push(__('Deleted box history'), route('admin.store.box.deleted'));
            });

        Route::group(['prefix' => '{box}'], function () {

            Route::get('ticket', [CashController::class, 'ticket'])
                ->name('ticket')
                ->middleware('permission:admin.access.store.list_box')
                ->breadcrumbs(function (Trail $trail, Cash $box) {
                    $trail->parent('admin.store.box.ticket', $order)
                        ->push(__('Ticket box'), route('admin.store.box.ticket', $box));
                });

            Route::get('ticket-cash', [CashController::class, 'ticketCash'])
                ->name('ticket-cash')
                ->middleware('permission:admin.access.store.list_box')
                ->breadcrumbs(function (Trail $trail, Cash $box) {
                    $trail->parent('admin.store.box.ticket', $order)
                        ->push(__('Ticket box'), route('admin.store.box.ticket', $box));
                });

            Route::get('ticket-cash-out', [CashController::class, 'ticketCashOut'])
                ->name('ticket-cash-out')
                ->middleware('permission:admin.access.store.list_box')
                ->breadcrumbs(function (Trail $trail, Cash $box) {
                    $trail->parent('admin.store.box.ticket', $order)
                        ->push(__('Ticket box'), route('admin.store.box.ticket-cash-out', $box));
                });

            Route::get('show', [CashController::class, 'show'])
                ->name('show')
                ->middleware('permission:admin.access.store.list_box')
                ->breadcrumbs(function (Trail $trail, Cash $box) {
                    $trail->parent('admin.store.box.history')
                        ->push(__('Show daily cash closing').': #'.$box->id, route('admin.store.box.show', $box));
                });
        });
    });
});
