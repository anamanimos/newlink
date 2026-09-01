<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Link;
use App\Models\BiolinkBlock;
use App\Models\TrackLink;
use App\Models\Project;
use App\Models\Domain;
use App\Models\Pixel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request, $type = 'shortened-links')
    {
        // Allowed tabs
        $validTypes = [
            'users-growth',
            'users',
            'users-map',
            'database',
            'broadcasts',
            'users-notifications',
            'shortened-links',
            'biolink-pages',
            'links-statistics',
            'biolinks-blocks',
            'projects',
            'domains',
            'pixels',
            'whatsapp-leads',
            'api',
        ];

        if (!in_array($type, $validTypes)) {
            $type = 'shortened-links';
        }

        // 1. Date Range Handling
        $range = $request->get('range', 'all_time');
        $startDate = null;
        $endDate = Carbon::now()->endOfDay();

        switch ($range) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $dateLabel = 'Hari Ini (' . $startDate->format('d M Y') . ')';
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                $dateLabel = 'Kemarin (' . $startDate->format('d M Y') . ')';
                break;
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $dateLabel = '7 Hari Terakhir (' . $startDate->format('d M') . ' - ' . $endDate->format('d M Y') . ')';
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $dateLabel = '30 Hari Terakhir (' . $startDate->format('d M') . ' - ' . $endDate->format('d M Y') . ')';
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $dateLabel = 'Bulan Ini (' . $startDate->format('M Y') . ')';
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                $dateLabel = 'Bulan Lalu (' . $startDate->format('M Y') . ')';
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $dateLabel = 'Tahun Ini (' . $startDate->format('Y') . ')';
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
                    $dateLabel = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
                } else {
                    $startDate = Carbon::now()->subDays(29)->startOfDay();
                    $dateLabel = '30 Hari Terakhir';
                }
                break;
            case 'all_time':
            default:
                $range = 'all_time';
                $startDate = Carbon::createFromDate(2015, 1, 1)->startOfDay();
                $dateLabel = '1 Jan, 2015 - ' . $endDate->format('d M, Y');
                break;
        }

        // Calculate diff in days to pick grouping
        $diffDays = $startDate->diffInDays($endDate);

        // Grouping format
        if ($diffDays > 730) {
            // More than 2 years -> Group by Year
            $dateFormat = '%Y';
            $datePhpFormat = 'Y';
        } elseif ($diffDays > 90) {
            // More than 3 months -> Group by Month
            $dateFormat = '%Y-%m';
            $datePhpFormat = 'M Y';
        } else {
            // Day by day
            $dateFormat = '%Y-%m-%d';
            $datePhpFormat = 'd M';
        }

        // Initialize variables
        $chartCategories = [];
        $chartSeries = [];
        $totalInPeriod = 0;
        $totalAllTime = 0;
        $activeCount = 0;
        $extraData = [];
        $badgeChange = '+0';

        // 2. Fetch specific statistics data per type
        switch ($type) {
            case 'shortened-links':
                $totalAllTime = Link::whereIn('type', ['link', 'file', 'vcard', 'event', 'static'])->count();
                
                $query = Link::whereIn('type', ['link', 'file', 'vcard', 'event', 'static'])
                    ->whereBetween('created_at', [$startDate, $endDate]);
                
                $totalInPeriod = (clone $query)->count();
                $activeCount = Link::whereIn('type', ['link', 'file', 'vcard', 'event', 'static'])->where('is_enabled', 1)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                // Extra: Top 5 most clicked shortlinks
                $extraData['top_links'] = Link::whereIn('type', ['link', 'file', 'vcard', 'event', 'static'])
                    ->orderBy('clicks', 'DESC')
                    ->limit(5)
                    ->get();
                break;

            case 'biolink-pages':
                $totalAllTime = Link::where('type', 'biolink')->count();
                
                $query = Link::where('type', 'biolink')
                    ->whereBetween('created_at', [$startDate, $endDate]);
                
                $totalInPeriod = (clone $query)->count();
                $activeCount = Link::where('type', 'biolink')->where('is_enabled', 1)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                // Extra: Top 5 most clicked biolinks
                $extraData['top_biolinks'] = Link::where('type', 'biolink')
                    ->orderBy('clicks', 'DESC')
                    ->limit(5)
                    ->get();
                break;

            case 'users-growth':
            case 'users':
                $totalAllTime = User::count();
                
                $query = User::whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();
                $activeCount = User::where('status', 1)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                // Extra: Plan distribution
                $extraData['plan_distribution'] = User::select('plan_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('plan_id')
                    ->pluck('total', 'plan_id')
                    ->toArray();
                
                $extraData['status_active'] = $activeCount;
                $extraData['status_disabled'] = User::where('status', 0)->count();
                $extraData['admin_count'] = User::where('type', 1)->count();
                break;

            case 'users-map':
                $totalAllTime = User::count();
                $totalInPeriod = User::whereBetween('created_at', [$startDate, $endDate])->count();
                
                // Get top countries from users and track_links
                $countries = TrackLink::select('country_code', DB::raw('COUNT(*) as total'))
                    ->whereNotNull('country_code')
                    ->where('country_code', '!=', '')
                    ->groupBy('country_code')
                    ->orderBy('total', 'DESC')
                    ->limit(10)
                    ->get();

                $extraData['top_countries'] = $countries;
                $badgeChange = number_format($countries->sum('total')) . ' clicks';

                // Chart: Top countries as bar chart
                $chartCategories = $countries->pluck('country_code')->toArray();
                $chartSeries = $countries->pluck('total')->toArray();
                break;

            case 'database':
                $tables = DB::select('SHOW TABLE STATUS');
                $dbTotalRows = 0;
                $dbTotalSize = 0; // in bytes

                $tableList = [];
                foreach ($tables as $t) {
                    $rows = $t->Rows ?? 0;
                    $size = ($t->Data_length ?? 0) + ($t->Index_length ?? 0);
                    $dbTotalRows += $rows;
                    $dbTotalSize += $size;

                    $tableList[] = [
                        'name' => $t->Name,
                        'engine' => $t->Engine ?? 'InnoDB',
                        'rows' => $rows,
                        'size_mb' => round($size / (1024 * 1024), 2),
                    ];
                }

                usort($tableList, fn($a, $b) => $b['rows'] <=> $a['rows']);

                $totalAllTime = $dbTotalRows;
                $totalInPeriod = count($tableList);
                $extraData['table_list'] = $tableList;
                $extraData['db_size_mb'] = round($dbTotalSize / (1024 * 1024), 2);
                $badgeChange = $extraData['db_size_mb'] . ' MB';

                // Chart: Top 8 largest tables by rows
                $topTables = array_slice($tableList, 0, 8);
                $chartCategories = array_column($topTables, 'name');
                $chartSeries = array_column($topTables, 'rows');
                break;

            case 'links-statistics':
                $totalAllTime = TrackLink::count();
                
                $query = TrackLink::whereBetween('datetime', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(datetime, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                // Device distribution
                $extraData['devices'] = TrackLink::select('device_type', DB::raw('COUNT(*) as total'))
                    ->groupBy('device_type')
                    ->pluck('total', 'device_type')
                    ->toArray();

                // Top Referrers
                $extraData['top_referrers'] = TrackLink::select('referrer_host', DB::raw('COUNT(*) as total'))
                    ->whereNotNull('referrer_host')
                    ->where('referrer_host', '!=', '')
                    ->groupBy('referrer_host')
                    ->orderBy('total', 'DESC')
                    ->limit(5)
                    ->get();
                break;

            case 'biolinks-blocks':
                $totalAllTime = BiolinkBlock::count();
                
                $query = BiolinkBlock::whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                // Distribution by block type
                $extraData['block_types'] = BiolinkBlock::select('type', DB::raw('COUNT(*) as total'))
                    ->groupBy('type')
                    ->orderBy('total', 'DESC')
                    ->get();
                break;

            case 'projects':
                $totalAllTime = Project::count();
                $query = Project::whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);
                break;

            case 'domains':
                $totalAllTime = Domain::count();
                $query = Domain::whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();
                $activeCount = Domain::where('is_enabled', 1)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                $extraData['domains_list'] = Domain::with('user')->orderBy('created_at', 'DESC')->limit(10)->get();
                break;

            case 'pixels':
                $totalAllTime = Pixel::count();
                $query = Pixel::whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);

                $extraData['pixel_types'] = Pixel::select('type', DB::raw('COUNT(*) as total'))
                    ->groupBy('type')
                    ->orderBy('total', 'DESC')
                    ->get();
                break;

            case 'whatsapp-leads':
                $totalAllTime = DB::table('whatsapp_leads')->count();
                $query = DB::table('whatsapp_leads')->whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod);
                break;

            case 'api':
                $totalAllTime = \App\Models\ApiLog::count();
                $query = \App\Models\ApiLog::whereBetween('created_at', [$startDate, $endDate]);
                $totalInPeriod = (clone $query)->count();

                $trend = (clone $query)
                    ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_key"), DB::raw('COUNT(*) as total'))
                    ->groupBy('date_key')
                    ->orderBy('date_key', 'ASC')
                    ->pluck('total', 'date_key')
                    ->toArray();

                $chartData = $this->buildChartTimeline($startDate, $endDate, $diffDays, $trend);
                $chartCategories = $chartData['categories'];
                $chartSeries = $chartData['series'];
                $badgeChange = '+' . number_format($totalInPeriod) . ' calls';

                // Status code distribution
                $extraData['status_codes'] = \App\Models\ApiLog::select('status_code', DB::raw('COUNT(*) as total'))
                    ->groupBy('status_code')
                    ->orderBy('total', 'DESC')
                    ->get();

                // Top endpoints
                $extraData['top_endpoints'] = \App\Models\ApiLog::select('endpoint', 'method', DB::raw('COUNT(*) as total'), DB::raw('AVG(response_time_ms) as avg_latency'))
                    ->groupBy('endpoint', 'method')
                    ->orderBy('total', 'DESC')
                    ->limit(8)
                    ->get();

                // Top users
                $extraData['top_api_users'] = \App\Models\ApiLog::with('user')
                    ->whereNotNull('user_id')
                    ->select('user_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('user_id')
                    ->orderBy('total', 'DESC')
                    ->limit(5)
                    ->get();

                // Average latency
                $extraData['avg_latency'] = round(\App\Models\ApiLog::avg('response_time_ms') ?? 0, 2);
                break;

            default:
                $badgeChange = '+0';
                break;
        }

        return view('admin.modules.statistics', compact(
            'type',
            'range',
            'dateLabel',
            'startDate',
            'endDate',
            'chartCategories',
            'chartSeries',
            'totalInPeriod',
            'totalAllTime',
            'activeCount',
            'badgeChange',
            'extraData'
        ));
    }

    /**
     * Build continuous timeline categories and zero-filled series
     */
    private function buildChartTimeline($startDate, $endDate, $diffDays, $trendData)
    {
        $categories = [];
        $series = [];

        if ($diffDays > 730) {
            // By Year
            $startYear = (int)$startDate->format('Y');
            $endYear = (int)$endDate->format('Y');
            for ($y = $startYear; $y <= $endYear; $y++) {
                $categories[] = (string)$y;
                $series[] = (int)($trendData[(string)$y] ?? 0);
            }
        } elseif ($diffDays > 90) {
            // By Month
            $cursor = (clone $startDate)->startOfMonth();
            while ($cursor->lte($endDate)) {
                $k = $cursor->format('Y-m');
                $categories[] = $cursor->format('M Y');
                $series[] = (int)($trendData[$k] ?? 0);
                $cursor->addMonth();
            }
        } else {
            // By Day
            $cursor = (clone $startDate)->startOfDay();
            while ($cursor->lte($endDate)) {
                $k = $cursor->format('Y-m-d');
                $categories[] = $cursor->format('d M');
                $series[] = (int)($trendData[$k] ?? 0);
                $cursor->addDay();
            }
        }

        return [
            'categories' => $categories,
            'series' => $series,
        ];
    }
}
