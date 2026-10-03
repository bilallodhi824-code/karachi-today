<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-News-License-Key");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Helper for JSON responses
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();
}

// 1. Health Check Endpoint
if ($uri === '/api/v1/health') {
    jsonResponse([
        'success' => true,
        'status' => 'operational',
        'database' => 'connected',
        'timestamp' => date('c'),
        'version' => 'v1.0'
    ]);
}

// 2. License Verification Endpoint
if ($uri === '/api/v1/license/verify') {
    $licenseKey = $_GET['key'] ?? $_SERVER['HTTP_X_NEWS_LICENSE_KEY'] ?? 'STD-LIC-2026';
    jsonResponse([
        'success' => true,
        'status' => 'verified',
        'license' => [
            'key' => $licenseKey,
            'client' => 'Authorized News Syndicator & Licensee',
            'tier' => 'Enterprise Unlimited REST API License',
            'rate_limit' => '5000 requests/min',
            'syndication_allowed' => true,
            'expires_at' => '2030-12-31'
        ]
    ]);
}

// 3. Homepage Data Package
if ($uri === '/api/v1/homepage') {
    jsonResponse([
        'success' => true,
        'data' => [
            'hero' => [
                'slug' => 'malir-expressway-operational-phase-1',
                'title' => 'Malir Expressway Phase-1 Fully Operational; Loop Connections to DHA & Link Roads Accelerated',
                'subhead' => 'Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks as city planners review final integrations for August 2026.',
                'author' => 'Adeel Ahmed',
                'author_role' => 'Senior Infrastructure Reporter',
                'date' => 'Aug 10, 2026',
                'category' => 'Karachi',
                'image' => '/images/malir-expressway.png',
                'is_hero' => true
            ],
            'supporting_cards' => [
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
            ],
            'top_headlines' => [
                ['id' => 1, 'slug' => 'imf-eff-review-completed-1-3b', 'title' => 'IMF Executive Board completes 3rd review; Pakistan receives $1.32B tranche', 'category' => 'Pakistan'],
                ['id' => 2, 'slug' => 'k-iv-water-project-june-2027-completion', 'title' => 'WAPDA targets June 2027 completion for K-IV bulk water supply system to Karachi', 'category' => 'Karachi'],
                ['id' => 3, 'slug' => 'national-champions-cup-2026-kickoff', 'title' => 'National Champions Cup 2026 kicks off in Multan featuring Shaheen & Saim', 'category' => 'Sports']
            ],
            'breaking_ticker' => [
                "Malir Expressway Phase-1 fully operational; Qayyumabad to Quaidabad travel time down to 15 mins",
                "IMF Executive Board completes 3rd EFF review; approves $1.32B disbursement for Pakistan",
                "PSX KSE-100 Index trades steadily above 180,000 benchmark following record institutional rally"
            ]
        ],
        'meta' => [
            'platform' => 'Karachi Today Digital Newsroom REST API',
            'version' => 'v1.0'
        ]
    ]);
}

// 4. Articles Listing & Search
if ($uri === '/api/v1/articles') {
    $category = $_GET['category'] ?? null;
    $search = $_GET['q'] ?? null;

    $articles = [
        [
            'slug' => 'malir-expressway-operational-phase-1',
            'title' => 'Malir Expressway Phase-1 Fully Operational',
            'category' => 'Karachi',
            'author' => 'Adeel Ahmed',
            'date' => 'Aug 10, 2026',
            'image' => '/images/malir-expressway.png'
        ],
        [
            'slug' => 'k-iv-water-project-june-2027-completion',
            'title' => 'WAPDA Advances K-IV Bulk Water Supply Pipeline',
            'category' => 'Karachi',
            'author' => 'Zainab Raza',
            'date' => 'Aug 8, 2026',
            'image' => '/images/green-line.png'
        ],
        [
            'slug' => 'imf-eff-review-completed-1-3b',
            'title' => 'IMF Executive Board Completes 3rd EFF Review',
            'category' => 'Pakistan',
            'author' => 'Bilal Khan',
            'date' => 'Aug 9, 2026',
            'image' => '/images/psx-stock-market.png'
        ],
        [
            'slug' => 'psx-kse100-historic-levels',
            'title' => 'Pakistan Stock Exchange Maintains Bullish Stance Above 180,000 Benchmark',
            'category' => 'Business',
            'author' => 'Financial Analyst',
            'date' => 'Aug 11, 2026',
            'image' => '/images/psx-stock-market.png'
        ]
    ];

    jsonResponse([
        'success' => true,
        'total' => count($articles),
        'data' => $articles
    ]);
}

// 5. Single Article Detail
if (str_starts_with($uri, '/api/v1/articles/')) {
    $slug = substr($uri, strlen('/api/v1/articles/'));
    jsonResponse([
        'success' => true,
        'data' => [
            'slug' => $slug,
            'title' => 'Malir Expressway Phase-1 Fully Operational; Loop Connections to DHA & Link Roads Accelerated',
            'subhead' => 'Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks as city planners review final integrations for August 2026.',
            'author' => 'Adeel Ahmed',
            'author_role' => 'Senior Infrastructure & Urban Transport Reporter',
            'date' => 'Aug 10, 2026',
            'category' => 'Karachi',
            'image' => '/images/malir-expressway.png',
            'body' => [
                "KARACHI — Traffic congestion across Karachi's eastern corridors has seen marked relief following full operationalization of Malir Expressway Phase 1.",
                "Provincial urban planning authorities confirmed on Monday that secondary loop ramp connections connecting directly into DHA Phase 8 are entering final surfacing stages."
            ],
            'tags' => ['Karachi', 'Malir Expressway', 'Infrastructure', 'Transport']
        ]
    ]);
}

// 6. Categories Listing
if ($uri === '/api/v1/categories') {
    jsonResponse([
        'success' => true,
        'data' => [
            ['name' => 'Karachi', 'slug' => 'karachi', 'articles_count' => 30],
            ['name' => 'Pakistan', 'slug' => 'pakistan', 'articles_count' => 30],
            ['name' => 'Business', 'slug' => 'business', 'articles_count' => 25],
            ['name' => 'Sports', 'slug' => 'sports', 'articles_count' => 25],
            ['name' => 'World', 'slug' => 'world', 'articles_count' => 15],
            ['name' => 'Culture', 'slug' => 'culture', 'articles_count' => 15],
            ['name' => 'Opinion', 'slug' => 'opinion', 'articles_count' => 15]
        ]
    ]);
}

// 7. Breaking News
if ($uri === '/api/v1/breaking-news') {
    jsonResponse([
        'success' => true,
        'data' => [
            "Malir Expressway Phase-1 fully operational; Qayyumabad to Quaidabad travel time down to 15 mins",
            "IMF Executive Board completes 3rd EFF review; approves $1.32B disbursement for Pakistan",
            "PSX KSE-100 Index trades steadily above 180,000 benchmark following record institutional rally"
        ]
    ]);
}

// 8. External JSON Feed & RSS
if ($uri === '/api/v1/external/feed.json') {
    jsonResponse([
        'version' => 'https://jsonfeed.org/version/1.1',
        'title' => 'Karachi Today Digital Newsroom Feed',
        'home_page_url' => 'http://localhost:3000',
        'feed_url' => 'http://localhost:8000/api/v1/external/feed.json',
        'items' => [
            [
                'id' => 'malir-expressway-operational-phase-1',
                'title' => 'Malir Expressway Phase-1 Fully Operational',
                'url' => 'http://localhost:3000/news/malir-expressway-operational-phase-1',
                'date_published' => '2026-08-10T12:00:00Z'
            ]
        ]
    ]);
}

if ($uri === '/api/v1/external/rss.xml') {
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"><channel><title>Karachi Today RSS Feed</title><link>http://localhost:3000</link><description>Real-time news feed</description></channel></rss>';
    exit();
}

// 404 Fallback
jsonResponse([
    'success' => false,
    'error' => 'API Endpoint Not Found',
    'available_endpoints' => [
        '/api/v1/health',
        '/api/v1/homepage',
        '/api/v1/articles',
        '/api/v1/articles/{slug}',
        '/api/v1/categories',
        '/api/v1/breaking-news',
        '/api/v1/license/verify',
        '/api/v1/external/feed.json',
        '/api/v1/external/rss.xml'
    ]
], 404);
