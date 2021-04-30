<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UploadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get("/", [IndexController::class, 'index'])->name('index');
    Route::get("/upload", [UploadController::class, 'create'])->name('upload');
    Route::post("/store", [UploadController::class, 'store'])->name('upload.store');
    Route::get("/dashboard", function () {
        return Inertia::render('Dashboard');
    });
});
