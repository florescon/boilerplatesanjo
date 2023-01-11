<?php

use App\Http\Controllers\CityController;
use App\Models\City;
use Tabuna\Breadcrumbs\Trail;

Route::get('select2-load-city', [CityController::class, 'select2LoadMore'])->name('city.select');
