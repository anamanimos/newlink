<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Link;
use App\Models\Domain;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $startOfMonth = now()->startOfMonth();

        // Calculate statistics
        $biolinksCount = Link::where('type', 'biolink')->count();
        $biolinksThisMonth = Link::where('type', 'biolink')->where('created_at', '>=', $startOfMonth)->count();

        $shortLinksCount = Link::where('type', 'link')->count();
        $shortLinksThisMonth = Link::where('type', 'link')->where('created_at', '>=', $startOfMonth)->count();

        $totalPageviews = Link::sum('clicks');
        $pageviewsThisMonth = 0;
        try {
            $pageviewsThisMonth = DB::connection('legacy')->table('track_links')->where('datetime', '>=', $startOfMonth->toDateTimeString())->count();
        } catch (\Exception $e) {
            $pageviewsThisMonth = 0;
        }

        $qrCodesCount = 0; // Mocked
        $qrCodesThisMonth = 0;

        $domainsCount = Domain::count();
        $domainsThisMonth = Domain::where('created_at', '>=', $startOfMonth)->count();

        $usersCount = User::count();
        $usersThisMonth = User::where('created_at', '>=', $startOfMonth)->count();

        $paymentsCount = 0; // Mocked
        $paymentsThisMonth = 0;

        $earnedCount = 0; // Mocked
        $earnedThisMonth = 0;

        // Fetch latest users
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'biolinksCount',
            'biolinksThisMonth',
            'shortLinksCount',
            'shortLinksThisMonth',
            'totalPageviews',
            'pageviewsThisMonth',
            'qrCodesCount',
            'qrCodesThisMonth',
            'domainsCount',
            'domainsThisMonth',
            'usersCount',
            'usersThisMonth',
            'paymentsCount',
            'paymentsThisMonth',
            'earnedCount',
            'earnedThisMonth',
            'latestUsers'
        ));
    }

    /**
     * Show the system settings page tabs.
     */
    public function settings($tab = 'main')
    {
        $allowedTabs = [
            'main', 'users', 'content', 'links', 'tools', 'codes', 
            'payment', 'business', 'affiliate', 'captcha', 
            'ads', 'cookie-consent', 'socials', 'smtp', 'theme', 'custom',
            'email-notifications', 'push-notifications', 'webhooks', 'offload', 
            'pwa', 'sso', 'cron', 'health', 'cache', 'license', 'support'
        ];
        
        if (!in_array($tab, $allowedTabs)) {
            $tab = 'main';
        }
        
        return view('admin.modules.settings', compact('tab'));
    }

    /**
     * Show the links administration page.
     */
    public function links(Request $request)
    {
        $query = Link::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('location_url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $links = $query->latest()->paginate(25);

        return view('admin.modules.links', compact('links'));
    }

    /**
     * Toggle the verified status of a link (Admin only).
     */
    public function toggleVerify(Request $request, $id)
    {
        $link = Link::findOrFail($id);
        $link->update(['is_verified' => !$link->is_verified]);

        return response()->json([
            'success' => true,
            'is_verified' => (bool)$link->is_verified,
            'message' => $link->is_verified ? 'Link berhasil diverifikasi!' : 'Verifikasi link berhasil dicabut!'
        ]);
    }
}
