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


Route::middleware('auth')->group(function() {
    Route::get('admin/dashboard', function () {
        $menu = [];
        return inertia('Dashboard', compact('menu'));
    });

    Route::get('admin/database', [DatabaseController::class, 'index']);
    Route::put('admin/database/{database}', [DatabaseController::class, 'create']);
    Route::post('admin/database', [DatabaseController::class, 'store']);

    Route::post('logout', function () {
        return inertia('Cazzi', compact('menu'));
    });
});

require __DIR__.'/auth.php';
