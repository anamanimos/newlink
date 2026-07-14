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
            'type' => 'required|string|in:text,link,socials',
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
}
