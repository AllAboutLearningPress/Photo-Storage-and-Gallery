<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UploadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
    Route::get("/", [IndexController::class, 'index'])->name('home');
    Route::post("/fetch-more", [IndexController::class, 'fetch_more'])->name('index.fetch_more');
    //Route::resource('photo', PhotoController::class);
    Route::get("/trash", [IndexController::class, 'trash'])->name('trash');

    /* Routes related to uploading files */
    Route::prefix('upload')->name("uploads.")->group(function () {
        Route::get("/", [UploadController::class, 'index'])->name('index');
        Route::post("store", [UploadController::class, 'store'])->name('store');
        Route::post("store-file", [UploadController::class, 'storeFile'])->name('store_file');
        Route::post("cancel-upload", [UploadController::class, 'cancelUpload'])->name('cancel_upload');
        Route::post("add-tag", [UploadController::class, 'addTag'])->name('add_tag');
        Route::post("remove-tag", [UploadController::class, 'removeTag'])->name('remove_tag');
        Route::post("update-details", [UploadController::class, 'updateDetails'])->name('update-details');
        Route::post("complete", [UploadController::class, 'completeUpload'])->name('complete');
    });

    Route::prefix('download')->name('downloads.')->group(function () {
        Route::post('generate-link', [DownloadController::class, 'generateLink'])->name('generate_link');
    });


    /* Routes related to photos */
    Route::get('photo/{id}/{slug}', [PhotoController::class, 'show'])->name('photo.show');
    Route::post('photo/delete', [PhotoController::class, 'destroy'])->name('photo.delete');
    Route::post('photo/restore', [PhotoController::class, 'restore'])->name('photo.restore');
    Route::post('photo/get-info', [PhotoController::class, 'getInfo'])->name('photo.get_info');

    /** Routes related to share */
    Route::prefix('share')->name('share.')->group(function () {
        Route::post('create', [ShareController::class, 'create'])->name('create');
    });

    /* Routes related to tags */
    Route::get("/tags/search-by-partial")->name('tags.search_partial');
    Route::get('/tags/get-tags', [TagController::class, 'getTags'])->name('tags.get_tags');
    Route::resource('tags', TagController::class);

    /** Routes related to search */
    Route::post("/search/search-title", [SearchController::class, 'searchTitle'])->name('search.search_title');

    /** Routes related to Invitations */
    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::get('inivtations', [InvitationController::class, 'index'])->name('index');
        Route::post('send-invite', [InvitationController::class, 'sendInvite'])->name('send_invite');
        Route::post("{id}/delete", [InvitationController::class, 'deleteInvite'])->name('delete_invite');
    });

    /** Routes related to notifications */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('all', [NotificationController::class, 'index'])->name('index');
        route::post('seen', [NotificationController::class, 'seen'])->name('seen');
    });

    /** Routes related to comparing images */
    Route::prefix('compare')->name('compare.')->group(function () {
        Route::get('{left}/{right}', [DuplicateController::class, 'index'])->name('index');
    });
    // Route::get("/dashboard", function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
});
/** Invitations routes that doesnt require authentication */
Route::get('invitations/accept-invite/{invite_code}', [InvitationController::class, 'acceptInvite'])->name('invitations.accept_invite');
Route::post('invitations/signup',  [InvitationController::class, 'signup'])->name('invitations.signup');
Route::get('share/{key}', [ShareController::class, 'view'])->name('share.show');
