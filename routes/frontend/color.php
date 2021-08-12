 <?php

use App\Http\Controllers\ColorController;
use App\Models\Color;

Route::get('select2-load-color-frontend', [ColorController::class, 'select2LoadMoreFrontend'])->name('colorSelect');
