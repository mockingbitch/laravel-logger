<?php

use phongtran\Logger\LogFormatter;

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Activity
    |--------------------------------------------------------------------------
    |
    | Here you can configure log activity for your application. Laravel
    | Using log activity will record all requests from users to the application.
    | It will be optimized in dev environment to be able to develop applications
    |
    */
    'enable_query_debugger' => env('ENABLE_QUERY_DEBUGGER', false),
    'enable_query_debugger_production' => env('ENABLE_QUERY_DEBUGGER_PRODUCTION', false),
    'min_query_time' => env('LOGGER_MIN_QUERY_TIME', 0), // Minimum query time to log (in milliseconds)
    'table' => env('LOGGER_TABLE', 'logs'),
    'query_table' => env('LOGGER_QUERY_TABLE', 'log_queries'),
    'connection' => env('LOGGER_CONNECTION', env('DB_CONNECTION', 'mysql')),
    'ignored_tables' => env('LOGGER_IGNORED_TABLES', ''), //Example: LOGGER_IGNORED_TABLES=sessions,users

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */

    'channels' => [
        'sql' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sql.log'),
            'level' => 'info',
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    "datetime:%datetime%\t%message%".PHP_EOL,
                    'Y/m/d H:i:s',
                ]),
            ],
        ],

        'warning' => [
            'driver' => 'daily',
            'path' => storage_path('logs/warn.log'),
            'level' => 'warning',
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    '[%datetime%] [%level_name%] %message% %context%'.PHP_EOL,
                    'Y-m-d H:i:s:v',
                ]),
            ],
        ],

        'info' => [
            'driver' => 'daily',
            'path' => storage_path('logs/info.log'),
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    '[%datetime%] [%level_name%] %message% %context%'.PHP_EOL,
                    'Y-m-d H:i:s:v',
                ]),
            ],
        ],

        'fatal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/fatal.log'),
            'level' => 'critical',
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    '[%datetime%] [%level_name%] %message% %context%'.PHP_EOL,
                    'Y-m-d H:i:s:v',
                ]),
            ],
        ],

        'exception' => [
            'driver' => 'daily',
            'path' => storage_path('logs/exception.log'),
            'level' => 'error',
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    '[%datetime%] [%level_name%] %message% %context%'.PHP_EOL,
                    'Y-m-d H:i:s:v',
                ]),
            ],
        ],

        'debug' => [
            'driver' => 'daily',
            'path' => storage_path('logs/debug.log'),
            'level' => 'debug',
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    '[%datetime%] [%level_name%] %message% %context%'.PHP_EOL,
                    'Y-m-d H:i:s:v',
                ]),
            ],
        ],

        'activity' => [
            'driver' => 'daily',
            'path' => storage_path('logs/activity.log'),
            'level' => 'info',
            'tap' => [
                LogFormatter::class.':'.implode(',', [
                    '[%datetime%] %message% %context%'.PHP_EOL,
                    'Y-m-d H:i:s:v',
                ]),
            ],
        ],
    ],

];
