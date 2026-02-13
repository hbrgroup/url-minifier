<?php

use App\Http\Controllers\ApiControllers\FileApiController;


/**
 * API Routes
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
 */

Route::post('/file', [FileApiController::class, 'upload'])->name('api.file.upload')
    ->middleware('throttle:10,1'); // Limit to 10 requests per minute
