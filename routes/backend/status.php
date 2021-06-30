 <?php

use App\Http\Controllers\StatusController;
use App\Models\Status;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'status',
    'as' => 'status.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [StatusController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.status.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Status Management'), route('admin.status.index'));
        });


    Route::get('deleted', [StatusController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.status.index')
                ->push(__('Deleted statuses'), route('admin.status.deleted'));
        });

});


Route::get('select2-load-status', [StatusController::class, 'select2LoadMore'])->name('status.select');
