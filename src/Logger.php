<?php

namespace phongtran\Logger;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use phongtran\Logger\app\Services\AbsLogService;
use phongtran\Logger\app\Services\Definitions\LoggerDef;

/**
 * Logger
 *
 * @package phongtran\Logger
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
class Logger
{
    /**
     * Format backtrace info
     *
     * @param array $backtrace
     * @return string
     */
    private static function formatBacktrace(array $backtrace = []): string
    {
        $caller = $backtrace[1] ?? [];

        if (!isset($caller['file'])) {
            return '<unknown file (Line:unknown)>';
        }

        $file = $caller['file'];
        $line = $caller['line'] ?? 'unknown';

        if (str_contains($file, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR)) {
            // If the file is inside the vendor directory → only keep the part after "vendor/"
            $file = Str::after($file, 'vendor' . DIRECTORY_SEPARATOR);
            $file = 'vendor/' . $file;
        } else {
            // If the file is inside base_path → shorten the path
            $file = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
        }

        return "<{$file} (Line:{$line})>";
    }

    /**
     * Write log to the specified channel and level.
     *
     * @param string $channel
     * @param string $level
     * @param string|null $message
     * @return mixed
     */
    private static function log(string $channel, string $level, ?string $message): mixed
    {
        /** @var AbsLogService $logService */
        $logService = app(AbsLogService::class);

        // Format message for a channel type
        $logMessage = match ($channel) {
            LoggerDef::CHANNEL_ACTIVITY => json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            default => self::formatBacktrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)) . " {$message}",
        };

        // Write log to channel
        Log::channel($channel)->log($level, $logMessage);

        // Write log to DB
        return app()->call([$logService, 'store'], [
            'channel' => $channel,
            'level' => $level,
            'message' => $logMessage,
        ]);
    }

    /**
     * Log a warning message.
     *
     * @param string|null $message
     * @return void
     */
    public static function warning(?string $message = ''): void
    {
        self::log(LoggerDef::CHANNEL_WARNING, LoggerDef::LEVEL_WARNING, $message);
    }

    /**
     * Log a fatal error message.
     *
     * @param string|null $message
     * @return void
     */
    public static function fatal(?string $message = ''): void
    {
        self::log(LoggerDef::CHANNEL_FATAL, LoggerDef::LEVEL_CRITICAL, $message);
    }

    /**
     * Log an exception message.
     *
     * @param string|null $message
     * @return void
     */
    public static function exception(?string $message = ''): void
    {
        self::log(LoggerDef::CHANNEL_EXCEPTION, LoggerDef::LEVEL_ERROR, $message);
    }

    /**
     * Log a debug message.
     *
     * @param string|null $message
     * @return void
     */
    public static function debug(?string $message = ''): void
    {
        self::log(LoggerDef::CHANNEL_DEBUG, LoggerDef::LEVEL_DEBUG, $message);
    }

    /**
     * Log an informational message.
     *
     * @param string|null $message
     * @return void
     */
    public static function info(?string $message = ''): void
    {
        self::log(LoggerDef::CHANNEL_INFO, LoggerDef::LEVEL_INFO, $message);
    }

    /**
     * Log an activity message.
     *
     * @param string|null $message
     * @return mixed
     */
    public static function activity(?string $message = ''): mixed
    {
        return self   ::log(LoggerDef::CHANNEL_ACTIVITY, LoggerDef::LEVEL_INFO, $message);
    }

    /**
     * Log an sql query.
     *
     * @param string|null $query
     * @param float $executionTime
     * @return mixed
     */
    public static function sql(?string $query = '', float $executionTime = 0): mixed
    {
        $logService = app(AbsLogService::class);
        Log::channel(LoggerDef::CHANNEL_SQL)
            ->log(LoggerDef::LEVEL_INFO, "[ExecutionTime: {$executionTime}ms] {$query}");

        return app()->call([$logService, 'storeSqlQuery'], [
            'query' => $query,
            'executionTime' => $executionTime,
        ]);
    }
}
