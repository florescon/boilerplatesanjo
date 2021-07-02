 <?php

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Status;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'order',
    'as' => 'order.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [OrderController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.product.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Order - Sale Management'), route('admin.order.index'));
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
                    ->push(__('Records status'), route('admin.order.records', $order));
            });

        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [OrderController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index')
                ->push(__('Deleted products'), route('admin.order.deleted'));
        });


});
