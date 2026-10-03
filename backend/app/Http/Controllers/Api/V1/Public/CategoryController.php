<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display listing of active categories.
     */
    public function index(): JsonResponse
    {
        $categories = [
            ['name' => 'Karachi', 'slug' => 'karachi', 'articles_count' => 30, 'description' => 'Metropolitan news, civic updates & mega infrastructure projects.'],
            ['name' => 'Pakistan', 'slug' => 'pakistan', 'articles_count' => 30, 'description' => 'National politics, federal policies & macroeconomic developments.'],
            ['name' => 'Business', 'slug' => 'business', 'articles_count' => 25, 'description' => 'Stock exchange rallies, banking, exports & corporate growth.'],
            ['name' => 'Sports', 'slug' => 'sports', 'articles_count' => 25, 'description' => 'Cricket, ICC tournaments, domestic leagues & athletics.'],
            ['name' => 'World', 'slug' => 'world', 'articles_count' => 15, 'description' => 'International summits, geopolitics & global trade corridors.'],
            ['name' => 'Culture', 'slug' => 'culture', 'articles_count' => 15, 'description' => 'Arts Council festivals, Sufi qawwali, heritage & literature.'],
            ['name' => 'Opinion', 'slug' => 'opinion', 'articles_count' => 15, 'description' => 'Editorial columns, urban planning op-eds & economic strategy.']
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display category details and associated articles.
     */
    public function show(string $slug): JsonResponse
    {
        $category = [
            'name' => ucfirst($slug),
            'slug' => $slug,
            'description' => "Real-time news and analysis in {$slug} sector across Pakistan.",
            'color_code' => '#38BDF8',
            'articles_count' => 30
        ];

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}
