<?php

namespace phongtran\Logger;

use phongtran\Logger\App\Http\Middleware\LogActivity;
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
     * @param Router $router
     * @return void
     */
    public function boot(Router $router): void
    {
        $router->middlewareGroup('activity', [LogActivity::class]);
        if (Config::get('logger')) {
            Config::set('logging', array_merge(
                Config::get('logging'),
                Config::get('logger')
            ));
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

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->app->singleton('logger', function ($app) {
            return new Logger();
        });
        $this->app->singleton(AbsLogService::class, function ($app) {
            return new LogService();
        });

//        $this->loadViewsFrom(__DIR__.'/resources/views/', 'Logger');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->registerEventListeners();
        $this->publishFiles();
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
