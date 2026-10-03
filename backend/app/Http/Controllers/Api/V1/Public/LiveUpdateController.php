<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LiveUpdateController extends Controller
{
    /**
     * Display live updates & live blog timeline feed.
     */
    public function index(): JsonResponse
    {
        $liveUpdates = [
            [
                'id' => 1,
                'time' => '03:25 PM PKT',
                'headline' => 'Malir Expressway Phase-1 Operational; Travel Times Drop drastically',
                'body' => 'Commuters report smooth traffic flows between Qayyumabad and Quaidabad as secondary loop ramps near DHA Phase 8 enter final surfacing.',
                'tag' => 'INFRASTRUCTURE',
                'slug' => 'malir-expressway-operational-phase-1'
            ],
            [
                'id' => 2,
                'time' => '02:40 PM PKT',
                'headline' => 'PSX KSE-100 Benchmark Consolidates Above 180,000 Level',
                'body' => 'Commercial banking, technology exporters, and energy sector stocks lead continuous institutional trading volume.',
                'tag' => 'MARKETS',
                'slug' => 'psx-kse100-historic-levels'
            ],
            [
                'id' => 3,
                'time' => '01:15 PM PKT',
                'headline' => 'WAPDA Reaffirms June 2027 Completion Target for K-IV Conduit Pipeline',
                'body' => 'Bulk intake system construction reaches 78% overall progress; Sindh Water Board coordinates distribution main upgrades.',
                'tag' => 'CIVIC WATER',
                'slug' => 'k-iv-water-project-june-2027-completion'
            ],
            [
                'id' => 4,
                'time' => '11:50 AM PKT',
                'headline' => 'National Champions Cup 2026 Opening Matches Announced',
                'body' => 'Multan and Karachi venues prepared for elite domestic action featuring Shaheen Afridi, Shadab Khan, and Saim Ayub.',
                'tag' => 'SPORTS',
                'slug' => 'national-champions-cup-2026-kickoff'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $liveUpdates,
            'last_updated' => now()->toIso8601String()
        ]);
    }
}
