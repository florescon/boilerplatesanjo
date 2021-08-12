 <?php

use App\Http\Controllers\SizeController;
use App\Models\Size;

Route::get('select2-load-size-frontend', [SizeController::class, 'select2LoadMoreFrontend'])->name('sizeSelect');
