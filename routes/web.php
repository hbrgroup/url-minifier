<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/{slug}/qrcode', [LinkController::class, 'create_qrcode'])->name('links.qrcode');

Route::get('/{slug}', [LinkController::class, 'click'])->name('links.click');
