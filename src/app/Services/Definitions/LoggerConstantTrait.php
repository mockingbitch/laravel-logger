<?php

namespace phongtran\Logger\app\Services\Definitions;

/**
 * LoggerConstantTrait
 *
 * @package phongtran\Logger\app\Services\Definitions
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
trait LoggerConstantTrait
{
    /**
     * @var array $type
     */
    private static array $type = [
        self::SQL => 'SQL',
        self::INFO => 'INFORMATION',
        self::DEBUG => 'DEBUG',
        self::FATAL => 'FATAL',
        self::WARNING => 'WARNING',
        self::ACTIVITY => 'ACTIVITY',
        self::EXCEPTION => 'EXCEPTION',
    ];

    /**
     * Get type
     *
     * @return array
     */
    public static function getType() : array
    {
        return self::$type;
    }

    /**
     * @var array|string[] $levelClasses
     */
    private static array $levelClasses = [
        self::LEVEL_INFO => 'success',
        self::LEVEL_CRITICAL => 'danger',
        self::LEVEL_ERROR => 'danger',
        self::LEVEL_ACTIVITY => 'success',
        self::LEVEL_WARNING => 'warning',
        self::LEVEL_DEBUG => 'info',
    ];

    /**
     * Get log badge level
     *
     * @param $level
     * @return string
     */
    public static function getLogBadgeLevel($level): string
    {
        return self::$levelClasses[$level] ?? 'info';
    }

    /**
     * Get type of Log
     *
     * @param $level
     * @return string
     */
    public static function getLevel($level): string
    {
        return self::$type[$level] ?? 'info';
    }

    /**
     * @var array|string[] $channels
     */
    private static array $channels = [
        self::CHANNEL_SQL => 'sql',
        self::CHANNEL_INFO => 'info',
        self::CHANNEL_FATAL => 'fatal',
        self::CHANNEL_DEBUG => 'debug',
        self::CHANNEL_WARNING => 'warning',
        self::CHANNEL_ACTIVITY => 'activity',
        self::CHANNEL_EXCEPTION => 'exception',
    ];

    /**
     * Get Channel
     *
     * @param $level
     * @return string
     */
    public static function getChannel($level): string
    {
        return self::$channels[$level] ?? '';
    }
}
