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

        Domain::create([
            'user_id' => null, // System domains don't belong to a specific user
            'scheme' => 'https://',
            'host' => strtolower(trim($request->host)),
            'custom_index_url' => $request->custom_index_url,
            'custom_not_found_url' => $request->custom_not_found_url,
            'type' => 1, // 1 = System Domain
            'is_enabled' => 1, // System domains are active by default
        ]);

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

        return back()->with('success', 'Domain updated successfully.');
    }

    public function destroy($id)
    {
        $domain = Domain::findOrFail($id);
        $domain->delete();

        return back()->with('success', 'Domain deleted successfully.');
    }
}
