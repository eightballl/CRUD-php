<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstagramBotController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', [AdminController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/export/{tanggal}', [AdminController::class, 'export'])->middleware('auth')->name('export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tiktok/comment', [InstagramBotController::class, 'showForm'])->name('instagram.form');
    Route::post('/tiktok/comment', [InstagramBotController::class, 'sendComment'])->name('instagram.sendComment');
    Route::get('/instagram/comment', [InstagramBotController::class, 'showUploadForm'])->name('comment.form');
    Route::post('/instagram/comment', [InstagramBotController::class, 'handleCsvUpload'])->name('comment.upload');
    Route::get('/instagram/report', [InstagramBotController::class, 'showReportForm'])->name('instagram.formreport');
    Route::post('/instagram/report', [InstagramBotController::class, 'sendReport'])->name('instagram.report');
    Route::get('/instagram/report1', [InstagramBotController::class, 'showReportForm1'])->name('instagram.formreport1');
    Route::post('/instagram/report1', [InstagramBotController::class, 'sendReport1'])->name('instagram.report1');
    Route::get('/tiktok/report', [InstagramBotController::class, 'showReportTiktok'])->name('tiktok.formreport');
    Route::post('/tiktok/report', [InstagramBotController::class, 'sendReportTiktok'])->name('tiktok.report');

});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
Route::post('/tiket', [TiketController::class, 'store'])->name('tiket.store');

Route::get('/event/create', [EventController::class, 'create'])->middleware('auth')->name('event.create');
Route::post('/event', [EventController::class, 'store'])->middleware('auth')->name('event.store');
Route::get('/event/{event}/edit', [EventController::class, 'edit'])->middleware('auth')->name('event.edit');
Route::put('/event/{event}', [EventController::class, 'update'])->middleware('auth')->name('event.update');
Route::delete('/event/{event}', [EventController::class, 'destroy'])->middleware('auth')->name('event.destroy');
Route::get('/events', [EventController::class, 'index'])->middleware('auth')->name('events');

// Route::get('/instagram', [InstagramBotController::class, 'showForm'])->name('instagram.form');
// Route::post('/instagram', [InstagramBotController::class, 'submitForm'])->name('instagram.comment');
// routes/web.php
// Route::get('/instagram', [InstagramBotController::class, 'showForm'])->name('instagram.form');
// Route::post('/instagram', [InstagramBotController::class, 'sendComment'])->name('instagram.sendComment');
// Route::get('/comment/upload', [InstagramBotController::class, 'showUploadForm'])->name('comment.form');
// Route::post('/comment/upload', [InstagramBotController::class, 'handleCsvUpload'])->name('comment.upload');
Route::get('/install', [InstagramBotController::class, 'install'])->name('install');

Route::view('/scan-barcode', 'scan-barcode');

Route::post('/barcode/store', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'barcode' => $request->code,
    ]);
});