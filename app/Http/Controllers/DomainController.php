<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $domains = Domain::where('user_id', $user->id)
            ->withCount(['links', 'shortLinks', 'biolinks', 'waRotators'])
            ->latest()
            ->get();
        
        $planSettings = json_decode($user->plan_settings, true) ?? [];
        $domainLimit = $planSettings['domains_limit'] ?? 0;
        
        return view('modules.domains', compact('domains', 'domainLimit'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $planSettings = json_decode($user->plan_settings, true) ?? [];
        $domainLimit = $planSettings['domains_limit'] ?? 0;
        
        $currentDomainsCount = Domain::where('user_id', $user->id)->count();
        
        if ($domainLimit !== -1 && $currentDomainsCount >= $domainLimit) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda telah mencapai batas maksimum custom domain pada paket Anda.'
                ], 422);
            }
            return back()->with('error', 'You have reached your plan limit for custom domains.');
        }

        $request->validate([
            'host' => 'required|string|max:256|unique:domains,host',
            'custom_index_url' => 'nullable|url|max:256',
            'custom_not_found_url' => 'nullable|url|max:256',
        ]);

        $domain = Domain::create([
            'user_id' => $user->id,
            'scheme' => 'https://',
            'host' => strtolower(trim($request->host)),
            'custom_index_url' => $request->custom_index_url,
            'custom_not_found_url' => $request->custom_not_found_url,
            'type' => 0, // 0 = Custom Domain
            'is_enabled' => 0, // Pending admin approval by default
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Custom Domain berhasil dihubungkan. Menunggu persetujuan / verifikasi.',
                'data' => $domain
            ]);
        }

        return back()->with('success', 'Domain added successfully. Waiting for admin approval.');
    }

    public function update(Request $request, $id)
    {
        $domain = Domain::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'custom_index_url' => 'nullable|url|max:256',
            'custom_not_found_url' => 'nullable|url|max:256',
        ]);

        $domain->update([
            'custom_index_url' => $request->custom_index_url,
            'custom_not_found_url' => $request->custom_not_found_url,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan domain berhasil diperbarui.',
                'data' => $domain
            ]);
        }

        return back()->with('success', 'Domain updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $domain = Domain::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $domain->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Domain berhasil dihapus.'
            ]);
        }

        return back()->with('success', 'Domain deleted successfully.');
    }

    public function verifyDns($id)
    {
        $domain = Domain::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $result = \App\Services\DomainSslService::verifyDns($domain->host);

        $domain->dns_status = $result['verified'] ? 'verified' : 'failed';
        $domain->last_dns_check_at = now();

        if ($result['verified'] && ($result['is_cloudflare'] ?? false)) {
            $domain->ssl_status = 'active';
            $domain->scheme = 'https://';
            $domain->is_enabled = 1;
        }

        $domain->save();

        return response()->json([
            'success' => $result['verified'],
            'is_cloudflare' => $result['is_cloudflare'] ?? false,
            'message' => $result['message'],
            'server_ip' => $result['server_ip'],
            'resolved_ips' => $result['resolved_ips'],
            'dns_status' => $domain->dns_status,
            'ssl_status' => $domain->ssl_status,
        ]);
    }

    public function provisionSsl($id)
    {
        $domain = Domain::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $result = \App\Services\DomainSslService::provisionSsl($domain->host);

        if ($result['success']) {
            $domain->ssl_status = 'active';
            $domain->scheme = 'https://';
            $domain->is_enabled = 1;
        } else {
            $domain->ssl_status = 'failed';
        }
        $domain->save();

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'ssl_status' => $domain->ssl_status,
        ]);
    }
}
