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
