<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\BiolinkBlock;
use Illuminate\Support\Facades\Auth;

class BiolinkController extends Controller
{
    public function builder($id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);
        $blocks = $link->biolinkBlocks;

        return view('biolinks.builder', compact('link', 'blocks'));
    }

    public function updateSettings(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096'
        ]);

        $settings = $link->settings ?? [];
        $settings['title'] = $request->title;
        $settings['description'] = $request->description;

        if ($request->has('settings') && is_array($request->input('settings'))) {
            foreach ($request->input('settings') as $key => $value) {
                $settings[$key] = $value;
            }
        }

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = 'avatar_' . $link->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            // Ensure uploads directory exists
            $avatar->move(public_path('uploads/biolinks'), $avatarName);
            $settings['avatar_url'] = '/uploads/biolinks/' . $avatarName;
        }

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverName = 'cover_' . $link->id . '_' . time() . '.' . $cover->getClientOriginalExtension();
            // Ensure uploads directory exists
            $cover->move(public_path('uploads/biolinks'), $coverName);
            $settings['cover_url'] = '/uploads/biolinks/' . $coverName;
        }

        $link->update(['settings' => $settings]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pengaturan profil biolink berhasil diperbarui!']);
        }

        return back()->with('success', 'Pengaturan profil biolink berhasil diperbarui!');
    }

    public function storeBlock(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);

        $request->validate([
            'type' => 'required|string|in:text,link,socials,whatsapp_rotator',
            'location_url' => 'nullable|url|max:512',
            'settings' => 'nullable|array'
        ]);

        $order = $link->biolinkBlocks()->max('order') + 1;

        $link->biolinkBlocks()->create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'location_url' => $request->location_url,
            'settings' => $request->settings ?? [],
            'order' => $order,
            'is_enabled' => 1
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blok berhasil ditambahkan!']);
        }

        return back()->with('success', 'Blok berhasil ditambahkan!');
    }

    public function updateBlock(Request $request, $id, $blockId)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);
        $block = $link->biolinkBlocks()->findOrFail($blockId);

        $request->validate([
            'location_url' => 'nullable|url|max:512',
            'settings' => 'nullable|array'
        ]);

        // merge settings to avoid overwriting un-passed fields if needed, or just replace
        $settings = array_merge($block->settings ?? [], $request->settings ?? []);

        $block->update([
            'location_url' => $request->location_url,
            'settings' => $settings
        ]);

        return back()->with('success', 'Blok berhasil diperbarui!');
    }

    public function destroyBlock($id, $blockId)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);
        $block = $link->biolinkBlocks()->findOrFail($blockId);
        
        $block->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blok berhasil dihapus!']);
        }

        return back()->with('success', 'Blok berhasil dihapus!');
    }

    public function reorderBlocks(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);
        
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'integer'
        ]);

        foreach ($request->orders as $blockId => $order) {
            $link->biolinkBlocks()->where('id', $blockId)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function blockAnalytics(Request $request, $id)
    {
        $block = \App\Models\BiolinkBlock::where('user_id', Auth::id())->findOrFail($id);
        $link = Link::findOrFail($block->link_id);

        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        // 1. Daily clicks (last 30 days)
        $dailyClicks = \App\Models\TrackLink::select(\Illuminate\Support\Facades\DB::raw('DATE(datetime) as date'), \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->where('biolink_block_id', $block->id)
            ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $clicksByDate = [];
        foreach ($dailyClicks as $day) {
            $clicksByDate[$day->date] = $day->count;
        }

        $chartDates = [];
        $chartData = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $chartDates[] = $date->format('d M');
            $chartData[] = $clicksByDate[$dateStr] ?? 0;
        }

        // 2. Top Referrers
        $topReferrers = \App\Models\TrackLink::select('referrer_host', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->where('biolink_block_id', $block->id)
            ->whereBetween('datetime', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->groupBy('referrer_host')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $referrersData = [];
        foreach ($topReferrers as $ref) {
            $referrersData[] = [
                'referrer' => empty($ref->referrer_host) ? 'Direct / Unknown' : $ref->referrer_host,
                'count' => $ref->count,
                'percent' => $block->clicks > 0 ? round(($ref->count / $block->clicks) * 100, 1) : 0
            ];
        }

        // Calculate CTR
        $ctr = $link->clicks > 0 ? round(($block->clicks / $link->clicks) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'title' => $block->settings['title'] ?? 'Tombol',
            'clicks' => number_format($block->clicks),
            'ctr' => $ctr . '%',
            'chartDates' => $chartDates,
            'chartData' => $chartData,
            'referrers' => $referrersData
        ]);
    }

    public function exportLeads($id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'biolink')->findOrFail($id);
        $blockIds = $link->biolinkBlocks()->pluck('id');
        
        $leads = \App\Models\WhatsappLead::whereIn('biolink_block_id', $blockIds)
            ->with('block')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'whatsapp_leads_' . $link->url . '_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Column Headers
            fputcsv($file, [
                'Waktu', 
                'Judul Rotator', 
                'Nama Pengisi', 
                'Kota/Kabupaten', 
                'Nomor WhatsApp Visitor', 
                'Pesan', 
                'WhatsApp Admin (Rotasi)', 
                'IP Address'
            ]);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->block->settings['title'] ?? 'WhatsApp Rotator',
                    $lead->name,
                    $lead->city,
                    $lead->phone,
                    $lead->message,
                    $lead->whatsapp_number_used,
                    $lead->ip
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
