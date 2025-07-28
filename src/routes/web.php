<?php

use phongtran\Logger\app\Http\Controllers\LoggerController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'logger',
    'middleware' => ['web'],
], function () {
    Route::get('/', [LoggerController::class, 'index'])->name('log.index');
    Route::get('/{id}', [LoggerController::class, 'detail'])->name('log.detail');
});
