<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ ApiController, DatabaseController };

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::post('admin/api/delete/{element}', [ApiController::class, 'delete']);

Route::get('admin/dashboard', function () {
    $menu = [];
    return inertia('Dashboard', compact('menu'));
});

Route::resource('admin/database', DatabaseController::class);

Route::post('logout', function () {
    return inertia('Cazzi', compact('menu'));
});

/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); */


/* Route::get('admin/{any}', function () {
    return view('app');
})->where('any', '.*'); */

require __DIR__.'/auth.php';
