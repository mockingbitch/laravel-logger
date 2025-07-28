<?php

namespace phongtran\Logger\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use phongtran\Logger\LoggerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LoggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup APP_KEY for testing
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup logger config
        $app['config']->set('logger', [
            'enable_query_debugger' => false,
            'table' => 'logs',
            'query_table' => 'log_queries',
            'connection' => 'testbench',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations for testing
        $this->artisan('migrate', ['--database' => 'testbench']);
    }
} 