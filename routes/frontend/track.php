<?php
use App\Http\Controllers\Frontend\TrackController;
use App\Models\Frontend\Order;

// Route::get('/track', [TrackController::class, 'orderTrack']);

Route::group([
    'prefix' => 'track',
    'as' => 'track.',
    // 'middleware' =>  'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [TrackController::class, 'orderTrack'])
        ->name('index')
        // ->middleware('permission:admin.access.product.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.index')
                ->push(__('Order track'), route('frontend.track.index'));
        });

    Route::get('/search', [TrackController::class, 'search'])->name('search');


    Route::group(['prefix' => '{order}'], function () {
        Route::get('show', [TrackController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('frontend.track.show')
                    ->push(__('Edit'), route('frontend.track.show', $order));
            });
    });
});
