 <?php

use App\Http\Controllers\ClothController;
use App\Models\Cloth;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'cloth',
    'as' => 'cloth.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [ClothController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.cloth.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Cloth Management'), route('admin.cloth.index'));
        });

    Route::get('deleted', [ClothController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.cloth.index')
                ->push(__('Deleted cloths'), route('admin.cloth.deleted'));
        });


});


