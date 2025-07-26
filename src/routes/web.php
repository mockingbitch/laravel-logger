<?php

use phongtran\Logger\App\Http\Controllers\LoggerController;
use Illuminate\Support\Facades\Route;

Route::prefix('logger')->group(function () {
    Route::get('/', [LoggerController::class, 'index'])->name('log.index');
    Route::get('/{id}', [LoggerController::class, 'detail'])->name('log.detail');
});
