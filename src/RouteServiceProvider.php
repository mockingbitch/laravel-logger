<?php

namespace phongtran\Logger;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use phongtran\Logger\app\Http\Controllers\LoggerController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->prefix('logger')
                ->group(function () {
                    Route::get('/', [LoggerController::class, 'index'])->name('log.index');
                    Route::get('/{id}', [LoggerController::class, 'detail'])->name('log.detail');
                });
        });
    }
} 