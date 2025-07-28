<?php

namespace phongtran\Logger;

use Throwable;
use Illuminate\Support\Facades\App;

/**
 * LoggerHandler
 *
 * @package phongtran\Logger
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
class LoggerHandler
{
    /**
     * Handler for Laravel 11+ and 12
     *
     * @param mixed $exceptions
     * @return void
     */
    public static function handle($exceptions): void
    {
        // Check if we're using Laravel 11+ (which has the new Exceptions class)
        if (class_exists('Illuminate\Foundation\Configuration\Exceptions') && $exceptions instanceof \Illuminate\Foundation\Configuration\Exceptions) {
            $exceptions->render(function (Throwable $e) {
                $message = self::formatExceptionMessage($e);
                Logger::fatal($message);
            });
        }
        
        // Laravel 12 compatibility - check for the new exception handling
        if (method_exists($exceptions, 'reportable')) {
            $exceptions->reportable(function (Throwable $e) {
                $message = self::formatExceptionMessage($e);
                Logger::fatal($message);
            });
        }
    }

    /**
     * Handler for Laravel 10 and below
     *
     * @param \Illuminate\Contracts\Debug\ExceptionHandler $handler
     * @return void
     */
    public static function handleLegacy($handler): void
    {
        $handler->reportable(function (Throwable $e) {
            $message = self::formatExceptionMessage($e);
            Logger::fatal($message);
        });
    }

    /**
     * Format exception message
     *
     * @param Throwable $e
     * @return string
     */
    private static function formatExceptionMessage(Throwable $e): string
    {
        // Initialize the message array
        $messageParts = [];

        // Add HTTP status code if available
        if (method_exists($e, 'getStatusCode') && $e->getStatusCode()) {
            $messageParts[] = '[HTTP: ' . $e->getStatusCode() . ']';
        }

        // Add exception message if available
        if ($e->getMessage()) {
            $messageParts[] = 'Message: ' . $e->getMessage();
        }

        // Add file and line information if available
        if ($e->getFile() && $e->getLine()) {
            $messageParts[] = 'File: ' . $e->getFile() . ' Line: ' . $e->getLine();
        }

        // Return the formatted message as a string
        return implode(' ', $messageParts);
    }
}
