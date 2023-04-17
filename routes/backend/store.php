<?php

use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Finance;
use App\Models\Cash;
use App\Models\Order;
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

    Route::get('order', function () {
            return view('backend.store.order');
        })->name('order')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Order Panel Management'), route('admin.store.order'));
        });

    Route::get('request', function () {
            return view('backend.store.request');
        })->name('request')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Request Panel Management'), route('admin.store.request'));
        });

    Route::get('quotation', function () {
            return view('backend.store.quotation');
        })->name('quotation')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Quotation Panel Management'), route('admin.store.quotation'));
        });

    Route::get('output_products', function () {
            return view('backend.store.output_products');
        })->name('output_products')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Output Products Panel Management'), route('admin.store.output_products'));
        });

    Route::get('sale', function () {
            return view('backend.store.sale');
        })->name('sale')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Sale Panel Management'), route('admin.store.sale'));
        });
    Route::get('sales', function () {
            return view('backend.store.sales');
        })->name('sales')
        ->middleware('permission:admin.access.store.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Sales Panel Management'), route('admin.store.sales'));
        });

    Route::group([
        'prefix' => 'product',
        'as' => 'product.',
    ], function () {

        Route::get('/', [ProductController::class, 'index_store'])
            ->name('index')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Shop Panel Product Store'), route('admin.store.product.index'));
            });

        Route::group(['prefix' => '{product}', 
            'middleware' => 'permission:admin.access.store.list'
        ], function () {
            Route::get('edit', [ProductController::class, 'edit_store'])
                ->name('edit')
                ->middleware('permission:admin.access.store.list')
                ->breadcrumbs(function (Trail $trail, Product $product) {
                    $trail->parent('admin.store.product.index', $product)
                        ->push(__('Edit').' '.$product->name, route('admin.store.product.edit', $product));
                });

        Route::get('prices', [ProductController::class, 'prices_store'])
            ->name('prices')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.store.product.edit', $product)
                    ->push(__('Prices and codes'), route('admin.store.product.prices', $product));
            });

        });
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

        Route::group(['prefix' => '{order}'], function () {
            Route::get('edit', [OrderController::class, 'edit_store'])
                ->name('edit')
                ->middleware('permission:admin.access.store.order.modify_store')
                ->breadcrumbs(function (Trail $trail, Order $order) {
                    $trail->parent($order->from_store ? 'admin.store.all.index' : 'admin.order.index')
                        ->push(__('Edit'), route('admin.store.all.edit', $order));
                });

            Route::get('print/{breackdown?}/{grouped?}', [OrderController::class, 'print_store'])
                ->name('print')
                ->middleware('permission:admin.access.store.print')
                ->breadcrumbs(function (Trail $trail, Order $order) {
                    $trail->parent('admin.order.edit', $order)
                        ->push(__('Print order'), route('admin.store.all.print', [$order, $breackdown, $grouped]));
                });

            Route::get('ticket', [OrderController::class, 'ticket_store'])
                ->name('ticket')
                ->middleware('permission:admin.access.store.print')
                ->breadcrumbs(function (Trail $trail, Order $order) {
                    $trail->parent('admin.order.edit', $order)
                        ->push(__('Ticket order'), route('admin.store.all.ticket', $order));
                });

            Route::get('ticket_order/{breackdown?}/{emptyPrices?}', [OrderController::class, 'ticket_order_store'])
                ->name('ticket_order')
                ->middleware('permission:admin.access.store.print')
                ->breadcrumbs(function (Trail $trail, Order $order) {
                    $trail->parent('admin.order.edit', $order)
                        ->push(__('Ticket order'), route('admin.store.all.ticket_order', [$order, $breackdown, $emptyPrices]));
                });

            Route::get('records_payment', [OrderController::class, 'records_payment_store'])
                ->name('records_payment')
                ->middleware('permission:admin.access.store.records_payment')
                ->breadcrumbs(function (Trail $trail, Order $order) {
                    $trail->parent('admin.order.edit', $order)
                        ->push(__('Status records payment'), route('admin.store.all.records_payment', $order));
                });

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

        Route::get('output_products', [OrderController::class, 'output_products_list_store'])
            ->name('output_products')
            ->middleware('permission:admin.access.store.output_products')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.all.index')
                    ->push(__('All store output products'), route('admin.store.all.output_products'));
            });

        Route::get('requests', [OrderController::class, 'requests_list_store'])
            ->name('requests')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.all.index')
                    ->push(__('All store requests'), route('admin.store.all.requests'));
            });

        Route::get('quotations', [OrderController::class, 'quotations_list_store'])
            ->name('quotations')
            ->middleware('permission:admin.access.store.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.all.index')
                    ->push(__('All store quotations'), route('admin.store.all.quotations'));
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

            Route::get('print', [CashController::class, 'print'])
                ->name('print')
                ->middleware('permission:admin.access.store.list_box')
                ->breadcrumbs(function (Trail $trail, Cash $box) {
                    $trail->parent('admin.store.box.ticket', $order)
                        ->push(__('Ticket box'), route('admin.store.box.print', $box));
                });

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
