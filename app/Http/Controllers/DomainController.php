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
        $domains = Domain::where('user_id', $user->id)->latest()->get();
        
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
            return back()->with('error', 'You have reached your plan limit for custom domains.');
        }

        $request->validate([
            'host' => 'required|string|max:256|unique:domains,host',
            'custom_index_url' => 'nullable|url|max:256',
            'custom_not_found_url' => 'nullable|url|max:256',
        ]);

        Domain::create([
            'user_id' => $user->id,
            'scheme' => 'https://',
            'host' => strtolower(trim($request->host)),
            'custom_index_url' => $request->custom_index_url,
            'custom_not_found_url' => $request->custom_not_found_url,
            'type' => 0, // 0 = Custom Domain
            'is_enabled' => 0, // Pending admin approval by default
        ]);

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

        return back()->with('success', 'Domain updated successfully.');
    }

    public function destroy($id)
    {
        $domain = Domain::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $domain->delete();

        return back()->with('success', 'Domain deleted successfully.');
    }
}
