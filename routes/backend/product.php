<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'product',
    'as' => 'product.',
], function () {
    Route::get('/', [ProductController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.product.modify')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Product Management'), route('admin.product.index'));
        });

    Route::get('list', [ProductController::class, 'list'])
        ->name('list')
        ->middleware('permission:admin.access.product.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('List of products'), route('admin.product.list'));
        });

    Route::get('records', [ProductController::class, 'recordsProduct'])
        ->name('records')
        ->middleware('permission:admin.access.product.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('Records of products'), route('admin.product.records'));
        });

    Route::get('create', [ProductController::class, 'create'])
        ->name('create')
        ->middleware('permission:admin.access.product.create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('Create product'), route('admin.product.create'));
        });

    Route::group(['prefix' => '{product}', 
        'middleware' => 'permission:admin.access.product.modify|admin.access.product.modify-prices-codes'
    ], function () {
        Route::get('edit', [ProductController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.access.product.modify')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Edit').' '.$product->name, route('admin.product.edit', $product));
            });

        Route::get('advanced', [ProductController::class, 'advanced'])
            ->name('advanced')
            ->middleware('permission:admin.access.product.modify')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Description'), route('admin.product.advanced', $product));
            });

        Route::get('prices', [ProductController::class, 'prices'])
            ->name('prices')
            ->middleware('permission:admin.access.product.modify-prices-codes')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Prices and codes'), route('admin.product.prices', $product));
            });

        Route::get('pictures', [ProductController::class, 'pictures'])
            ->name('pictures')
            ->middleware('permission:admin.access.product.modify')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Product images'), route('admin.product.pictures', $product));
            });

        Route::get('move', [ProductController::class, 'moveStock'])
            ->name('move')
            ->middleware('permission:admin.access.product.move-stocks')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Move between stocks'), route('admin.product.move', $product));
            });

        Route::get('kardex', [ProductController::class, 'kardex'])
            ->name('kardex')
            ->middleware('permission:admin.access.product.kardex')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Product Kardex'), route('admin.product.kardex', $product));
            });

        Route::get('delete-attributes', [ProductController::class, 'deleteAttributes'])
            ->name('delete-attributes')
            ->middleware('permission:admin.access.product.modify')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Delete attributes'), route('admin.product.delete-attributes', $product));
            });

        Route::get('consumption', [ProductController::class, 'consumption'])
            ->name('consumption')
            ->middleware('permission:admin.access.product.consumption')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.edit', $product)
                    ->push(__('Consumption'), route('admin.product.consumption', $product));
            });

        Route::get('consumption_filter', [ProductController::class, 'consumption_filter'])
            ->name('consumption_filter')
            ->middleware('permission:admin.access.product.consumption')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index')
                    ->push(__('Product consumption filter'), route('admin.product.consumption_filter', $product));
            });

        Route::get('large-qr', [ProductController::class, 'large_qr'])
            ->name('large-qr')
            ->middleware('permission:admin.access.product.list')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Large qr'), route('admin.product.large-qr', $product));
            });
        Route::get('large-barcode', [ProductController::class, 'large_barcode'])
            ->name('large-barcode')
            ->middleware('permission:admin.access.product.list')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Large barcode'), route('admin.product.large-barcode', $product));
            });

        Route::get('short-barcode', [ProductController::class, 'short_barcode'])
            ->name('short-barcode')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Short barcode'), route('admin.product.short-barcode', $product));
            });

        Route::get('packing-barcode', [ProductController::class, 'packing_barcode'])
            ->name('packing-barcode')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Short barcode'), route('admin.product.packing-barcode', $product));
            });

        Route::get('t', [ProductController::class, 'short_ticket'])
            ->name('t')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index', $product)
                    ->push(__('Short ticket'), route('admin.product.t', $product));
            });

        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');

        Route::delete('clear_consumption', [ProductController::class, 'clear_consumption'])
            ->name('clear_consumption');

    });

    Route::group(['prefix' => '{product}'], function () {
        Route::post('create-codes', [ProductController::class, 'createCodes'])
            ->middleware('permission:admin.access.product.modify-prices-codes')
            ->name('create-codes');
    });

    Route::get('deleted', [ProductController::class, 'deleted'])
        ->name('deleted')
        ->middleware('permission:admin.access.product.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index')
                ->push(__('Deleted products'), route('admin.product.deleted'));
        });
});

Route::get('select2-load-product', [ProductController::class, 'select2LoadMore'])->name('product.select');
Route::get('select2-load-productgroup', [ProductController::class, 'select2LoadMoreGroup'])->name('product.selectgroup');
Route::get('select2-load-service', [ProductController::class, 'select2ServiceLoadMore'])->name('service.select');
