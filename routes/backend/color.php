 <?php

use App\Http\Controllers\ColorController;
use App\Models\Color;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'color',
    'as' => 'color.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [ColorController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.color.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Color Management'), route('admin.color.index'));
        });
});


Route::get('select2-load-color', [ColorController::class, 'select2LoadMore'])->name('color.select');
