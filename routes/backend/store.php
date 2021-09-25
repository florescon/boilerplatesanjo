 <?php

use App\Http\Controllers\FinanceController;
use App\Models\Finance;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'store',
    'as' => 'store.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('pos', function () {
            return view('backend.store.pos');
        })->name('pos')
        // ->middleware('permission:admin.access.line.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Shop Panel Management'), route('admin.store.pos'));
        });

    Route::group([
        'prefix' => 'finances',
        'as' => 'finances.',
    ], function () {
        Route::get('/', function () {
                return view('backend.store.finances');
            })->name('index')
            // ->middleware('permission:admin.access.line.list')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Finances Management'), route('admin.store.finances.index'));
            });

        Route::get('deleted', [FinanceController::class, 'deleted'])
            ->name('deleted')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.store.finances.index')
                    ->push(__('Deleted finances'), route('admin.store.finances.deleted'));
            });
    });


    Route::get('box', function () {
            return view('backend.store.box');
        })->name('box')
        // ->middleware('permission:admin.access.line.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Daily cash closing Management'), route('admin.store.box'));
        });
});
