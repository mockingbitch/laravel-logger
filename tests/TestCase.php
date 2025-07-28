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
} 