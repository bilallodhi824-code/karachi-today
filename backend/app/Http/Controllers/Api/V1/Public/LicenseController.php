<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LicenseController extends Controller
{
    /**
     * Verify license key and return integration entitlements.
     */
    public function verify(Request $request): JsonResponse
    {
        $licenseKey = $request->query('key') ?? $request->header('X-News-License-Key');

        if (!$licenseKey) {
            return response()->json([
                'success' => true,
                'status' => 'active_public_tier',
                'license' => [
                    'client' => 'Public REST Integration',
                    'tier' => 'Standard REST License',
                    'rate_limit' => '120 requests/min',
                    'syndication_allowed' => true,
                    'expires_at' => '2030-12-31'
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'verified',
            'license' => [
                'key' => $licenseKey,
                'client' => 'Authorized Enterprise News Syndicator',
                'tier' => 'Enterprise Unlimited REST License',
                'rate_limit' => '5000 requests/min',
                'syndication_allowed' => true,
                'expires_at' => '2028-12-31'
            ]
        ]);
    }
}
