<?php

namespace phongtran\Logger;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

/**
 * Query Debugger
 *
 * @package phongtran\Logger
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
class QueryDebugger
{
    /**
     * Set up the debugger
     *
     * @return void
     */
    public static function setup(): void
    {
        // Only enable in non-production environments by default
        if (App::environment('production') && !config('logger.enable_query_debugger_production', false)) {
            return;
        }
        
        // Laravel 12 compatibility - check if DB facade is available
        if (!class_exists('Illuminate\Support\Facades\DB')) {
            return;
        }
        
        DB::listen(function ($sql) {
            // Skip if execution time is too fast (performance optimization)
            if ($sql->time < config('logger.min_query_time', 0)) {
                return;
            }
            
            // Extract the table name (this is a basic approach and might need adjustment based on query structure)
            $table = '';
            if (
                preg_match('/from\s+([^\s]+)/i', $sql->sql, $matches)
                || preg_match('/update\s+([^\s]+)/i', $sql->sql, $matches)
                || preg_match('/into\s+([^\s]+)/i', $sql->sql, $matches)
            ) {
                $table = self::removeSemicolon($matches[1]);
            }
            if (!in_array($table, self::getIgnoredTables())) {
                $bindings = [];
                foreach ($sql->bindings as $binding) {
                    if ($binding instanceof DateTime) {
                        $bindings[] = $binding->format('Y-m-d H:i:s');
                    } elseif (is_string($binding)) {
                        $bindings[] = $binding;
                    } else {
                        $bindings[] = (string) $binding;
                    }
                }
                
                // Use parameterized query for logging to prevent SQL injection
                $query = $sql->sql;
                $executionTime = $sql->time;
                Logger::sql($query, $executionTime);
            }
        });
    }

    /**
     * Remove semicolon
     *
     * @param string $string
     * @return string
     */
    private static function removeSemicolon(string $string): string
    {
        return preg_replace('/["`\';]/', '', $string);
    }

    /**
     * Get Ignored Tables
     *
     * @return array
     */
    private static function getIgnoredTables(): array
    {
        $logTables = [
            config('logger.table'),
            config('logger.query_table')
        ];

        $ignoredTables = array_filter(array_map(
                'trim',
                explode(',', config('logger.ignored_tables', ''))
            )
        );

        return array_merge($logTables, $ignoredTables);
    }
}
