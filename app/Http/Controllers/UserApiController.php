<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ApiLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure user has an API Key
        if (empty($user->api_key)) {
            $user->api_key = Str::random(32);
            $user->save();
        }

        // 1. Calculate Metrics
        $totalAllTime = ApiLog::where('user_id', $user->id)->count();
        
        $todayCalls = ApiLog::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $monthCalls = ApiLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $successCalls = ApiLog::where('user_id', $user->id)
            ->whereBetween('status_code', [200, 299])
            ->count();

        $successRate = $totalAllTime > 0 ? round(($successCalls / $totalAllTime) * 100, 1) : 100;

        // 2. 30-day timeline chart
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $trend = ApiLog::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date_key"), DB::raw('COUNT(*) as total'))
            ->groupBy('date_key')
            ->pluck('total', 'date_key')
            ->toArray();

        $chartCategories = [];
        $chartSeries = [];
        $cursor = (clone $startDate);
        while ($cursor->lte($endDate)) {
            $k = $cursor->format('Y-m-%d');
            $chartCategories[] = $cursor->format('d M');
            $chartSeries[] = (int)($trend[$k] ?? 0);
            $cursor->addDay();
        }

        // 3. Recent 50 logs
        $logs = ApiLog::where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get();

        return view('modules.user_api', compact(
            'user',
            'totalAllTime',
            'todayCalls',
            'monthCalls',
            'successRate',
            'chartCategories',
            'chartSeries',
            'logs'
        ));
    }

    public function regenerate(Request $request)
    {
        $user = Auth::user();
        $newKey = Str::random(32);
        $user->update(['api_key' => $newKey]);

        return redirect()->route('user.api')->with('success', 'Kunci API (API Key) Anda berhasil diperbarui.');
    }
}
