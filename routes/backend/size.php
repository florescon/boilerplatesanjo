 <?php

use App\Http\Controllers\SizeController;
use App\Models\Size;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'size',
    'as' => 'size.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [SizeController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.size.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Size Management'), route('admin.size.index'));
        });

    Route::get('deleted', [SizeController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.size.index')
                ->push(__('Deleted sizes'), route('admin.size.deleted'));
        });


});

Route::get('select2-load-size', [SizeController::class, 'select2LoadMore'])->name('size.select');
