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
        $channel = request()->query('channel');
        $perPage = request()->query('perPage', 20);
        $sort = request()->query('sort', 'desc');

        return view('logger.index', [
            'logs' => $this->logService->get($channel, (int)$perPage, $sort) ?? null,
            'currentChannel' => $channel ?? null,
        ]);
    }

    /**
     * Detail log
     *
     * @param $id
     * @return Factory|View|Application
     */
    public function detail($id): Factory|View|Application
    {
        return view('logger.detail', [
            'log' => $this->logService->show($id) ?? null,
        ]);
    }
}
