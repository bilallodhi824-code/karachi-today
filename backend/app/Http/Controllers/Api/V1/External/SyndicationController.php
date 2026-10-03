<?php

namespace App\Http\Controllers\Api\V1\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SyndicationController extends Controller
{
    /**
     * Standard JSON Syndication Feed for licensed partners.
     */
    public function jsonFeed(): JsonResponse
    {
        $feed = [
            'version' => 'https://jsonfeed.org/version/1.1',
            'title' => 'Karachi Today Digital Newsroom Feed',
            'home_page_url' => 'http://localhost:3000',
            'feed_url' => 'http://localhost:8000/api/v1/external/feed.json',
            'description' => 'Official news feed for Karachi Today digital platform.',
            'items' => [
                [
                    'id' => 'malir-expressway-operational-phase-1',
                    'url' => 'http://localhost:3000/news/malir-expressway-operational-phase-1',
                    'title' => 'Malir Expressway Phase-1 Fully Operational',
                    'summary' => 'Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks.',
                    'image' => 'http://localhost:3000/images/malir-expressway.png',
                    'date_published' => '2026-08-10T12:00:00Z',
                    'author' => ['name' => 'Adeel Ahmed']
                ],
                [
                    'id' => 'psx-kse100-historic-levels',
                    'url' => 'http://localhost:3000/news/psx-kse100-historic-levels',
                    'title' => 'Pakistan Stock Exchange Maintains Bullish Stance Above 180,000 Benchmark',
                    'summary' => 'KSE-100 index exhibits strong institutional buying.',
                    'image' => 'http://localhost:3000/images/psx-stock-market.png',
                    'date_published' => '2026-08-11T09:00:00Z',
                    'author' => ['name' => 'Financial Desk']
                ]
            ]
        ];

        return response()->json($feed);
    }

    /**
     * Standard RSS 2.0 XML Feed for Google News and syndication aggregators.
     */
    public function rssFeed(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $xml .= '<rss version="2.0">' . "\n";
        $xml .= '<channel>' . "\n";
        $xml .= '  <title>Karachi Today RSS News Feed</title>' . "\n";
        $xml .= '  <link>http://localhost:3000</link>' . "\n";
        $xml .= '  <description>Real-time digital news coverage from Karachi and Pakistan</description>' . "\n";
        $xml .= '  <language>en-us</language>' . "\n";
        $xml .= '  <item>' . "\n";
        $xml .= '    <title>Malir Expressway Phase-1 Fully Operational</title>' . "\n";
        $xml .= '    <link>http://localhost:3000/news/malir-expressway-operational-phase-1</link>' . "\n";
        $xml .= '    <description>Key 9.1km corridor connecting Qayyumabad to Quaidabad eases traffic bottlenecks.</description>' . "\n";
        $xml .= '    <pubDate>Mon, 10 Aug 2026 12:00:00 GMT</pubDate>' . "\n";
        $xml .= '  </item>' . "\n";
        $xml .= '</channel>' . "\n";
        $xml .= '</rss>';

        return response($xml, 200, ['Content-Type' => 'text/xml']);
    }
}
