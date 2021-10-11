<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InvitationController;
// use App\Http\Controllers\InvitationSignupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UploadController;
use Aws\Account\AccountClient;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
œ|
*/



/** All the routes that needs authentication */
Route::middleware('auth')->group(function () {
    Route::get("/", [IndexController::class, 'index'])->name('home');
    Route::post("/fetch-more", [IndexController::class, 'fetch_more'])->name('index.fetch_more');
    //Route::resource('photo', PhotoController::class);
    Route::get("/trash", [PhotoController::class, 'trash'])->name('photos.trash');

    /* Routes related to uploading files */
    Route::prefix('upload')->name("uploads.")->group(function () {
        Route::get("/", [UploadController::class, 'index'])->name('index');
        Route::post("store", [UploadController::class, 'store'])->name('store');
        Route::post("store-file", [UploadController::class, 'storeFile'])->name('store_file');
        Route::post("cancel-upload", [UploadController::class, 'cancelUpload'])->name('cancel_upload');
        Route::post("update-details", [UploadController::class, 'updateDetails'])->name('update-details');
        Route::post("complete", [UploadController::class, 'completeUpload'])->name('complete');
    });


    Route::prefix('download')->name('downloads.')->group(function () {
        Route::post('generate-link', [DownloadController::class, 'generateLink'])->name('generate_link');
    });


    /* Routes related to photos */
    Route::prefix('photo')->name('photos.')->group(function () {
        Route::post('delete', [PhotoController::class, 'destroy'])->name('delete');
        Route::post('get-info', [PhotoController::class, 'getInfo'])->name('get_info');
        Route::post("add-tag", [PhotoController::class, 'addTag'])->name('add_tag');
        Route::post("remove-tag", [PhotoController::class, 'removeTag'])->name('remove_tag');
        Route::post("restore", [PhotoController::class, 'restore'])->name('restore');
        Route::get('{id}/{slug}', [PhotoController::class, 'show'])->name('show');
    });

    /** Routes related to share */
    Route::prefix('share')->name('share.')->group(function () {
        Route::post('create', [ShareController::class, 'create'])->name('create');
    });

    /* Routes related to tags */
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('', [TagController::class, 'index'])->name('index');
        Route::get('get-tags', [TagController::class, 'getTags'])->name('get_tags');
        route::get('view/{slug}', [TagController::class, 'show'])->name('show');
        // Route::get("search-by-partial")->name('search_partial');

    });

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
        route::post('delete', [NotificationController::class, 'destroy'])->name('destroy');
    });

    /** Routes related to comparing images */
    Route::prefix('compare')->name('compare.')->group(function () {
        Route::get('{left}/{right}', [DuplicateController::class, 'index'])->name('index');
    });

    /** Routes related to account */
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('', [AccountController::class, 'index'])->name('index');
        Route::post('update', [AccountController::class, 'update'])->name('update');
    });

    Route::resource('roles', RoleController::class);
});

Route::get('share/{key}', [ShareController::class, 'view'])->name('share.show');

require __DIR__ . '/auth.php';
