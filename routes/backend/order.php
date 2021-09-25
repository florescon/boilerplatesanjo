 <?php

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Status;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'order',
    'as' => 'order.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [OrderController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.product.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Order - Sale Management'), route('admin.order.index'));
        });
    Route::get('suborders', [OrderController::class, 'suborders_list'])
        ->name('suborders')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Suborders'), route('admin.order.suborders'));
        });
    Route::get('sales', [OrderController::class, 'sales_list'])
        ->name('sales')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Sales'), route('admin.order.sales'));
        });
    Route::get('mix', [OrderController::class, 'mix_list'])
        ->name('mix')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Mix'), route('admin.order.mix'));
        });
    Route::get('all', [OrderController::class, 'all_list'])
        ->name('all')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('All orders'), route('admin.order.all'));
        });
    Route::get('deleted', [OrderController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Deleted products'), route('admin.order.deleted'));
        });



    Route::group(['prefix' => '{order}'], function () {
        Route::get('edit', [OrderController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.index')
                    ->push(__('Edit'), route('admin.order.edit', $order));
            });

        Route::get('whereIs', [OrderController::class, 'where_is_products'])
            ->name('whereIs')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Where is products?'), route('admin.order.whereIs', $order));
            });


        Route::get('print', [OrderController::class, 'print'])
            ->name('print')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Print order'), route('admin.order.print', $order));
            });

        Route::get('ticket', [OrderController::class, 'ticket'])
            ->name('ticket')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket', $order));
            });

        Route::get('ticket_order', [OrderController::class, 'ticket_order'])
            ->name('ticket_order')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket_order', $order));
            });


        Route::get('ticket_materia', [OrderController::class, 'ticket_materia'])
            ->name('ticket_materia')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Ticket order'), route('admin.order.ticket_materia', $order));
            });

        Route::get('sub', [OrderController::class, 'suborders'])
            ->name('sub')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Suborders'), route('admin.order.sub', $order));
            });

        Route::get('advanced', [OrderController::class, 'advanced'])
            ->name('advanced')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Advanced order'), route('admin.order.advanced', $order));
            });

        Route::get('assignments/{status}', [OrderController::class, 'assignments'])
            ->name('assignments')
            ->breadcrumbs(function (Trail $trail, Order $order, Status $status) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Assignments').' - '.$status->name, route('admin.order.assignments', [$order, $status]));
            });

        Route::get('records', [OrderController::class, 'records'])
            ->name('records')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.edit', $order)
                    ->push(__('Status records'), route('admin.order.records', $order));
            });

        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
    });


});
