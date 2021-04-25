 <?php

use App\Http\Controllers\LineController;
use App\Models\Line;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'line',
    'as' => 'line.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [LineController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.line.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Line Management'), route('admin.line.index'));
        });

    Route::get('deleted', [LineController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.line.index')
                ->push(__('Deleted lines'), route('admin.line.deleted'));
        });


});


