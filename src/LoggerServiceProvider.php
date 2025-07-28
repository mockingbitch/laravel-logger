<?php

namespace phongtran\Logger;

use phongtran\Logger\app\Http\Middleware\LogActivity;
use phongtran\Logger\app\Http\Controllers\LoggerController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use phongtran\Logger\app\Services\LogService;
use phongtran\Logger\app\Services\AbsLogService;

/**
 * Logger Service Provider
 *
 * @package phongtran\Logger
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router|null $router
     * @return void
     */
    public function boot(?Router $router = null): void
    {
        // Laravel 12 compatibility - router might be null
        if ($router) {
            $router->middlewareGroup('activity', [LogActivity::class]);
        }
        
        // Register routes directly in ServiceProvider
        $this->registerRoutes();
        
        if (Config::get('logger')) {
            $existingLogging = Config::get('logging', []);
            $loggerConfig = Config::get('logger', []);
            
            // Only merge channels to avoid overwriting Laravel's core logging config
            if (isset($loggerConfig['channels']) && is_array($loggerConfig['channels'])) {
                $existingLogging['channels'] = array_merge(
                    $existingLogging['channels'] ?? [],
                    $loggerConfig['channels']
                );
                Config::set('logging', $existingLogging);
            }
        }
        if (config('logger.enable_query_debugger')) {
            QueryDebugger::setup();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (file_exists(config_path('logger.php'))) {
            $this->mergeConfigFrom(config_path('logger.php'), 'Logger');
        } else {
            $this->mergeConfigFrom(__DIR__ . '/config/logger.php', 'Logger');
        }

        // Routes will be registered via registerRoutes() method

        $this->app->singleton('logger', function ($app) {
            return new Logger();
        });
        $this->app->singleton(AbsLogService::class, function ($app) {
            return new LogService();
        });

        $this->loadViewsFrom(__DIR__.'/resources/views/', 'logger');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->registerEventListeners();
        $this->publishFiles();
        
        // Laravel 12 compatibility - register middleware
        $this->registerMiddleware();
        
        // Load helpers
        if (file_exists(__DIR__ . '/helpers.php')) {
            require_once __DIR__ . '/helpers.php';
        }
    }
    
    /**
     * Register middleware for Laravel 12 compatibility
     *
     * @return void
     */
    private function registerMiddleware(): void
    {
        // Check if we're in Laravel 12+ where router is not passed to boot method
        if (app()->bound('router')) {
            $router = app('router');
            $router->middlewareGroup('activity', [LogActivity::class]);
        }
    }

    /**
     * Register routes directly
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        if (!app()->bound('router')) {
            return;
        }
        
        $router = app('router');
        
        $router->group([
            'prefix' => 'logger',
            'middleware' => ['web'],
        ], function ($router) {
            $router->get('/', [LoggerController::class, 'index'])->name('log.index');
            $router->get('/{id}', [LoggerController::class, 'detail'])->name('log.detail');
        });
    }



    /**
     * Register the list of listeners and events.
     *
     * @return void
     */
    private function registerEventListeners(): void
    {
    }

    /**
     * Publish files for Laravel Logger.
     *
     * @return void
     */
    private function publishFiles(): void
    {
        $publishTag = 'logger';

        $this->publishes([
            __DIR__ . '/config/logger.php' => base_path('config/logger.php'),
        ], $publishTag);

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/' . $publishTag),
        ], $publishTag);

        $this->publishes([
            __DIR__.'/database/migrations' => base_path('database/migrations/' . $publishTag),
        ], $publishTag);

        $this->publishes([
            __DIR__.'/public/vendor' => base_path('public/vendor/' . $publishTag),
        ], $publishTag);
    }
}
