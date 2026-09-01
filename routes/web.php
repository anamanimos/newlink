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

// Domain Ping Verification Endpoint
Route::get('/_system/domain-ping', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'newlink',
        'server_ip' => \App\Services\DomainSslService::getServerIp(),
        'timestamp' => time()
    ]);
});

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
    Route::get('/biolink/{id}/preview', [BiolinkController::class, 'preview'])->name('biolinks.preview');
    Route::put('/biolink/{id}/settings', [BiolinkController::class, 'updateSettings'])->name('biolinks.settings.update');
    Route::post('/biolink/{id}/blocks', [BiolinkController::class, 'storeBlock'])->name('biolinks.blocks.store');
    Route::put('/biolink/{id}/blocks/{blockId}', [BiolinkController::class, 'updateBlock'])->name('biolinks.blocks.update');
    Route::delete('/biolink/{id}/blocks/{blockId}', [BiolinkController::class, 'destroyBlock'])->name('biolinks.blocks.destroy');
    Route::patch('/biolink/{id}/blocks/{blockId}/toggle', [BiolinkController::class, 'toggleBlock'])->name('biolinks.blocks.toggle');
    Route::post('/biolink/{id}/blocks/reorder', [BiolinkController::class, 'reorderBlocks'])->name('biolinks.blocks.reorder');
    Route::get('/biolink/block/{id}/analytics', [BiolinkController::class, 'blockAnalytics'])->name('biolinks.blocks.analytics');
    Route::get('/biolink/{id}/export-leads', [BiolinkController::class, 'exportLeads'])->name('biolinks.leads.export');
    Route::post('/biolink/{id}/duplicate', [BiolinkController::class, 'duplicate'])->name('biolinks.duplicate');

    Route::get('/warotator', [DashboardController::class, 'index'])->defaults('type', 'warotator')->name('warotators.index');
    Route::get('/warotator/create', [\App\Http\Controllers\WaRotatorController::class, 'create'])->name('warotators.create');
    Route::post('/warotator', [\App\Http\Controllers\WaRotatorController::class, 'store'])->name('warotators.store');
    Route::get('/warotator/{id}', [LinkController::class, 'show'])->name('warotators.show');
    Route::get('/warotator/{id}/builder', [\App\Http\Controllers\WaRotatorController::class, 'builder'])->name('warotators.builder');
    Route::get('/warotator/{id}/preview', [\App\Http\Controllers\WaRotatorController::class, 'preview'])->name('warotators.preview');
    Route::put('/warotator/{id}/settings', [\App\Http\Controllers\WaRotatorController::class, 'updateSettings'])->name('warotators.settings.update');
    Route::get('/warotator/{id}/export-leads', [\App\Http\Controllers\WaRotatorController::class, 'exportLeads'])->name('warotators.leads.export');

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
    Route::post('/domains/{id}/verify-dns', [\App\Http\Controllers\DomainController::class, 'verifyDns'])->name('domains.verify-dns');
    Route::post('/domains/{id}/provision-ssl', [\App\Http\Controllers\DomainController::class, 'provisionSsl'])->name('domains.provision-ssl');

    Route::get('/pixels', [\App\Http\Controllers\PixelController::class, 'index'])->name('pixels.index');
    Route::post('/pixels', [\App\Http\Controllers\PixelController::class, 'store'])->name('pixels.store');
    Route::put('/pixels/{id}', [\App\Http\Controllers\PixelController::class, 'update'])->name('pixels.update');
    Route::delete('/pixels/{id}', [\App\Http\Controllers\PixelController::class, 'destroy'])->name('pixels.destroy');
    Route::get('/clicks', [\App\Http\Controllers\ClickLogController::class, 'index'])->name('clicks.index');
    Route::get('/account/api', [\App\Http\Controllers\UserApiController::class, 'index'])->name('user.api');
    Route::post('/account/api/regenerate', [\App\Http\Controllers\UserApiController::class, 'regenerate'])->name('user.api.regenerate');
    Route::get('/profile', function () { return view('modules.profile'); })->name('profile.edit');

    // Online Tools Module
    Route::get('/tools', [\App\Http\Controllers\ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/whatsapp-link-generator', [\App\Http\Controllers\ToolController::class, 'whatsappLinkGenerator'])->name('tools.whatsapp-link-generator');
    Route::get('/tools/utm-link-generator', [\App\Http\Controllers\ToolController::class, 'utmLinkGenerator'])->name('tools.utm-link-generator');
    Route::get('/tools/slug-generator', [\App\Http\Controllers\ToolController::class, 'slugGenerator'])->name('tools.slug-generator');
    Route::get('/tools/password-generator', [\App\Http\Controllers\ToolController::class, 'passwordGenerator'])->name('tools.password-generator');
    Route::get('/tools/uuid-generator', [\App\Http\Controllers\ToolController::class, 'uuidGenerator'])->name('tools.uuid-generator');
    Route::get('/tools/lorem-ipsum-generator', [\App\Http\Controllers\ToolController::class, 'loremIpsumGenerator'])->name('tools.lorem-ipsum-generator');
});

// Public API Documentation
Route::get('/api-docs', [\App\Http\Controllers\ApiDocsController::class, 'index'])->name('api-docs');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{id}/login-as', [\App\Http\Controllers\Admin\UserController::class, 'loginAs'])->name('admin.users.login-as');
    Route::get('/domains', [\App\Http\Controllers\Admin\DomainController::class, 'index'])->name('admin.domains');
    Route::post('/domains', [\App\Http\Controllers\Admin\DomainController::class, 'store'])->name('admin.domains.store');
    Route::put('/domains/{id}', [\App\Http\Controllers\Admin\DomainController::class, 'update'])->name('admin.domains.update');
    Route::delete('/domains/{id}', [\App\Http\Controllers\Admin\DomainController::class, 'destroy'])->name('admin.domains.destroy');
    Route::post('/domains/{id}/verify-dns', [\App\Http\Controllers\Admin\DomainController::class, 'verifyDns'])->name('admin.domains.verify-dns');
    Route::post('/domains/{id}/provision-ssl', [\App\Http\Controllers\Admin\DomainController::class, 'provisionSsl'])->name('admin.domains.provision-ssl');
    Route::get('/settings/{tab?}', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/{tab?}', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('admin.plans');
    Route::post('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'store'])->name('admin.plans.store');
    Route::put('/plans/{id}', [\App\Http\Controllers\Admin\PlanController::class, 'update'])->name('admin.plans.update');
    Route::delete('/plans/{id}', [\App\Http\Controllers\Admin\PlanController::class, 'destroy'])->name('admin.plans.destroy');
    Route::get('/statistics/{type?}', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('admin.statistics');
    Route::get('/links', [AdminController::class, 'links'])->name('admin.links');
    Route::post('/links/bulk-action', [AdminController::class, 'bulkAction'])->name('admin.links.bulk-action');
    Route::post('/links/{id}/toggle-verify', [AdminController::class, 'toggleVerify'])->name('admin.links.toggle-verify');
    Route::post('/links/{id}/toggle-status', [AdminController::class, 'toggleStatusLink'])->name('admin.links.toggle-status');
    Route::delete('/links/{id}', [AdminController::class, 'destroyLink'])->name('admin.links.destroy');
    Route::get('/sync-legacy/check', [\App\Http\Controllers\Admin\SyncController::class, 'checkConnection'])->name('admin.sync.check');
    Route::post('/sync-legacy/step', [\App\Http\Controllers\Admin\SyncController::class, 'processStep'])->name('admin.sync.step');
});

// Root route (Handles domain custom_index_url and auth redirects)
Route::get('/', [RedirectController::class, 'root'])->name('root');

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

Route::get('/biolink/block/{id}/redirect', [RedirectController::class, 'redirectBlock'])->name('biolinks.blocks.redirect');
Route::post('/warotator/{id}/whatsapp-submit', [RedirectController::class, 'whatsappRotatorSubmit'])->name('warotators.whatsapp.submit');

// Wildcard Route for Redirects (MUST BE LAST)
Route::get('/{slug}', [RedirectController::class, 'resolve'])->name('redirect.resolve');
