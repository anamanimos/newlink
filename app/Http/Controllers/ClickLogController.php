<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackLink;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClickLogController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Statistics
        $totalClicksToday = TrackLink::where('user_id', $userId)
            ->whereDate('datetime', $today)
            ->count();

        $totalClicksYesterday = TrackLink::where('user_id', $userId)
            ->whereDate('datetime', Carbon::yesterday())
            ->count();

        $totalClicksMonth = TrackLink::where('user_id', $userId)
            ->where('datetime', '>=', $startOfMonth)
            ->count();

        $totalClicksAllTime = TrackLink::where('user_id', $userId)->count();

        // Top Country
        $topCountry = TrackLink::where('user_id', $userId)
            ->whereNotNull('country_code')
            ->select('country_code', DB::raw('count(*) as count'))
            ->groupBy('country_code')
            ->orderByDesc('count')
            ->first();

        // Top Referrer
        $topReferrer = TrackLink::where('user_id', $userId)
            ->whereNotNull('referrer_host')
            ->where('referrer_host', '!=', '')
            ->select('referrer_host', DB::raw('count(*) as count'))
            ->groupBy('referrer_host')
            ->orderByDesc('count')
            ->first();

        // Device Breakdown
        $devices = TrackLink::where('user_id', $userId)
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        $mobileCount = $devices['mobile'] ?? 0;
        $desktopCount = $devices['desktop'] ?? 0;
        $tabletCount = $devices['tablet'] ?? 0;
        $deviceTotal = max(1, $mobileCount + $desktopCount + $tabletCount);

        $mobilePct = round(($mobileCount / $deviceTotal) * 100);
        $desktopPct = round(($desktopCount / $deviceTotal) * 100);
        $tabletPct = round(($tabletCount / $deviceTotal) * 100);

        // 2. Query Click Activity
        $query = TrackLink::with(['link.domain', 'link.project', 'biolinkBlock'])
            ->where('user_id', $userId);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip', 'like', "%{$search}%")
                  ->orWhere('city_name', 'like', "%{$search}%")
                  ->orWhere('country_code', 'like', "%{$search}%")
                  ->orWhere('referrer_host', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('os', 'like', "%{$search}%")
                  ->orWhereHas('link', function ($lq) use ($search) {
                      $lq->where('url', 'like', "%{$search}%")
                         ->orWhere('location_url', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('link_id')) {
            $query->where('link_id', $request->link_id);
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        if ($request->filled('country_code')) {
            $query->where('country_code', strtoupper($request->country_code));
        }

        if ($request->filled('referrer')) {
            $query->where('referrer_host', 'like', "%{$request->referrer}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('datetime', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('datetime', '<=', $request->date_to);
        }

        $resultsPerPage = in_array((int)$request->results_per_page, [15, 25, 50, 100]) ? (int)$request->results_per_page : 25;

        $clickLogs = $query->orderBy('datetime', 'desc')->paginate($resultsPerPage)->withQueryString();

        // User Links for filter dropdown
        $userLinks = Link::where('user_id', $userId)->orderBy('url')->get();

        return view('modules.clicks', compact(
            'clickLogs',
            'totalClicksToday',
            'totalClicksYesterday',
            'totalClicksMonth',
            'totalClicksAllTime',
            'topCountry',
            'topReferrer',
            'mobilePct',
            'desktopPct',
            'tabletPct',
            'mobileCount',
            'desktopCount',
            'tabletCount',
            'userLinks'
        ));
    }
}
