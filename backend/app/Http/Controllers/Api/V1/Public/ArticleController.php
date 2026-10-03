<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Display a listing of news articles with pagination & filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $category = $request->query('category');
        $tag = $request->query('tag');
        $search = $request->query('q');
        $limit = (int) $request->query('limit', 15);
        $page = (int) $request->query('page', 1);

        $mockArticles = [
            [
                'slug' => 'malir-expressway-operational-phase-1',
                'title' => 'Malir Expressway Phase-1 Fully Operational; Loop Connections to DHA & Link Roads Accelerated',
                'subhead' => 'Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks as city planners review final integrations for August 2026.',
                'author' => 'Adeel Ahmed',
                'author_role' => 'Senior Infrastructure Reporter',
                'date' => 'Aug 10, 2026',
                'category' => 'Karachi',
                'image' => '/images/malir-expressway.png',
                'tags' => ['Karachi', 'Malir Expressway', 'Infrastructure', 'Transport']
            ],
            [
                'slug' => 'k-iv-water-project-june-2027-completion',
                'title' => 'WAPDA Advances K-IV Bulk Water Supply Pipeline; Target Completion Set for Mid-2027',
                'subhead' => 'Bulk intake system engineered to pump 260 million gallons per day (MGD) of freshwater into Karachi municipal reservoirs.',
                'author' => 'Zainab Raza',
                'author_role' => 'Civic Affairs Correspondent',
                'date' => 'Aug 8, 2026',
                'category' => 'Karachi',
                'image' => '/images/green-line.png',
                'tags' => ['Karachi', 'K-IV', 'Water Supply', 'WAPDA']
            ],
            [
                'slug' => 'imf-eff-review-completed-1-3b',
                'title' => 'IMF Executive Board Completes 3rd EFF Review; Pakistan Secures $1.32 Billion Disbursement',
                'subhead' => 'Macroeconomic stability measures pave the way for 4th review in September 2026.',
                'author' => 'Bilal Khan',
                'author_role' => 'Chief Economics Editor',
                'date' => 'Aug 9, 2026',
                'category' => 'Pakistan',
                'image' => '/images/psx-stock-market.png',
                'tags' => ['Pakistan', 'IMF', 'Economy', 'Finance']
            ],
            [
                'slug' => 'psx-kse100-historic-levels',
                'title' => 'Pakistan Stock Exchange Maintains Bullish Stance Above 180,000 Benchmark',
                'subhead' => 'KSE-100 index exhibits strong institutional buying following historic 191,000 peak.',
                'author' => 'Financial Analyst Bureau',
                'author_role' => 'Capital Markets Desk',
                'date' => 'Aug 11, 2026',
                'category' => 'Business',
                'image' => '/images/psx-stock-market.png',
                'tags' => ['Business', 'PSX', 'KSE-100', 'Stock Market']
            ],
            [
                'slug' => 'champions-trophy-2025-retrospective',
                'title' => 'ICC Champions Trophy Legacy: World Cricket Celebrates Pakistan Flawless Hosting',
                'subhead' => 'Reflecting on the historic tournament across Karachi, Lahore, and Rawalpindi.',
                'author' => 'Kamran Akram',
                'author_role' => 'Senior Sports Editor',
                'date' => 'Mar 10, 2025',
                'category' => 'Sports',
                'image' => '/images/champions-trophy.png',
                'tags' => ['Sports', 'Cricket', 'Champions Trophy', 'PCB']
            ],
            [
                'slug' => 'arts-council-azadi-qawwali-night-2026',
                'title' => 'Arts Council Karachi Hosts Grand Azadi Festival & International Qawwali Night',
                'subhead' => 'Renowned vocalists perform for thousands of art lovers in Saddar heritage hall.',
                'author' => 'Cultural Bureau',
                'author_role' => 'Arts & Heritage Desk',
                'date' => 'Aug 11, 2026',
                'category' => 'Culture',
                'image' => '/images/arts-council.png',
                'tags' => ['Culture', 'Arts Council', 'Karachi', 'Music']
            ]
        ];

        // Apply filtering
        $filtered = collect($mockArticles);

        if ($category) {
            $filtered = $filtered->filter(fn($item) => strtolower($item['category']) === strtolower($category));
        }

        if ($tag) {
            $filtered = $filtered->filter(fn($item) => in_array(strtolower($tag), array_map('strtolower', $item['tags'])));
        }

        if ($search) {
            $q = strtolower($search);
            $filtered = $filtered->filter(fn($item) => 
                str_contains(strtolower($item['title']), $q) || 
                str_contains(strtolower($item['subhead']), $q) ||
                str_contains(strtolower($item['category']), $q)
            );
        }

        $total = $filtered->count();
        $paginated = $filtered->slice(($page - 1) * $limit, $limit)->values();

        return response()->json([
            'success' => true,
            'data' => $paginated,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit) ?: 1
            ]
        ]);
    }

    /**
     * Display single article detail by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $article = [
            'slug' => $slug,
            'title' => 'Malir Expressway Phase-1 Fully Operational; Loop Connections to DHA & Link Roads Accelerated',
            'subhead' => 'Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks as city planners review final integrations for August 2026.',
            'author' => 'Adeel Ahmed',
            'author_role' => 'Senior Infrastructure & Urban Transport Reporter',
            'date' => 'Aug 10, 2026',
            'category' => 'Karachi',
            'image' => '/images/malir-expressway.png',
            'image_caption' => 'Aerial view of the newly commissioned Malir Expressway Phase 1 flyover and riverbed corridor in Karachi.',
            'body' => [
                "KARACHI — Traffic congestion across Karachi's eastern corridors has seen marked relief following full operationalization of Malir Expressway Phase 1, stretching 9.1 kilometers between Qayyumabad and Quaidabad.",
                "Provincial urban planning authorities confirmed on Monday that secondary loop ramp connections connecting directly into DHA Phase 8 and the Ayesha Mosque Link Road are entering final surfacing stages.",
                "The expressway, engineered to bypass high-density residential bottlenecks along Korangi Road, has reduced peak commute times between Clifton and the National Highway from 55 minutes down to less than 15 minutes.",
                "Civil engineers are continuing overnight beautification and LED streetlight installations along the central median ahead of the complete Phase 2 extension towards the Karachi-Hyderabad Motorway (M-9)."
            ],
            'tags' => ['Karachi', 'Malir Expressway', 'Infrastructure', 'Transport', 'Sindh Govt'],
            'views_count' => 14250,
            'reading_time_minutes' => 4,
            'share_url' => "http://localhost:3000/news/{$slug}"
        ];

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }
}
