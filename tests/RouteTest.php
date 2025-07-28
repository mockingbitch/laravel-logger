<?php

namespace phongtran\Logger\Tests;

class RouteTest extends TestCase
{
    public function test_logger_index_route_returns_response()
    {
        $response = $this->get('/logger');
        
        // Should not return 404
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_logger_detail_route_returns_response()
    {
        $response = $this->get('/logger/1');
        
        // Should not return 404
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_logger_routes_are_registered_in_router()
    {
        $routes = app('router')->getRoutes();
        $loggerRoutes = collect($routes)->filter(function ($route) {
            return str_contains($route->uri(), 'logger');
        });
        
        $this->assertGreaterThan(0, $loggerRoutes->count(), 'Logger routes should be registered');
        
        // Check specific routes
        $indexRoute = collect($routes)->first(function ($route) {
            return $route->uri() === 'logger';
        });
        
        $detailRoute = collect($routes)->first(function ($route) {
            return $route->uri() === 'logger/{id}';
        });
        
        $this->assertNotNull($indexRoute, 'Logger index route should be registered');
        $this->assertNotNull($detailRoute, 'Logger detail route should be registered');
    }
} 