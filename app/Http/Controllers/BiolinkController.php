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
