<?php

namespace phongtran\Logger\app\Http\Middleware;

use Illuminate\Http\Request;
use phongtran\Logger\Logger;
use phongtran\Logger\app\Services\AbsLogService;
use phongtran\Logger\App\Http\Traits\LogActivityTrait;
use Closure;

/**
 * LogActivity Middleware
 *
 * @package phongtran\Logger\app\Http\Middleware
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
class LogActivity
{
    use LogActivityTrait;

    /**
     * @var float $startTime
     */
    protected float $startTime;

    /**
     * Constructor
     *
     * @param AbsLogService $logService
     */
    public function __construct(
        protected AbsLogService $logService,
    )
    {
        $this->startTime = microtime(true);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $route = $request->route();
        $routeArray = $this->routeToArray($route);
        $activity = Logger::activity($this->formatRouteLog($routeArray));
        $request->attributes->set('activity_id', $activity->id);
        $response = $next($request);
        $endTime = microtime(true);
        app()->call([$this->logService, 'updateActivity'], [
            'id' => $activity->id,
            'executionTime' => number_format($endTime - $this->startTime, 3),
            'response' => $this->getResponse($response),
        ]);

        return $response;
    }
}
