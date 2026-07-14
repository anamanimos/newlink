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
    
    // Biolink Builder Routes
    Route::get('/biolink/{id}/builder', [BiolinkController::class, 'builder'])->name('biolinks.builder');
    Route::post('/biolink/{id}/blocks', [BiolinkController::class, 'storeBlock'])->name('biolinks.blocks.store');
    Route::put('/biolink/{id}/blocks/{blockId}', [BiolinkController::class, 'updateBlock'])->name('biolinks.blocks.update');
    Route::delete('/biolink/{id}/blocks/{blockId}', [BiolinkController::class, 'destroyBlock'])->name('biolinks.blocks.destroy');
    Route::post('/biolink/{id}/blocks/reorder', [BiolinkController::class, 'reorderBlocks'])->name('biolinks.blocks.reorder');

    Route::get('/qrcode', [DashboardController::class, 'index'])->defaults('type', 'qrcode')->name('qrcodes.index');
    
    // Projects Module
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/domains', function () { 
        return view('modules.domains', ['domains' => \App\Models\Domain::where('user_id', auth()->id())->latest()->get()]); 
    })->name('domains.index');
    Route::get('/pixels', function () { 
        return view('modules.pixels', ['pixels' => \App\Models\Pixel::where('user_id', auth()->id())->latest()->get()]); 
    })->name('pixels.index');
    Route::get('/profile', function () { return view('modules.profile'); })->name('profile.edit');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', function () { return view('admin.modules.users', ['users' => \App\Models\User::latest()->get()]); })->name('admin.users');
    Route::get('/settings/{tab?}', [AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/plans', function () { return view('admin.modules.plans'); })->name('admin.plans');
});

// Root redirects to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Wildcard Route for Redirects (MUST BE LAST)
Route::get('/{slug}', [RedirectController::class, 'resolve'])->name('redirect.resolve');
