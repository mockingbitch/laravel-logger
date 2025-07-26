<?php

namespace phongtran\Logger\app\Http\Traits;

use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * LogActivityTrait
 *
 * @package phongtran\Logger\app\Http\Traits
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
trait LogActivityTrait
{
    /**
     * Get route's information from request
     *
     * @param $route
     * @return array
     */
    public function routeToArray($route): array
    {
        return [
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'routeName' => $route->getName(),
            'action' => $route->getAction(),
            'parameters' => $route->parameters(),
            'middleware' => $route->gatherMiddleware(),
        ];
    }

    /**
     * Convert route details to a readable log string.
     *
     * @param array $routeArray
     * @return string
     */
    public function formatRouteLog(array $routeArray): string
    {
        return sprintf(
            "uri: %s, params: %s, method: %s, route: %s, handler: %s, middleware: %s",
            $routeArray['uri'] ?? 'unknown uri',
            implode(',', $routeArray['parameters'] ?? []) ?: '[]',
            $routeArray['methods'][0] ?? 'unknown method',
            $routeArray['routeName'] ?? 'unknown route',
            $routeArray['action']['controller'] ?? 'unknown handler',
            implode(',', self::removeValue($routeArray['middleware'], 'activity') ?? []) ?: '[]',

        );
    }

    /**
     * Remove value
     *
     * @param array $array
     * @param $value
     * @return array
     */
    public static function removeValue(array $array, $value): array
    {
        return array_values(array_filter($array, fn($v) => $v !== $value));
    }

    /**
     * Get response
     *
     * @param $response
     * @return array|View
     */
    public function getResponse($response): array|\Illuminate\View\View
    {
        $data = [];
        $original = $response->original;
        $data['status_code'] = $response->getStatusCode();
        $data['type'] = 'unknown';
        if ($response instanceof \Illuminate\Http\Response) {
            if ($original instanceof \Illuminate\View\View) {
                $data['views'] = $original->getName();
                $data['data'] = $original->getData();
                $data['path'] = $original->getPath();
                $data['type'] = 'views response';
            }
        }
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            $data['target_url'] = $response->getTargetUrl();
            $data['type'] = 'redirect response';
        }
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $original;
            $data['type'] = 'json response';
        }
        if ($response instanceof StreamedResponse) {
            $data['type'] = 'streamed response';
        }
        if ($response instanceof BinaryFileResponse) {
            $data['file_path'] = $response->getFile()->getRealPath();
            $data['type'] = 'binary file response';
        }

        return $data;
    }
}
