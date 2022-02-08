<?php

use App\Http\Controllers\SettingController;
use App\Http\Controllers\PaymentMethodController;
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
});

Route::get('select2-load-payment-method', [PaymentMethodController::class, 'select2LoadMore'])->name('payments.select');
