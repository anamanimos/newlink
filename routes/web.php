<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BiolinkController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/link', [DashboardController::class, 'index'])->defaults('type', 'link')->name('links.index');
    Route::post('/link', [LinkController::class, 'store'])->name('links.store');
    Route::get('/link/check-availability', [LinkController::class, 'checkAvailability'])->name('links.check');
    Route::post('/link/bulk-action', [LinkController::class, 'bulkAction'])->name('links.bulk');
    Route::get('/link/{id}', [LinkController::class, 'show'])->name('links.show');
    Route::put('/link/{id}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/link/{id}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::post('/link/{id}/toggle-status', [LinkController::class, 'toggleStatus'])->name('links.toggle');
    
    Route::get('/biolink', [DashboardController::class, 'index'])->defaults('type', 'biolink')->name('biolinks.index');
    Route::get('/biolink/{id}', [LinkController::class, 'show'])->name('biolinks.show');
    
    // Biolink Builder Routes
    Route::get('/biolink/{id}/builder', [BiolinkController::class, 'builder'])->name('biolinks.builder');
    Route::put('/biolink/{id}/settings', [BiolinkController::class, 'updateSettings'])->name('biolinks.settings.update');
    Route::post('/biolink/{id}/blocks', [BiolinkController::class, 'storeBlock'])->name('biolinks.blocks.store');
    Route::put('/biolink/{id}/blocks/{blockId}', [BiolinkController::class, 'updateBlock'])->name('biolinks.blocks.update');
    Route::delete('/biolink/{id}/blocks/{blockId}', [BiolinkController::class, 'destroyBlock'])->name('biolinks.blocks.destroy');
    Route::post('/biolink/{id}/blocks/reorder', [BiolinkController::class, 'reorderBlocks'])->name('biolinks.blocks.reorder');
    Route::get('/biolink/block/{id}/analytics', [BiolinkController::class, 'blockAnalytics'])->name('biolinks.blocks.analytics');

    Route::get('/qrcode', [DashboardController::class, 'index'])->defaults('type', 'qrcode')->name('qrcodes.index');
    
    // Projects Module
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/domains', [\App\Http\Controllers\DomainController::class, 'index'])->name('domains.index');
    Route::post('/domains', [\App\Http\Controllers\DomainController::class, 'store'])->name('domains.store');
    Route::put('/domains/{id}', [\App\Http\Controllers\DomainController::class, 'update'])->name('domains.update');
    Route::delete('/domains/{id}', [\App\Http\Controllers\DomainController::class, 'destroy'])->name('domains.destroy');

    Route::get('/pixels', function () { 
        return view('modules.pixels', ['pixels' => \App\Models\Pixel::where('user_id', auth()->id())->latest()->get()]); 
    })->name('pixels.index');
    Route::get('/profile', function () { return view('modules.profile'); })->name('profile.edit');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', function () { return view('admin.modules.users', ['users' => \App\Models\User::latest()->get()]); })->name('admin.users');
    Route::get('/domains', [\App\Http\Controllers\Admin\DomainController::class, 'index'])->name('admin.domains');
    Route::post('/domains', [\App\Http\Controllers\Admin\DomainController::class, 'store'])->name('admin.domains.store');
    Route::put('/domains/{id}', [\App\Http\Controllers\Admin\DomainController::class, 'update'])->name('admin.domains.update');
    Route::delete('/domains/{id}', [\App\Http\Controllers\Admin\DomainController::class, 'destroy'])->name('admin.domains.destroy');
    Route::get('/settings/{tab?}', [AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/plans', function () { return view('admin.modules.plans'); })->name('admin.plans');
    Route::get('/links', [AdminController::class, 'links'])->name('admin.links');
    Route::post('/links/{id}/toggle-verify', [AdminController::class, 'toggleVerify'])->name('admin.links.toggle-verify');
});

// Root redirects to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Production Legacy Import Endpoint
Route::get('/api/import-legacy', function (Request $request) {
    // Basic protection using a secret key
    $secret = env('IMPORT_SECRET', 'rahasia-newlink-123');
    
    if ($request->get('secret') !== $secret) {
        return response()->json(['error' => 'Unauthorized. Invalid secret key.'], 403);
    }

    // Prevent timeout for large databases
    set_time_limit(0);

    try {
        // Run the artisan command
        Artisan::call('app:import-legacy-data');
        $output = Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Legacy data imported successfully.',
            'log' => explode("\n", trim($output))
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred during import.',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Production SQL Import Endpoint (Bypasses phpMyAdmin limitations)
Route::get('/api/restore-sql', function (Request $request) {
    $secret = env('IMPORT_SECRET', 'rahasia-newlink-123');
    
    if ($request->get('secret') !== $secret) {
        return response()->json(['error' => 'Unauthorized. Invalid secret key.'], 403);
    }

    set_time_limit(0);

    try {
        $sqlPath = base_path('newlink_production_ready.sql');
        if (!file_exists($sqlPath)) {
            return response()->json(['error' => 'SQL file not found on server. Did you pull it?'], 404);
        }

        \Illuminate\Support\Facades\DB::unprepared(file_get_contents($sqlPath));
        
        return response()->json([
            'success' => true,
            'message' => 'Database successfully restored from SQL dump.'
        ]);
    } catch (\Exception $e) {
        $errorMessage = $e->getMessage();
        if (!mb_check_encoding($errorMessage, 'UTF-8')) {
            $errorMessage = mb_convert_encoding($errorMessage, 'UTF-8', 'UTF-8');
        }
        return response()->json([
            'success' => false,
            'message' => 'An error occurred during SQL execution.',
            'error' => $errorMessage
        ], 500);
    }
});

// Public Biolink Block Redirect and Click Tracking Route
Route::get('/biolink/block/{id}/redirect', [RedirectController::class, 'redirectBlock'])->name('biolinks.blocks.redirect');

// Wildcard Route for Redirects (MUST BE LAST)
Route::get('/{slug}', [RedirectController::class, 'resolve'])->name('redirect.resolve');
