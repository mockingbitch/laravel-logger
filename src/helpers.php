<?php

if (!function_exists('logger_routes_debug')) {
    /**
     * Debug function to check if logger routes are registered
     *
     * @return array
     */
    function logger_routes_debug(): array
    {
        $routes = [];
        
        if (app()->bound('router')) {
            $router = app('router');
            $routeCollection = $router->getRoutes();
            
            foreach ($routeCollection as $route) {
                if (str_contains($route->uri(), 'logger')) {
                    $routes[] = [
                        'uri' => $route->uri(),
                        'methods' => $route->methods(),
                        'name' => $route->getName(),
                        'action' => $route->getActionName(),
                        'middleware' => $route->middleware(),
                    ];
                }
            }
        }
        
        return $routes;
    }
}

if (!function_exists('logger_debug_info')) {
    /**
     * Debug function to check logger package status
     *
     * @return array
     */
    function logger_debug_info(): array
    {
        return [
            'service_provider_registered' => app()->bound('logger'),
            'config_loaded' => config('logger') !== null,
            'views_loaded' => view()->exists('logger::index'),
            'routes_registered' => logger_routes_debug(),
            'laravel_version' => app()->version(),
        ];
    }
} 