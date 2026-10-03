<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BreakingNewsController extends Controller
{
    /**
     * Display active breaking news ticker headlines.
     */
    public function index(): JsonResponse
    {
        $headlines = [
            "Malir Expressway Phase-1 fully operational; Qayyumabad to Quaidabad travel time down to 15 mins",
            "IMF Executive Board completes 3rd EFF review; approves $1.32B disbursement for Pakistan",
            "PSX KSE-100 Index trades steadily above 180,000 benchmark following record institutional rally",
            "WAPDA targets June 2027 completion for K-IV bulk water supply system to Karachi",
            "Pakistan IT export remittances hit historic $3.2 Billion annual milestone, up 24% YoY",
            "Arts Council Karachi inaugurates Azadi Festival 2026 with international Sufi Qawwali night"
        ];

        return response()->json([
            'success' => true,
            'data' => $headlines,
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
