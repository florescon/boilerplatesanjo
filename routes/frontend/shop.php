<?php

use App\Http\Controllers\Frontend\ShopController;
use App\Models\Frontend\Product;
use Tabuna\Breadcrumbs\Trail;
use App\Http\Middleware\XSS;

Route::group([
    'prefix' => 'shop',
    'as' => 'shop.',
], function () {
	Route::get('/', [ShopController::class, 'index'])
    ->name('index')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('frontend.index')
            ->push(__('Shop'), route('frontend.shop.index'));
    });

	Route::group(['prefix' => '{shop}', 'middleware' => ['XSS']], function () {
	    Route::get('/', [ShopController::class, 'show'])
	        ->name('show')
	        ->breadcrumbs(function (Trail $trail, Product $shop) {
	            $trail->parent('frontend.shop.index')
	                ->push(__('Show product'), route('frontend.shop.show', $shop));
	        });

	    Route::get('datasheet', [ShopController::class, 'datasheet'])
	        ->name('datasheet')
	        ->breadcrumbs(function (Trail $trail, Product $shop) {
	            $trail->parent('frontend.shop.show', $shop)
	                ->push(__('Product data sheet'), route('frontend.shop.datasheet', $shop));
	        });

	});

});

