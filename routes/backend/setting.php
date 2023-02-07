<?php

use App\Http\Controllers\SettingController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ImageController;
use App\Models\Setting;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'setting',
    'as' => 'setting.',
], function () {
    Route::get('/', [SettingController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.access.settings.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Setting Management'), route('admin.setting.index'));
        });

    Route::get('pages', function () {
            return view('backend.setting.pages');
        })->name('pages')
        ->middleware('permission:admin.access.settings.list_pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Pages Management'), route('admin.setting.pages'));
        });

    Route::get('images_ai', function () {
            return view('backend.setting.images_ai');
        })->name('images_ai')
        ->middleware('permission:admin.access.settings.list_pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Banner Images Management'), route('admin.setting.images_ai'));
        });

    Route::get('banner', function () {
            return view('backend.setting.banner');
        })->name('banner')
        ->middleware('permission:admin.access.settings.list_pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Banner Images Management'), route('admin.setting.banner'));
        });

    Route::get('logos', function () {
            return view('backend.setting.logos');
        })->name('logos')
        ->middleware('permission:admin.access.settings.list_pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Brands Images Management'), route('admin.setting.logos'));
        });

    Route::get('gallery', function () {
            return view('backend.setting.gallery');
        })->name('gallery')
        ->middleware('permission:admin.access.settings.list_pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Gallery Management'), route('admin.setting.gallery'));
        });
});

Route::get('select2-load-payment-method', [PaymentMethodController::class, 'select2LoadMore'])->name('payments.select');

Route::get('select2-load-image', [ImageController::class, 'select2LoadMore'])->name('image.select');
