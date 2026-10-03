<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Public\HomepageController;
use App\Http\Controllers\Api\V1\Public\ArticleController;
use App\Http\Controllers\Api\V1\Public\BreakingNewsController;
use App\Http\Controllers\Api\V1\Public\CategoryController;
use App\Http\Controllers\Api\V1\Public\LiveUpdateController;
use App\Http\Controllers\Api\V1\Public\LicenseController;
use App\Http\Controllers\Api\V1\External\SyndicationController;

/*
|--------------------------------------------------------------------------
| Karachi Today V1 REST API Routes & External License Integrations
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Health Check Endpoint
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'status' => 'operational',
            'database' => 'connected',
            'timestamp' => now()->toIso8601String(),
            'version' => 'v1.0'
        ]);
    });

    // Public Read-Only News REST API
    Route::get('/homepage', [HomepageController::class, 'index']);
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/breaking-news', [BreakingNewsController::class, 'index']);
    Route::get('/live-updates', [LiveUpdateController::class, 'index']);

    // License Verification Endpoint
    Route::get('/license/verify', [LicenseController::class, 'verify']);

    // External Client Feed & RSS Syndication
    Route::prefix('external')->group(function () {
        Route::get('/feed.json', [SyndicationController::class, 'jsonFeed']);
        Route::get('/rss.xml', [SyndicationController::class, 'rssFeed']);
    });

    // Authenticated Admin API Namespace
    Route::prefix('admin')->group(function () {
        Route::get('/me', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => 1,
                    'name' => 'Newsroom Lead Editor',
                    'email' => 'editor@karachitoday.com',
                    'role' => 'Administrator'
                ]
            ]);
        });

        // Admin Article Management
        Route::get('/articles', [ArticleController::class, 'index']);
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::post('/articles/{id}/publish', [ArticleController::class, 'publish']);

        // Admin Breaking News Controls
        Route::post('/breaking-news', [BreakingNewsController::class, 'store']);
    });
});
