 <?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Tabuna\Breadcrumbs\Trail;


Route::group([
    'prefix' => 'product',
    'as' => 'product.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [ProductController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.product.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Product Management'), route('admin.product.index'));
        });

    Route::get('create', [ProductController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('Create product'), route('admin.product.create'));
        });


    Route::group(['prefix' => '{product}'], function () {
        Route::get('edit', [ProductController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Edit'), route('admin.product.edit', $product));
            });

        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
    });


    Route::group(['prefix' => '{product}'], function () {
        Route::get('consumption', [ProductController::class, 'consumption'])
            ->name('consumption')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Edit'), route('admin.product.consumption', $product));
            });

        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::get('deleted', [ProductController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('Deleted products'), route('admin.product.deleted'));
        });


});


