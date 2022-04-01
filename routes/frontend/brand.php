 <?php

use App\Http\Controllers\BrandController;
use App\Models\Brand;

Route::get('select2-load-brand-frontend', [BrandController::class, 'select2LoadMoreFrontend'])->name('brandSelect');
