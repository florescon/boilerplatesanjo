<?php

use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'documentation',
    'as' => 'documentation.',
], function () {
    Route::get('/', function () {
            return view('backend.documentation.index');
        })->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Documentation'), route('admin.documentation.index'));
        });

    Route::get('license', function () {
            return view('backend.documentation.license');
        })->name('license')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('License'), route('admin.documentation.license'));
        });

    Route::get('documentation', function () {
            return view('backend.documentation.documentation');
        })->name('documentation')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Documentation'), route('admin.documentation.documentation'));
        });

    Route::get('faqs', function () {
            return view('backend.documentation.faqs');
        })->name('faqs')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Faqs'), route('admin.documentation.faqs'));
        });

    Route::get('start', function () {
            return view('backend.documentation.start');
        })->name('start')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Quick start'), route('admin.documentation.start'));
        });
});
