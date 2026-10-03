<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    /**
     * Display the curated homepage data package.
     */
    public function index(Request $request): JsonResponse
    {
        $heroCard = [
            'slug' => 'malir-expressway-operational-phase-1',
            'title' => 'Malir Expressway Phase-1 Fully Operational; Loop Connections to DHA & Link Roads Accelerated',
            'subhead' => 'Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks as city planners review final integrations for August 2026.',
            'author' => 'Adeel Ahmed',
            'author_role' => 'Senior Infrastructure Reporter',
            'date' => 'Aug 10, 2026',
            'category' => 'Karachi',
            'image' => '/images/malir-expressway.png',
            'image_caption' => 'Aerial view of Malir Expressway Phase 1 corridor.',
            'is_hero' => true
        ];

        $supportingCards = [
            [
                'slug' => 'psx-kse100-historic-levels',
                'title' => 'PSX KSE-100 Index Trades Steadily Above 180,000 Benchmark',
                'category' => 'Business',
                'date' => 'Aug 11, 2026',
                'image' => '/images/psx-stock-market.png'
            ],
            [
                'slug' => 'champions-trophy-2025-retrospective',
                'title' => 'ICC Champions Trophy Legacy: Cricket World Celebrates Pakistan Venues',
                'category' => 'Sports',
                'date' => 'Mar 10, 2025',
                'image' => '/images/champions-trophy.png'
            ],
            [
                'slug' => 'arts-council-azadi-qawwali-night-2026',
                'title' => 'Arts Council Karachi Inaugurates Azadi Festival 2026 with Sufi Qawwali',
                'category' => 'Culture',
                'date' => 'Aug 11, 2026',
                'image' => '/images/arts-council.png'
            ]
        ];

        $topHeadlines = [
            [
                'id' => 1,
                'slug' => 'imf-eff-review-completed-1-3b',
                'title' => 'IMF Executive Board completes 3rd review; Pakistan receives $1.32B tranche',
                'category' => 'Pakistan',
                'image' => '/images/psx-stock-market.png'
            ],
            [
                'id' => 2,
                'slug' => 'k-iv-water-project-june-2027-completion',
                'title' => 'WAPDA targets June 2027 completion for K-IV bulk water supply system to Karachi',
                'category' => 'Karachi',
                'image' => '/images/green-line.png'
            ],
            [
                'id' => 3,
                'slug' => 'national-champions-cup-2026-kickoff',
                'title' => 'National Champions Cup 2026 kicks off in Multan featuring Shaheen & Saim',
                'category' => 'Sports',
                'image' => '/images/champions-trophy.png'
            ],
            [
                'id' => 4,
                'slug' => 'global-climate-transition-fund-2026',
                'title' => 'Global Climate Summit approves $100B transition fund for resilient coastal cities',
                'category' => 'World',
                'image' => '/images/world-summit.png'
            ],
            [
                'id' => 5,
                'slug' => 'remittances-reach-3-63-billion',
                'title' => 'Pakistan overseas remittances surge 13% YoY to $3.63 Billion in July 2026',
                'category' => 'Economy'
            ]
        ];

        $breakingTicker = [
            "Malir Expressway Phase-1 fully operational; Qayyumabad to Quaidabad travel time down to 15 mins",
            "IMF Executive Board completes 3rd EFF review; approves $1.32B disbursement for Pakistan",
            "PSX KSE-100 Index trades steadily above 180,000 benchmark following record institutional rally",
            "WAPDA targets June 2027 completion for K-IV bulk water supply system to Karachi",
            "Pakistan IT export remittances hit historic $3.2 Billion annual milestone, up 24% YoY",
            "Arts Council Karachi inaugurates Azadi Festival 2026 with international Sufi Qawwali night"
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'hero' => $heroCard,
                'supporting_cards' => $supportingCards,
                'top_headlines' => $topHeadlines,
                'breaking_ticker' => $breakingTicker
            ],
            'meta' => [
                'platform' => 'Karachi Today Digital News API',
                'version' => 'v1.0',
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }
}
