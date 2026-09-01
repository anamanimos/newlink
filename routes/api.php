<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\LinkController;
use App\Http\Controllers\Api\V1\BiolinkController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\DomainController;
use App\Http\Controllers\Api\V1\PixelController;
use App\Http\Controllers\Api\V1\StatsController;
use App\Http\Controllers\Api\V1\QrCodeController;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('api.auth')->group(function () {
    
    // User / Profile
    Route::get('/user', [UserController::class, 'profile'])->name('api.v1.user.profile');
    Route::post('/user/regenerate-key', [UserController::class, 'regenerateKey'])->name('api.v1.user.regenerate-key');

    // QR Codes
    Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('api.v1.qr-codes.index');
    Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('api.v1.qr-codes.store');
    Route::post('/qr-codes/generate', [QrCodeController::class, 'generate'])->name('api.v1.qr-codes.generate');
    Route::get('/qr-codes/{id}', [QrCodeController::class, 'show'])->name('api.v1.qr-codes.show');
    Route::put('/qr-codes/{id}', [QrCodeController::class, 'update'])->name('api.v1.qr-codes.update');
    Route::delete('/qr-codes/{id}', [QrCodeController::class, 'destroy'])->name('api.v1.qr-codes.destroy');

    // Links (Shortened Links)
    Route::get('/links', [LinkController::class, 'index'])->name('api.v1.links.index');
    Route::post('/links', [LinkController::class, 'store'])->name('api.v1.links.store');
    Route::get('/links/{id}', [LinkController::class, 'show'])->name('api.v1.links.show');
    Route::put('/links/{id}', [LinkController::class, 'update'])->name('api.v1.links.update');
    Route::delete('/links/{id}', [LinkController::class, 'destroy'])->name('api.v1.links.destroy');

    // Biolinks
    Route::get('/biolinks', [BiolinkController::class, 'index'])->name('api.v1.biolinks.index');
    Route::post('/biolinks', [BiolinkController::class, 'store'])->name('api.v1.biolinks.store');
    Route::get('/biolinks/{id}', [BiolinkController::class, 'show'])->name('api.v1.biolinks.show');
    Route::post('/biolinks/{id}/blocks', [BiolinkController::class, 'addBlock'])->name('api.v1.biolinks.add-block');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('api.v1.projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('api.v1.projects.store');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('api.v1.projects.destroy');

    // Domains
    Route::get('/domains', [DomainController::class, 'index'])->name('api.v1.domains.index');

    // Pixels
    Route::get('/pixels', [PixelController::class, 'index'])->name('api.v1.pixels.index');
    Route::post('/pixels', [PixelController::class, 'store'])->name('api.v1.pixels.store');

    // Statistics
    Route::get('/statistics', [StatsController::class, 'index'])->name('api.v1.statistics.index');
});
