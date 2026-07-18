<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Link;
use App\Models\Project;
use App\Models\Domain;

class WaRotatorController extends Controller
{
    /**
     * Show the standalone WhatsApp Rotator creation page.
     */
    public function create()
    {
        $projects = Project::where('user_id', Auth::id())->get();
        $domains = Domain::where('user_id', Auth::id())->orWhere(function($q) {
            $q->where('type', 1)->where('is_enabled', 1);
        })->get();

        return view('warotators.create', compact('projects', 'domains'));
    }

    /**
     * Store a new WhatsApp Rotator link.
     */
    public function store(Request $request)
    {
        $domainId = $request->domain_id && $request->domain_id > 0 ? $request->domain_id : 0;

        $request->validate([
            'url' => [
                'required',
                'alpha_dash',
                'max:64',
                \Illuminate\Validation\Rule::unique('links', 'url')->where(function ($query) use ($domainId) {
                    return $query->where('domain_id', $domainId);
                })
            ],
            'title' => 'required|string|max:100',
            'numbers' => 'required|string',
            'template' => 'required|string',
            'button_text' => 'required|string|max:100',
            'cities' => 'nullable|string',
            'project_id' => 'nullable|integer',
            'domain_id' => 'nullable|integer'
        ]);

        $user = Auth::user();

        // Create link entry
        $link = Link::create([
            'user_id' => $user->id,
            'url' => $request->url,
            'location_url' => '',
            'type' => 'warotator',
            'is_enabled' => 1,
            'project_id' => $request->project_id ?? null,
            'domain_id' => $request->domain_id && $request->domain_id > 0 ? $request->domain_id : 0,
            'settings' => [
                'title' => $request->title,
                'description' => $request->description ?? '',
                'numbers' => $request->numbers,
                'template' => $request->template,
                'button_text' => $request->button_text,
                'cities' => $request->cities ?? 'Jakarta, Bandung, Surabaya, Semarang, Yogyakarta, Medan',
                'bg_type' => 'solid',
                'bg_color' => '#f3f4f6',
                'btn_bg_color' => '#2ac3a6',
                'btn_text_color' => '#ffffff',
                'text_color' => '#111827',
                'avatar_url' => '',
                'banner_url' => ''
            ]
        ]);

        return redirect()->route('warotators.builder', $link->id)->with('success', 'WhatsApp Rotator berhasil dibuat!');
    }

    /**
     * Show the WhatsApp Rotator builder layout.
     */
    public function builder($id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'warotator')->findOrFail($id);
        
        $projects = Project::where('user_id', Auth::id())->get();
        $domains = Domain::where('user_id', Auth::id())->orWhere(function($q) {
            $q->where('type', 1)->where('is_enabled', 1);
        })->get();

        return view('warotators.builder', compact('link', 'projects', 'domains'));
    }

    /**
     * Update WhatsApp Rotator settings.
     */
    public function updateSettings(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'warotator')->findOrFail($id);

        $domainId = $request->has('domain_id') 
            ? ($request->domain_id && $request->domain_id > 0 ? $request->domain_id : 0)
            : ($link->domain_id ?? 0);

        $request->validate([
            'url' => [
                'sometimes',
                'required',
                'alpha_dash',
                'max:64',
                \Illuminate\Validation\Rule::unique('links', 'url')->where(function ($query) use ($domainId) {
                    return $query->where('domain_id', $domainId);
                })->ignore($link->id)
            ],
            'project_id' => 'nullable|integer',
            'domain_id' => 'nullable|integer',
            'settings' => 'nullable|array'
        ]);

        // Merge inputs
        $settings = array_merge($link->settings ?? [], $request->settings ?? []);

        // Handle Avatar File Upload via Ajax or standard POST
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $name = time() . '_avatar_' . $link->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $name);
            $settings['avatar_url'] = url('uploads/avatars/' . $name);
        }

        // Handle Cover/Banner File Upload
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $name = time() . '_cover_' . $link->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $name);
            $settings['banner_url'] = url('uploads/covers/' . $name);
        }

        $updateData = [
            'settings' => $settings
        ];

        if ($request->has('url')) {
            $updateData['url'] = $request->url;
        }
        if ($request->has('project_id')) {
            $updateData['project_id'] = $request->project_id ?? null;
        }
        if ($request->has('domain_id')) {
            $updateData['domain_id'] = $request->domain_id && $request->domain_id > 0 ? $request->domain_id : 0;
        }

        $link->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Pengaturan WhatsApp Rotator berhasil diperbarui!',
                'avatar_url' => $settings['avatar_url'] ?? '',
                'banner_url' => $settings['banner_url'] ?? ''
            ]);
        }

        return back()->with('success', 'Pengaturan WhatsApp Rotator berhasil diperbarui!');
    }

    /**
     * Export Leads as CSV.
     */
    public function exportLeads($id)
    {
        $link = Link::where('user_id', Auth::id())->where('type', 'warotator')->findOrFail($id);
        
        $leads = \App\Models\WhatsappLead::where('link_id', $link->id)
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

        $callback = function() use ($leads, $link) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Column Headers
            fputcsv($file, [
                'Waktu', 
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
