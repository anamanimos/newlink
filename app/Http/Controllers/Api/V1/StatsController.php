<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrackLink;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // User's link IDs
        $linkIds = Link::where('user_id', $user->id)->pluck('id')->toArray();

        $totalClicks = TrackLink::whereIn('link_id', $linkIds)->count();
        $todayClicks = TrackLink::whereIn('link_id', $linkIds)
            ->whereDate('datetime', Carbon::today())
            ->count();

        // Top 5 Countries
        $topCountries = TrackLink::whereIn('link_id', $linkIds)
            ->select('country_code', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country_code')
            ->where('country_code', '!=', '')
            ->groupBy('country_code')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get();

        // Top Devices
        $topDevices = TrackLink::whereIn('link_id', $linkIds)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderBy('total', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_clicks' => $totalClicks,
                'today_clicks' => $todayClicks,
                'top_countries' => $topCountries,
                'top_devices' => $topDevices,
            ]
        ]);
    }
}
