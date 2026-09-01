<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('order')->get();

        // Calculate subscribers for each plan dynamically
        foreach ($plans as $plan) {
            $plan->users_count = User::where('plan_id', $plan->slug)->count();
        }

        $totalPlansCount = $plans->count();
        $totalSubscribers = User::count();
        $freeSubscribers = User::where('plan_id', 'free')->orWhereNull('plan_id')->orWhere('plan_id', '')->count();
        $paidSubscribers = User::whereNotIn('plan_id', ['free', '0', '', null])->count();

        return view('admin.modules.plans', compact(
            'plans',
            'totalPlansCount',
            'totalSubscribers',
            'freeSubscribers',
            'paidSubscribers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:50|unique:plans,slug',
            'description' => 'nullable|string|max:255',
            'monthly_price' => 'nullable|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',
            'lifetime_price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'badge' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Plan::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $settings = [
            'biolinks_limit' => $request->has('unlimited_biolinks') ? -1 : (int)($request->biolinks_limit ?? 15),
            'links_limit' => $request->has('unlimited_links') ? -1 : (int)($request->links_limit ?? 50),
            'projects_limit' => $request->has('unlimited_projects') ? -1 : (int)($request->projects_limit ?? 5),
            'domains_limit' => (int)($request->domains_limit ?? 0),
            'pixels_limit' => (int)($request->pixels_limit ?? 0),
            'custom_branding' => $request->has('custom_branding'),
            'statistics' => $request->statistics ?? 'basic',
            'verified_badge' => $request->has('verified_badge'),
            'dofollow_links' => $request->has('dofollow_links'),
        ];

        $nextOrder = (Plan::max('order') ?? 0) + 1;

        $plan = Plan::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'monthly_price' => $request->monthly_price ?? 0,
            'annual_price' => $request->annual_price ?? 0,
            'lifetime_price' => $request->lifetime_price ?? 0,
            'currency' => $request->currency ?? 'USD',
            'badge' => $request->badge,
            'color' => $request->color ?? '#3e97ff',
            'settings' => $settings,
            'order' => $nextOrder,
            'is_enabled' => $request->has('is_enabled') ? 1 : 0,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket langganan ' . $plan->name . ' berhasil dibuat.',
                'data' => $plan
            ]);
        }

        return redirect()->route('admin.plans')->with('success', 'Paket langganan baru berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'monthly_price' => 'nullable|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',
            'lifetime_price' => 'nullable|numeric|min:0',
            'badge' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
        ]);

        $settings = [
            'biolinks_limit' => $request->has('unlimited_biolinks') ? -1 : (int)($request->biolinks_limit ?? 15),
            'links_limit' => $request->has('unlimited_links') ? -1 : (int)($request->links_limit ?? 50),
            'projects_limit' => $request->has('unlimited_projects') ? -1 : (int)($request->projects_limit ?? 5),
            'domains_limit' => (int)($request->domains_limit ?? 0),
            'pixels_limit' => (int)($request->pixels_limit ?? 0),
            'custom_branding' => $request->has('custom_branding'),
            'statistics' => $request->statistics ?? 'basic',
            'verified_badge' => $request->has('verified_badge'),
            'dofollow_links' => $request->has('dofollow_links'),
        ];

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'monthly_price' => $request->monthly_price ?? 0,
            'annual_price' => $request->annual_price ?? 0,
            'lifetime_price' => $request->lifetime_price ?? 0,
            'currency' => $request->currency ?? $plan->currency,
            'badge' => $request->badge,
            'color' => $request->color ?? $plan->color,
            'settings' => $settings,
            'order' => $request->order ?? $plan->order,
            'is_enabled' => $request->has('is_enabled') ? 1 : 0,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket ' . $plan->name . ' berhasil diperbarui.',
                'data' => $plan
            ]);
        }

        return redirect()->route('admin.plans')->with('success', 'Paket ' . $plan->name . ' berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        if ($plan->slug === 'free') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paket Free adalah paket bawaan sistem dan tidak dapat dihapus.'
                ], 422);
            }
            return redirect()->route('admin.plans')->with('error', 'Paket Free adalah paket bawaan sistem dan tidak dapat dihapus.');
        }

        // Reassign any existing users to free plan before deleting
        User::where('plan_id', $plan->slug)->update(['plan_id' => 'free']);

        $plan->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket berhasil dihapus dan pengguna terkait telah dialihkan ke Free Plan.'
            ]);
        }

        return redirect()->route('admin.plans')->with('success', 'Paket berhasil dihapus dan pengguna terkait telah dialihkan ke Free Plan.');
    }
}
