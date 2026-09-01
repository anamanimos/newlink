<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::with('user')->latest()->get();
        return view('admin.modules.domains', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'host' => 'required|string|max:256|unique:domains,host',
            'custom_index_url' => 'nullable|url|max:256',
            'custom_not_found_url' => 'nullable|url|max:256',
        ]);

        $domain = Domain::create([
            'user_id' => null, // System domains don't belong to a specific user
            'scheme' => 'https://',
            'host' => strtolower(trim($request->host)),
            'custom_index_url' => $request->custom_index_url,
            'custom_not_found_url' => $request->custom_not_found_url,
            'type' => 1, // 1 = System Domain
            'is_enabled' => 1, // System domains are active by default
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'System Domain berhasil ditambahkan.',
                'data' => $domain
            ]);
        }

        return back()->with('success', 'System Domain added successfully.');
    }

    public function update(Request $request, $id)
    {
        $domain = Domain::findOrFail($id);

        $request->validate([
            'is_enabled' => 'required|boolean',
            'custom_index_url' => 'nullable|url|max:256',
            'custom_not_found_url' => 'nullable|url|max:256',
        ]);

        $domain->update([
            'is_enabled' => $request->is_enabled,
            'custom_index_url' => $request->custom_index_url,
            'custom_not_found_url' => $request->custom_not_found_url,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Domain berhasil diperbarui.',
                'data' => $domain
            ]);
        }

        return back()->with('success', 'Domain updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $domain = Domain::findOrFail($id);
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
        $domain = Domain::findOrFail($id);
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
        $domain = Domain::findOrFail($id);
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
