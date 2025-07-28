<?php

namespace phongtran\Logger\app\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use phongtran\Logger\app\Services\AbsLogService;

/**
 * Logger Controller
 *
 * @package phongtran\Logger\app\Http\Controllers
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author phongtran <jarvis.phongtran@gmail.com>
 */
class LoggerController
{
    /**
     * Constructor
     *
     * @param AbsLogService $logService
     */
    public function __construct(
        protected AbsLogService $logService,
    ) {}

    /**
     * Logger dashboard
     *
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        try {
            $channel = request()->query('channel');
            $perPage = request()->query('perPage', 20);
            $sort = request()->query('sort', 'desc');

            return view('logger.index', [
                'logs' => $this->logService->get($channel, (int)$perPage, $sort) ?? null,
                'currentChannel' => $channel ?? null,
            ]);
        } catch (\Exception $e) {
            // Return a simple response if view is not found
            return response()->json([
                'message' => 'Logger dashboard is not available',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detail log
     *
     * @param $id
     * @return Factory|View|Application
     */
    public function detail($id): Factory|View|Application
    {
        try {
            return view('logger.detail', [
                'log' => $this->logService->show($id) ?? null,
            ]);
        } catch (\Exception $e) {
            // Return a simple response if view is not found
            return response()->json([
                'message' => 'Logger detail is not available',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
