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

    Route::get('list', [ProductController::class, 'list'])
        ->name('list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('List of products'), route('admin.product.list'));
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
                    ->push(__('Edit').' '.$product->name, route('admin.product.edit', $product));
            });

        Route::get('advanced', [ProductController::class, 'advanced'])
            ->name('advanced')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Description'), route('admin.product.advanced', $product));
            });

        Route::get('prices', [ProductController::class, 'prices'])
            ->name('prices')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Prices'), route('admin.product.prices', $product));
            });

        Route::get('pictures', [ProductController::class, 'pictures'])
            ->name('pictures')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Product images'), route('admin.product.pictures', $product));
            });

        Route::get('move', [ProductController::class, 'moveStock'])
            ->name('move')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Move between stocks'), route('admin.product.move', $product));
            });

        Route::get('consumption', [ProductController::class, 'consumption'])
            ->name('consumption')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Consumption'), route('admin.product.consumption', $product));
            });

        Route::get('consumption_filter', [ProductController::class, 'consumption_filter'])
            ->name('consumption_filter')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Product consumption filter'), route('admin.product.consumption_filter', $product));
            });

        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
    });



    Route::group(['prefix' => '{product}'], function () {
        Route::post('create-codes', [ProductController::class, 'createCodes'])
            ->name('create-codes');
    });


    Route::get('deleted', [ProductController::class, 'deleted'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('Deleted products'), route('admin.product.deleted'));
        });
});

Route::get('select2-load-product', [ProductController::class, 'select2LoadMore'])->name('product.select');
Route::get('select2-load-productgroup', [ProductController::class, 'select2LoadMoreGroup'])->name('product.selectgroup');
