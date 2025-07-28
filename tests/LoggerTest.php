<?php

namespace phongtran\Logger\Tests;

use phongtran\Logger\Logger;
use phongtran\Logger\app\Services\LogService;

class LoggerTest extends TestCase
{
    public function test_logger_can_log_info_message()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::info('Test info message');
    }

    public function test_logger_can_log_warning_message()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::warning('Test warning message');
    }

    public function test_logger_can_log_debug_message()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::debug('Test debug message');
    }

    public function test_logger_can_log_exception_message()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::exception('Test exception message');
    }

    public function test_logger_can_log_fatal_message()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::fatal('Test fatal message');
    }

    public function test_logger_can_log_activity_message()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::activity('Test activity message');
    }

    public function test_logger_can_log_sql_query()
    {
        $this->expectNotToPerformAssertions();
        
        Logger::sql('SELECT * FROM users', 1.5);
    }

    public function test_log_service_can_be_resolved()
    {
        $logService = app(\phongtran\Logger\app\Services\AbsLogService::class);
        
        $this->assertInstanceOf(LogService::class, $logService);
    }

    public function test_logger_routes_are_registered()
    {
        $response = $this->get('/logger');
        
        // Should not return 404
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_logger_detail_route_works()
    {
        $response = $this->get('/logger/1');
        
        // Should not return 404
        $this->assertNotEquals(404, $response->getStatusCode());
    }
} 