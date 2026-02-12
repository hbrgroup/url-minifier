<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\FileController;

/***
 * Web Routes
 *
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 *
 */

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/f/{uuid}', [FileController::class, 'download_page'])->name('file.page.download');
Route::get('/f/{uuid}/download', [FileController::class, 'download_files'])->name('file.downloads');
Route::get('/f/{uuid}/download/{file}', [FileController::class, 'download_file'])->name('file.download');
Route::get('/{slug}/qrcode', [LinkController::class, 'create_qrcode'])->name('links.qrcode');
Route::get('/{slug}', [LinkController::class, 'click'])->name('links.click');
