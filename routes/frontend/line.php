 <?php

use App\Http\Controllers\LineController;
use App\Models\Line;

Route::get('select2-load-line-frontend', [LineController::class, 'select2LoadMoreLineFrontend'])->name('lineSelect');
