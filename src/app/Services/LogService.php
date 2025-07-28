<?php

namespace phongtran\Logger\app\Services;

use phongtran\Logger\app\Models\Log;
use phongtran\Logger\app\Models\LogQuery;

class LogService extends AbsLogService
{
    /**
     * Store log into database
     *
     * @param string $channel
     * @param string $level
     * @param string|null $message
     * @return mixed
     */
    public function store(string $channel, string $level, ?string $message = ''): mixed
    {
        return Log::create([
            'channel' => $channel,
            'level' => $level,
            'message' => $message,
            'activity_id' => request()->attributes->get('activity_id') ?? null,
        ]);
    }

    /**
     * Get all with paginating
     *
     * @param string|null $channel
     * @param int $perPage
     * @param string $sort
     * @return mixed
     */
    public function get(?string $channel = null, int $perPage = 20, string $sort = 'desc'): mixed
    {
        // Validate parameters
        $perPage = max(1, min(100, $perPage)); // Limit perPage between 1 and 100
        $sort = in_array(strtolower($sort), ['asc', 'desc']) ? strtolower($sort) : 'desc';
        
        $query = Log::query();

        if ($channel && is_string($channel)) {
            $query->where('channel', $channel);
        }

        return $query->orderBy('created_at', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Show detail
     *
     * @param int $id
     * @return Log|null
     */
    public function show(int $id): ?Log
    {
        return Log::with(['logQueries'])->find($id);
    }

    /**
     * Update activity log
     *
     * @param int $id
     * @param $executionTime
     * @param array $response
     * @return Log|null
     */
    public function updateActivity(int $id, $executionTime, array $response = []): ?Log
    {
        $log = $this->show($id);
        
        if (!$log) {
            return null;
        }
        
        $log->execution_time = $executionTime;
        $log->response = json_encode($response);
        $log->save();

        return $log;
    }

    /**
     * Store SQL Query
     *
     * @param string|null $query
     * @param float $executionTime
     * @return mixed
     */
    public function storeSqlQuery(?string $query = '', float $executionTime = 0): mixed
    {
        return LogQuery::create([
            'query' => $query,
            'execution_time' => $executionTime ?? null,
        ]);
    }
}
