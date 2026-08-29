<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pixel;
use Illuminate\Support\Facades\Auth;

class PixelController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $totalPixels = Pixel::where('user_id', $userId)->count();

        $query = Pixel::where('user_id', $userId)->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('pixel', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $pixels = $query->paginate(15)->withQueryString();

        $supportedPlatforms = [
            'facebook' => ['name' => 'Meta (Facebook)', 'icon' => 'ki-facebook', 'color' => '#1877f2', 'placeholder' => 'e.g. 123456789012345'],
            'google_analytics' => ['name' => 'Google Analytics (GA4)', 'icon' => 'ki-chart-pie', 'color' => '#f59e0b', 'placeholder' => 'e.g. G-XXXXXXXXXX'],
            'google_tag_manager' => ['name' => 'Google Tag Manager', 'icon' => 'ki-code', 'color' => '#3b82f6', 'placeholder' => 'e.g. GTM-XXXXXXX'],
            'tiktok' => ['name' => 'TikTok Pixel', 'icon' => 'ki-music', 'color' => '#000000', 'placeholder' => 'e.g. CXXXXXXXXXXXXXXX'],
            'twitter' => ['name' => 'Twitter / X Pixel', 'icon' => 'ki-twitter', 'color' => '#1da1f2', 'placeholder' => 'e.g. oXXXX'],
            'pinterest' => ['name' => 'Pinterest Tag', 'icon' => 'ki-pin', 'color' => '#e60023', 'placeholder' => 'e.g. 261XXXXXXXXXX'],
            'linkedin' => ['name' => 'LinkedIn Insight Tag', 'icon' => 'ki-linkedin', 'color' => '#0a66c2', 'placeholder' => 'e.g. 123456'],
            'snapchat' => ['name' => 'Snapchat Pixel', 'icon' => 'ki-ghost', 'color' => '#fffc00', 'placeholder' => 'e.g. xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'],
        ];

        return view('modules.pixels', compact('pixels', 'totalPixels', 'supportedPlatforms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:64',
            'type' => 'required|string|max:64',
            'pixel' => 'required|string|max:64',
        ]);

        Pixel::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type,
            'pixel' => trim($request->pixel),
        ]);

        return back()->with('success', 'Tracking Pixel berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pixel = Pixel::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:64',
            'type' => 'required|string|max:64',
            'pixel' => 'required|string|max:64',
        ]);

        $pixel->update([
            'name' => $request->name,
            'type' => $request->type,
            'pixel' => trim($request->pixel),
        ]);

        return back()->with('success', 'Tracking Pixel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pixel = Pixel::where('user_id', Auth::id())->findOrFail($id);
        $pixel->delete();

        return back()->with('success', 'Tracking Pixel berhasil dihapus!');
    }
}
