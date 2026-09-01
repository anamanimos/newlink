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

        return back()->with('success', ucfirst(str_replace('-', ' ', $tab)) . ' settings updated successfully.');
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
