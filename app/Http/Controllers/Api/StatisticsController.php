<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getDaily($ally)
    {
        return DB::table('journals')
        ->join('links', 'journals.link_id', '=', 'links.id')
            ->select(
                DB::raw(' count(*) as total_views, count(distinct user_agent, ip) as unique_views, DATE(journals.created_at) as date')
            )->where('short_ally', $ally)
            ->orderBy('date', 'desc')
            ->groupBy('date')
            ->get()
            ->all();
    }

    public function getOverall()
    {
        $total = Journal::count();
        $unique = Journal::select('ip', 'user_agent', 'link_id')->distinct()->get()->count();
        return response()->json(['total_views' => $total, 'unique_views' => $unique]);
    }
}
