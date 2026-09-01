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

        $settings = \App\Models\Setting::get($tab, []);
        
        return view('admin.modules.settings', compact('tab', 'settings'));
    }

    public function updateSettings(Request $request, $tab = 'main')
    {
        $data = $request->except(['_token', '_method']);

        if ($tab === 'payment') {
            // Process payment settings toggles and currencies
            $currencies = [];
            if ($request->has('currencies_code') && is_array($request->currencies_code)) {
                foreach ($request->currencies_code as $idx => $code) {
                    $code = strtoupper(trim($code));
                    if ($code) {
                        $currencies[$code] = [
                            'code' => $code,
                            'symbol' => $request->currencies_symbol[$idx] ?? '$',
                            'default_payment_processor' => $request->currencies_default_processor[$idx] ?? 'paypal',
                        ];
                    }
                }
            }

            $paymentData = [
                'is_enabled' => $request->has('is_enabled'),
                'type' => $request->input('type', 'both'),
                'default_payment_type' => $request->input('default_payment_type', 'one_time'),
                'default_payment_frequency' => $request->input('default_payment_frequency', 'monthly'),
                'currencies' => $currencies,
                'default_currency' => strtoupper($request->input('default_currency', 'USD')),
                'codes_is_enabled' => $request->has('codes_is_enabled'),
                'taxes_and_billing_is_enabled' => $request->has('taxes_and_billing_is_enabled'),
                'invoices_is_enabled' => $request->has('invoices_is_enabled'),
                'user_plan_expiry_reminder' => (int) $request->input('user_plan_expiry_reminder', 0),
                'user_plan_expiry_checker_is_enabled' => $request->has('user_plan_expiry_checker_is_enabled'),
                'currency_exchange_api_key' => $request->input('currency_exchange_api_key', ''),
            ];

            \App\Models\Setting::set('payment', $paymentData);
        } else {
            \App\Models\Setting::set($tab, $data);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('-', ' ', $tab)) . ' settings berhasil diperbarui.'
            ]);
        }

        return back()->with('success', ucfirst(str_replace('-', ' ', $tab)) . ' settings updated successfully.');
    }

    /**
     * Show the links administration page with multi-filter & type support.
     */
    public function links(Request $request)
    {
        $query = Link::with(['user', 'domain', 'project'])
            ->withCount('biolinkBlocks');

        // Search Filter (Slug, Destination URL, User Name, User Email)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('location_url', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Type Filter (biolink, link, warotator, qrcode, etc.)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Domain Filter
        if ($request->filled('domain_id')) {
            if ($request->domain_id === '0' || $request->domain_id === 'default') {
                $query->where(function($q) {
                    $q->whereNull('domain_id')->orWhere('domain_id', 0);
                });
            } else {
                $query->where('domain_id', $request->domain_id);
            }
        }

        // User / Owner Filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Status Filter (Active / Inactive)
        if ($request->filled('status')) {
            if ($request->status === 'active' || $request->status === '1') {
                $query->where('is_enabled', 1);
            } elseif ($request->status === 'inactive' || $request->status === '0') {
                $query->where('is_enabled', 0);
            }
        }

        // Verified Filter (Verified / Unverified)
        if ($request->filled('verified')) {
            if ($request->verified === 'yes' || $request->verified === '1') {
                $query->where('is_verified', 1);
            } elseif ($request->verified === 'no' || $request->verified === '0') {
                $query->where(function($q) {
                    $q->where('is_verified', 0)->orWhereNull('is_verified');
                });
            }
        }

        // Sort Order
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'clicks_desc') {
            $query->orderBy('clicks', 'desc');
        } elseif ($sort === 'clicks_asc') {
            $query->orderBy('clicks', 'asc');
        } elseif ($sort === 'url_asc') {
            $query->orderBy('url', 'asc');
        } elseif ($sort === 'url_desc') {
            $query->orderBy('url', 'desc');
        } else {
            $query->latest();
        }

        // Per Page Pagination
        $perPage = (int) $request->get('per_page', 25);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 25;
        }

        $links = $query->paginate($perPage)->withQueryString();

        // Dropdown Data
        $domains = Domain::orderBy('host')->get();
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        // Statistics Count
        $totalAll = Link::count();
        $totalBiolink = Link::where('type', 'biolink')->count();
        $totalShortlink = Link::where('type', 'link')->count();
        $totalWaRotator = Link::where('type', 'warotator')->count();
        $totalQrCode = Link::where('type', 'qrcode')->count();

        return view('admin.modules.links', compact(
            'links',
            'domains',
            'users',
            'totalAll',
            'totalBiolink',
            'totalShortlink',
            'totalWaRotator',
            'totalQrCode'
        ));
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

    /**
     * Toggle active status of a link (Admin).
     */
    public function toggleStatusLink(Request $request, $id)
    {
        $link = Link::findOrFail($id);
        $link->update(['is_enabled' => !$link->is_enabled]);

        return response()->json([
            'success' => true,
            'is_enabled' => (bool)$link->is_enabled,
            'message' => $link->is_enabled ? 'Link berhasil diaktifkan!' : 'Link berhasil dinonaktifkan!'
        ]);
    }

    /**
     * Delete a link (Admin).
     */
    public function destroyLink(Request $request, $id)
    {
        $link = Link::findOrFail($id);
        $link->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Link berhasil dihapus.'
            ]);
        }

        return back()->with('success', 'Link berhasil dihapus.');
    }
}
