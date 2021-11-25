<?php

use App\Http\Controllers\CartController;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/cart' , App\Http\Livewire\Backend\Cart::class)->name('cart');


Route::group([
    'prefix' => 'cart',
    'as' => 'cart.',
    'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [CartController::class, 'index'])
        ->name('index')
        // ->middleware('permission:admin.access.cloth.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Cart Management'), route('admin.cart.index'));
        });

    Route::get('from_store', [CartController::class, 'from_store'])
        ->name('from_store')
        // ->middleware('permission:admin.access.cloth.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Cart Management'), route('admin.cart.from_store'));
        });
});


