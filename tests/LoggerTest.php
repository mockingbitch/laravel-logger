<?php

namespace phongtran\Logger\Tests;

use phongtran\Logger\Logger;
use phongtran\Logger\app\Services\LogService;

class LoggerTest extends TestCase
{
    public function test_logger_can_log_info_message()
    {
        // Mock the log service to avoid database operations
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::info('Test info message');
    }

    public function test_logger_can_log_warning_message()
    {
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::warning('Test warning message');
    }

    public function test_logger_can_log_debug_message()
    {
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::debug('Test debug message');
    }

    public function test_logger_can_log_exception_message()
    {
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::exception('Test exception message');
    }

    public function test_logger_can_log_fatal_message()
    {
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::fatal('Test fatal message');
    }

    public function test_logger_can_log_activity_message()
    {
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::activity('Test activity message');
    }

    public function test_logger_can_log_sql_query()
    {
        $this->mock(\phongtran\Logger\app\Services\LogService::class, function ($mock) {
            $mock->shouldReceive('storeSqlQuery')->andReturn(null);
        });
        
        $this->expectNotToPerformAssertions();
        Logger::sql('SELECT * FROM users', 1.5);
    }

    public function test_log_service_can_be_resolved()
    {
        $logService = app(\phongtran\Logger\app\Services\AbsLogService::class);
        
        $this->assertInstanceOf(LogService::class, $logService);
    }

    public function test_logger_facade_works()
    {
        $this->assertTrue(app()->bound('logger'), 'Logger service should be bound to container');
    }

    public function test_logger_config_is_loaded()
    {
        $this->assertNotNull(config('logger'), 'Logger config should be loaded');
        $this->assertEquals('logs', config('logger.table'), 'Logger table should be configured');
    }

    public function test_logger_routes_are_registered()
    {
        // Check if routes are registered
        $routes = app('router')->getRoutes();
        $loggerRoutes = collect($routes)->filter(function ($route) {
            return str_contains($route->uri(), 'logger');
        });
        
        $this->assertGreaterThan(0, $loggerRoutes->count(), 'Logger routes should be registered');
    }

    public function test_logger_detail_route_works()
    {
        // Check if detail route is registered
        $routes = app('router')->getRoutes();
        $detailRoute = collect($routes)->first(function ($route) {
            return str_contains($route->uri(), 'logger/{id}');
        });
        
        $this->assertNotNull($detailRoute, 'Logger detail route should be registered');
    }
} 