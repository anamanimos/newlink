<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Link;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 1)->count();
        $adminUsers = User::where('type', 1)->count();
        $proUsers = User::whereNotIn('plan_id', ['free', '0', ''])->count();

        $query = User::withCount([
            'links',
            'links as biolinks_count' => function ($q) {
                $q->where('type', 'biolink');
            },
            'links as shortlinks_count' => function ($q) {
                $q->where('type', 'link');
            },
            'domains',
            'pixels'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '' && $request->status !== null) {
            $query->where('status', (int)$request->status);
        }

        if ($request->has('type') && $request->type !== '' && $request->type !== null) {
            $query->where('type', (int)$request->type);
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        $orderBy = in_array($request->order_by, ['id', 'name', 'email', 'created_at', 'last_activity']) ? $request->order_by : 'created_at';
        $orderType = in_array(strtolower($request->order_type), ['asc', 'desc']) ? strtolower($request->order_type) : 'desc';
        $query->orderBy($orderBy, $orderType);

        $resultsPerPage = in_array((int)$request->results_per_page, [10, 25, 50, 100]) ? (int)$request->results_per_page : 25;

        $users = $query->paginate($resultsPerPage)->withQueryString();

        return view('admin.modules.users', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'adminUsers',
            'proUsers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users,email',
            'password' => 'required|string|min:6',
            'status' => 'required|in:0,1',
            'type' => 'required|in:0,1',
            'plan_id' => 'required|string|max:32',
            'plan_expiration_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => (int)$request->status,
            'type' => (int)$request->type,
            'plan_id' => $request->plan_id,
            'plan_expiration_date' => $request->plan_expiration_date ? date('Y-m-d H:i:s', strtotime($request->plan_expiration_date)) : null,
            'email_verified_at' => now(),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna ' . $user->name . ' berhasil dibuat!',
                'data' => $user
            ]);
        }

        return back()->with('success', 'User berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:0,1',
            'type' => 'required|in:0,1',
            'plan_id' => 'required|string|max:32',
            'plan_expiration_date' => 'nullable|date',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => (int)$request->status,
            'type' => (int)$request->type,
            'plan_id' => $request->plan_id,
            'plan_expiration_date' => $request->plan_expiration_date ? date('Y-m-d H:i:s', strtotime($request->plan_expiration_date)) : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna ' . $user->name . ' berhasil diperbarui!',
                'data' => $user
            ]);
        }

        return back()->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        if (Auth::id() == $id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun Anda sendiri!'
                ], 422);
            }
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus!'
            ]);
        }

        return back()->with('success', 'User berhasil dihapus!');
    }

    public function loginAs($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Berhasil login sebagai {$user->name}!");
    }
}
