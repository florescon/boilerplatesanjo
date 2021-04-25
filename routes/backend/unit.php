 <?php

use App\Http\Controllers\UnitController;
use App\Models\Unit;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'unit',
    'as' => 'unit.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [UnitController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.unit.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Unit Management'), route('admin.unit.index'));
        });

    Route::get('deleted', [UnitController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.unit.index')
                ->push(__('Deleted units'), route('admin.unit.deleted'));
        });


});


Route::get('select2-load-unit', [UnitController::class, 'select2LoadMore'])->name('unit.select');

