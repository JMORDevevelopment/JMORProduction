<?php

// Add this to routes/web.php

use App\Http\Controllers\HomeController;
Route::get('/test-hello', fn() => 'Hello World');
Route::get('/', [HomeController::class, 'index'])->name('home');