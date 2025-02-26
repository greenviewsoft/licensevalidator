<?php

namespace Tipusultan\LicenseValidator\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;

class LicenseValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Register middleware globally
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('secure.validation', \Tipusultan\LicenseValidator\Middleware\SecureValidationKey::class);

        // Validate license on every request
        $this->validateLicense();
    }

    private function validateLicense(): bool
    {
        $cacheKey = 'license_validation';

        if (Cache::get($cacheKey, false)) {
            return true;
        }

        $license_key = env('PUSHER_VALIDATION_KEY'); 
        $app_domain = app('request')->getHost();
        $api_url = "https://api.greenviewsoft.com/validate.php";

        $response = Http::asForm()->post($api_url, [
            'license_key' => $license_key,
            'domain' => $app_domain,
        ]);

        if (!str_contains($response->body(), '✅ License Verified!')) {
            throw new HttpResponseException(response()->view('license.mismatch', [], 403));
        }

        Cache::put($cacheKey, true, now()->addHours(48));

        return true;
    }
}
