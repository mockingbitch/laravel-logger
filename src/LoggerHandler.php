<?php

namespace phongtran\Logger;

use Illuminate\Foundation\Configuration\Exceptions;
use Throwable;

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
     * Handler
     *
     * @param Exceptions $exceptions
     * @return void
     */
    public static function handle(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $e) {
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
