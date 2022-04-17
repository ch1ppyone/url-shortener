<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatService;

class StatisticsController extends Controller
{

    public function __construct(private  StatService  $statService)
    {
    }

    public function getDaily(string $ally)
    {
        return $this->statService->getDailyByAlly($ally);
    }

    public function getOverall()
    {
        return $this->statService->getOverall();
    }
}
