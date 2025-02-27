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
        $cacheKey = 'license_validation';
    
        if (!Cache::has($cacheKey)) {
            $license_key = env('PUSHER_VALIDATION_KEY'); 
            $app_domain = request()->getHost();
            $api_url = "https://api.greenviewsoft.com/validate.php";
    
            $response = Http::asForm()->post($api_url, [
                'license_key' => $license_key,
                'domain' => $app_domain,
            ]);

 // Log the full response for debugging
//  Log::info('License Validation Response:', [
//     'response_body' => $response->body(),
//     'http_status' => $response->status(),
// ]);

    
            if (!str_contains($response->body(), '✅ License Verified!')) {
                //Log::error('License Mismatch', ['response' => $response->body()]);
                return response()->view('license.mismatch', ['error_message' => $response->body()], 403);
            }
    
            Cache::put($cacheKey, true, now()->addHours(48)); // Cache for 48  hours
        }
    
        return $next($request);
    }
}
