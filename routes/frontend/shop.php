<?php

use App\Http\Controllers\Frontend\ShopController;
use App\Models\Product;
use Tabuna\Breadcrumbs\Trail;


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

	Route::group(['prefix' => '{shop}'], function () {
	    Route::get('/', [ShopController::class, 'show'])
	        ->name('show')
	        ->breadcrumbs(function (Trail $trail, Product $shop) {
	            $trail->parent('frontend.shop.index', $shop)
	                ->push(__('Show product'), route('frontend.shop.show', $shop));
	        });

	});

});

