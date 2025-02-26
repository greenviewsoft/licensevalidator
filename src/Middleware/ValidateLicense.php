<?php

namespace Tipusultan\LicenseValidator\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ValidateLicense
{
    public function handle(Request $request, Closure $next)
    {

        Log::info('✅ ValidateLicense Middleware is Running!');
        $cacheKey = 'license_validation';

        if (!Cache::has($cacheKey)) {
            $license_key = env('PUSHER_VALIDATION_KEY'); 
            $app_domain = request()->getHost();
            $api_url = "https://api.greenviewsoft.com/validate.php";

            $response = Http::asForm()->post($api_url, [
                'license_key' => $license_key,
                'domain' => $app_domain,
            ]);

            if (!str_contains($response->body(), '✅ License Verified!')) {
                return response()->view('license.mismatch', [], 403);
            }

            Cache::put($cacheKey, true, now()->addHours(24)); // Cache for 24 hours
        }

        return $next($request);
    }
}
