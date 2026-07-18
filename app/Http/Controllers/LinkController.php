<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Link;
use App\Models\Project;
use App\Models\Domain;

class LinkController extends Controller
{
    /**
     * Store a newly created shortened link.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'nullable|string|in:link,biolink,qrcode,card',
            'location_url' => 'required_if:type,link|nullable|url|max:2048',
            'url' => 'nullable|string|alpha_dash|max:256',
            'project_id' => 'nullable|integer',
            'domain_id' => 'nullable|integer',
        ]);

        $user = Auth::user();
        $type = $request->type ?? 'link';
        
        // Generate random string if custom alias path is empty
        $alias = $request->url;
        $domainId = $request->domain_id ?? 0;
        if (empty($alias)) {
            do {
                $alias = Str::random(6);
            } while (Link::where('url', $alias)->where('domain_id', $domainId)->exists());
        } else {
            // Check if alias is already taken
            if (Link::where('url', $alias)->where('domain_id', $domainId)->exists()) {
                return back()->withErrors(['url' => 'Alias URL ini sudah digunakan. Silakan gunakan alias lain.'])->withInput();
            }
        }

        $link = Link::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'domain_id' => $request->domain_id ?? 0,
            'type' => $type,
            'url' => $alias,
            'location_url' => $request->location_url,
            'clicks' => 0,
            'is_enabled' => 1,
        ]);

        if ($type === 'biolink') {
            return redirect()->route('biolinks.builder', $link->id)->with('success', 'Halaman Biolink berhasil dibuat!');
        }

        return back()->with('success', 'Tautan pendek berhasil dibuat!');
    }

    /**
     * Update the specified link.
     */
    public function update(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->findOrFail($id);

        $rules = [
            'url' => 'required|string|alpha_dash|max:256',
            'project_id' => 'nullable|integer',
            'domain_id' => 'nullable|integer',
        ];

        if ($link->type === 'link') {
            $rules['location_url'] = 'required|url|max:2048';
        } else {
            $rules['location_url'] = 'nullable|url|max:2048';
        }

        $request->validate($rules);

        // Check if alias is taken by another link
        $domainId = $request->domain_id ?? 0;
        if (Link::where('url', $request->url)->where('domain_id', $domainId)->where('id', '!=', $id)->exists()) {
            return back()->withErrors(['url' => 'Alias URL ini sudah digunakan oleh link lain.'])->withInput();
        }

        $link->update([
            'project_id' => $request->project_id,
            'domain_id' => $request->domain_id ?? 0,
            'url' => $request->url,
            'location_url' => $link->type === 'link' ? $request->location_url : $link->location_url,
        ]);

        return back()->with('success', 'Tautan pendek berhasil diperbarui!');
    }

    /**
     * Delete the specified link.
     */
    public function destroy($id)
    {
        $link = Link::where('user_id', Auth::id())->findOrFail($id);
        $link->delete();

        return back()->with('success', 'Tautan pendek berhasil dihapus!');
    }

    /**
     * Show the detailed analytics and information for a specific link.
     */
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $link = Link::where('user_id', $user->id)->findOrFail($id);

        $currentRoute = $request->route()->getName();
        if ($link->type === 'biolink' && $currentRoute !== 'biolinks.show') {
            return redirect()->route('biolinks.show', array_merge(['id' => $id], $request->query()));
        }
        if ($link->type === 'warotator' && $currentRoute !== 'warotators.show') {
            return redirect()->route('warotators.show', array_merge(['id' => $id], $request->query()));
        }
        if ($link->type === 'link' && $currentRoute !== 'links.show') {
            return redirect()->route('links.show', array_merge(['id' => $id], $request->query()));
        }

        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date'))->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();

        // Fetch click data from legacy DB if possible
        $clicksByDate = [];
        $topReferrers = [];
        $topCountries = [];
        $topDevices = [];
        $topOs = [];
        $topBrowsers = [];
        $rawClicks = collect();
        
        $totalClicks = $link->clicks;
        $uniqueClicks = 0;

        try {
            // Daily Clicks for Chart
            $dailyClicks = \App\Models\TrackLink::select(\Illuminate\Support\Facades\DB::raw('DATE(datetime) as date'), \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();
                
            foreach ($dailyClicks as $day) {
                $clicksByDate[$day->date] = $day->count;
            }

            // Top Referrers
            $topReferrers = \App\Models\TrackLink::select('referrer_host', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->groupBy('referrer_host')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            // Top Countries
            $topCountries = \App\Models\TrackLink::select('country_code', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->groupBy('country_code')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            // Top OS & Browser
            $topOs = \App\Models\TrackLink::select('os', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->groupBy('os')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
                
            $topBrowsers = \App\Models\TrackLink::select('browser', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->groupBy('browser')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            // Unique Clicks (based on distinct IP)
            $uniqueClicks = \App\Models\TrackLink::where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereNotNull('ip')
                ->distinct('ip')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->count();
                
            // Raw Paginated Clicks
            $rawClicks = \App\Models\TrackLink::where('link_id', $link->link_id ?? $link->id)
                ->whereNull('biolink_block_id')
                ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->orderBy('datetime', 'DESC')
                ->paginate(25)
                ->withQueryString();
                
            // Calculate uniqueness for the current page items
            foreach ($rawClicks as $click) {
                if (empty($click->ip)) {
                    $click->is_unique = false;
                } else {
                    $previousClickExists = \App\Models\TrackLink::where('link_id', $link->link_id ?? $link->id)
                        ->where('ip', $click->ip)
                        ->where('id', '<', $click->id)
                        ->exists();
                    $click->is_unique = !$previousClickExists;
                }
            }
                
        } catch (\Exception $e) {
            // Silently handle if legacy DB is unavailable
            // Use empty paginator for view
            $rawClicks = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 25);
        }

        // Fill empty dates for chart to show continuous line between start and end
        $chartDates = [];
        $chartData = [];
        
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        // If period is too long, limit the chart or just show it all. For simple logic, we show all dates in the range.
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $chartDates[] = $date->format('d M');
            $chartData[] = $clicksByDate[$dateStr] ?? 0;
        }
        $biolinkBlocks = collect();
        $whatsappLeads = collect();
        if ($link->type === 'biolink') {
            $biolinkBlocks = $link->biolinkBlocks()->where('type', 'link')->orderByDesc('clicks')->get();
            $blockIds = $link->biolinkBlocks()->pluck('id');
            $whatsappLeads = \App\Models\WhatsappLead::whereIn('biolink_block_id', $blockIds)
                ->orderBy('created_at', 'desc')
                ->paginate(25, ['*'], 'leads_page');
        } elseif ($link->type === 'warotator') {
            $whatsappLeads = \App\Models\WhatsappLead::where('link_id', $link->id)
                ->orderBy('created_at', 'desc')
                ->paginate(25, ['*'], 'leads_page');
        }

        if ($link->type === 'biolink') {
            $viewName = 'biolinks.show';
        } elseif ($link->type === 'warotator') {
            $viewName = 'warotators.show';
        } else {
            $viewName = 'links.show';
        }

        return view($viewName, compact(
            'link', 'totalClicks', 'uniqueClicks', 'chartDates', 'chartData', 
            'topReferrers', 'topCountries', 'topOs', 'topBrowsers', 'startDate', 'endDate', 'rawClicks', 'biolinkBlocks', 'whatsappLeads'
        ));
    }

    /**
     * Check if a URL alias is available on a specific domain.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'url' => 'required|string|alpha_dash|max:256',
            'domain_id' => 'required|integer',
            'exclude_id' => 'nullable|integer'
        ]);

        $query = Link::where('url', $request->url)
            ->where('domain_id', $request->domain_id);
            
        if ($request->exclude_id) {
            $query->where('id', '!=', $request->exclude_id);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }

    /**
     * Perform bulk actions on selected links.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:links,id',
            'action' => 'required|string|in:delete,enable,disable'
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $userId = Auth::id();

        // Target only links belonging to the authenticated user
        $links = Link::where('user_id', $userId)->whereIn('id', $ids);

        if ($action === 'delete') {
            $links->delete();
            $message = 'Tautan terpilih berhasil dihapus!';
        } elseif ($action === 'enable') {
            $links->update(['is_enabled' => 1]);
            $message = 'Tautan terpilih berhasil diaktifkan!';
        } elseif ($action === 'disable') {
            $links->update(['is_enabled' => 0]);
            $message = 'Tautan terpilih berhasil dinonaktifkan!';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Toggle the active status of a link.
     */
    public function toggleStatus(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->findOrFail($id);
        $link->update([
            'is_enabled' => $request->is_enabled ? 1 : 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status tautan berhasil diubah!',
            'is_enabled' => $link->is_enabled
        ]);
    }
}
